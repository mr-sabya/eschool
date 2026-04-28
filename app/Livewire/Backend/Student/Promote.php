<?php

namespace App\Livewire\Backend\Student;

use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Models\AcademicSession;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Promote extends Component
{
    public $from_class_id = null;
    public $to_class_id = null;
    public $to_session_id = null;

    public $selected_students = [];
    public $new_roll_numbers = [];
    public $new_department_ids = [];
    public $selectAll = false;

    const HIGHEST_CLASS_NUMERIC_NAME = 10;
    const DEPT_SELECTION_FROM_NUMERIC_NAME = 8;
    const DEPT_SELECTION_TO_NUMERIC_NAME = 9;

    public function mount()
    {
        $currentSession = AcademicSession::where('is_active', true)->first();
        $this->to_session_id = $currentSession ? $currentSession->id : null;
    }

    public function updatedFromClassId()
    {
        $this->reset(['selected_students', 'new_roll_numbers', 'new_department_ids', 'selectAll', 'to_class_id']);
    }

    public function updatedSelectAll($value)
    {
        if ($value && $this->from_class_id) {
            $this->selected_students = User::whereHas('student', function ($q) {
                $q->where('school_class_id', $this->from_class_id)->where('is_passed_out', false);
            })->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected_students = [];
        }
    }

    public function promoteSelectedStudents()
    {
        $this->validate([
            'from_class_id' => 'required',
            'to_class_id' => 'required',
            'to_session_id' => 'required_unless:to_class_id,passed_out',
            'selected_students' => 'required|array|min:1',
        ]);

        $fromClass = SchoolClass::find($this->from_class_id);
        $toClass = ($this->to_class_id !== 'passed_out') ? SchoolClass::find($this->to_class_id) : null;

        // Dept logic
        $isDeptRequired = optional($fromClass)->numeric_name == self::DEPT_SELECTION_FROM_NUMERIC_NAME
            && optional($toClass)->numeric_name == self::DEPT_SELECTION_TO_NUMERIC_NAME;

        if ($isDeptRequired) {
            foreach ($this->selected_students as $userId) {
                if (empty($this->new_department_ids[$userId])) {
                    $this->dispatch('notify', type: 'error', message: 'You must select a department for every selected student.');
                    return;
                }
            }
        }

        DB::beginTransaction();
        try {
            if ($this->to_class_id === 'passed_out') {
                Student::whereIn('user_id', $this->selected_students)->update(['is_passed_out' => true, 'roll_number' => null]);
            } else {
                foreach ($this->selected_students as $userId) {
                    $student = Student::where('user_id', $userId)->first();
                    if ($student) {

                        // ✅ AUTO-ROLL LOGIC START
                        $finalRoll = $student->roll_number;

                        // 1. Check if user typed a manual roll
                        if (!empty($this->new_roll_numbers[$userId])) {
                            $finalRoll = $this->new_roll_numbers[$userId];
                        } else {
                            // 2. Automatic Prefix logic (e.g., 9001 -> 10001)
                            $oldPrefix = (string) $fromClass->numeric_name;
                            $newPrefix = (string) $toClass->numeric_name;
                            $currentRoll = (string) $student->roll_number;

                            // If current roll starts with current class numeric name (e.g. starts with "9")
                            if (str_starts_with($currentRoll, $oldPrefix)) {
                                // Strip the old prefix and prepend the new one
                                $suffix = substr($currentRoll, strlen($oldPrefix));
                                $finalRoll = $newPrefix . $suffix;
                            }
                        }
                        // ✅ AUTO-ROLL LOGIC END

                        $student->update([
                            'school_class_id'     => $this->to_class_id,
                            'academic_session_id' => $this->to_session_id,
                            'roll_number'         => $finalRoll,
                            'department_id'       => $isDeptRequired ? $this->new_department_ids[$userId] : $student->department_id,
                        ]);
                    }
                }
            }

            DB::commit();
            $this->dispatch('notify', type: 'success', message: 'Promotion completed successfully!');
            $this->reset(['selected_students', 'new_roll_numbers', 'new_department_ids', 'selectAll', 'to_class_id']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $allClasses = SchoolClass::orderBy('numeric_name', 'asc')->get();
        $fromClassModel = $this->from_class_id ? $allClasses->firstWhere('id', $this->from_class_id) : null;
        $toClassModel = ($this->to_class_id && $this->to_class_id !== 'passed_out') ? $allClasses->firstWhere('id', $this->to_class_id) : null;

        $students = $this->from_class_id
            ? User::with('student')->whereHas('student', function ($q) {
                $q->where('school_class_id', $this->from_class_id)->where('is_passed_out', false);
            })->orderBy('id', 'asc')->get() : collect();

        $promotionDestinations = $allClasses->where('id', '!=', $this->from_class_id)
            ->map(fn($class) => ['id' => $class->id, 'name' => $class->name])
            ->values()->toArray();

        if (optional($fromClassModel)->numeric_name == self::HIGHEST_CLASS_NUMERIC_NAME) {
            $promotionDestinations[] = ['id' => 'passed_out', 'name' => 'Passed Out (Graduate)'];
        }

        return view('livewire.backend.student.promote', [
            'students' => $students,
            'allClasses' => $allClasses,
            'fromClassModel' => $fromClassModel,
            'toClassModel' => $toClassModel,
            'promotionDestinations' => $promotionDestinations,
            'allDepartments' => Department::all(),
            'academicSessions' => AcademicSession::orderBy('start_date', 'desc')->get(),
        ]);
    }
}
