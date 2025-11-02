<?php

namespace App\Livewire\Backend\Student;

use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class Promote extends Component
{
    // Properties bound to the view's dropdowns
    public $from_class_id;
    public $to_class_id;

    // Properties to hold the full Eloquent models of the selected classes
    public ?SchoolClass $fromClassModel = null;
    public ?SchoolClass $toClassModel = null;

    // Data properties
    public Collection $students;
    public $selected_students = [];
    public $new_roll_numbers = [];
    public $new_department_ids = [];
    public $selectAll = false;

    // Constants based on your model's data
    const HIGHEST_CLASS_NUMERIC_NAME = 10;
    const DEPT_SELECTION_FROM_NUMERIC_NAME = 8;
    const DEPT_SELECTION_TO_NUMERIC_NAME = 9;


    public function mount()
    {
        $this->students = new Collection();
    }

    /**
     * Livewire hook that runs automatically when 'from_class_id' changes.
     * It fetches the full model details and loads the students.
     */
    public function updatedFromClassId($value)
    {
        $this->fromClassModel = SchoolClass::find($value);
        $this->fetchStudents();
    }

    /**
     * Livewire hook that runs automatically when 'to_class_id' changes.
     * It fetches the full model details.
     */
    public function updatedToClassId($value)
    {
        if ($value && $value !== 'passed_out') {
            $this->toClassModel = SchoolClass::find($value);
        } else {
            $this->toClassModel = null;
        }
    }

    public function updatedSelectAll($value)
    {
        $this->selected_students = $value
            ? $this->students->pluck('id')->map(fn($id) => (string) $id)->toArray()
            : [];
    }

    public function fetchStudents()
    {
        if ($this->from_class_id) {
            $this->students = User::with('student.schoolClass')
                ->whereHas('student', fn($q) => $q->where('school_class_id', $this->from_class_id)->where('is_passed_out', false))
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $this->students = new Collection();
        }
        // Reset dependent selections
        $this->reset(['selected_students', 'new_roll_numbers', 'new_department_ids', 'selectAll', 'to_class_id', 'toClassModel']);
    }

    public function promoteSelectedStudents()
    {
        $this->validate([
            'from_class_id' => 'required',
            'to_class_id' => 'required',
            'selected_students' => 'required|array|min:1',
        ]);

        // Validation logic using the correct model properties
        $isDepartmentSelectionRequired = optional($this->fromClassModel)->numeric_name == self::DEPT_SELECTION_FROM_NUMERIC_NAME
            && optional($this->toClassModel)->numeric_name == self::DEPT_SELECTION_TO_NUMERIC_NAME;

        if ($isDepartmentSelectionRequired) {
            foreach ($this->selected_students as $student_id) {
                if (empty($this->new_department_ids[$student_id])) {
                    $this->dispatch('notify', 'Error: You must select a department for every selected student.');
                    return; // Stop the process
                }
            }
        }

        DB::beginTransaction();
        try {
            if ($this->to_class_id === 'passed_out') {
                $this->handlePassedOutStudents();
            } else {
                $this->handleRegularPromotion($isDepartmentSelectionRequired);
            }
            DB::commit();
            $this->dispatch('notify', 'Action completed successfully!');
            $this->reset();
            $this->mount();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function handlePassedOutStudents()
    {
        Student::whereIn('user_id', $this->selected_students)->update([
            'is_passed_out' => true,
            'school_class_id' => null,
            'roll_number' => null,
            'department_id' => null,
        ]);
    }

    private function handleRegularPromotion(bool $isDepartmentSelectionRequired)
    {
        $this->validate(['to_class_id' => 'exists:school_classes,id']);
        foreach ($this->selected_students as $student_user_id) {
            $student = Student::where('user_id', $student_user_id)->first();
            if ($student) {
                $update_data = [
                    'school_class_id' => $this->to_class_id,
                    'roll_number' => $this->new_roll_numbers[$student_user_id] ?? $student->roll_number,
                ];
                if ($isDepartmentSelectionRequired) {
                    $update_data['department_id'] = $this->new_department_ids[$student_user_id];
                }
                $student->update($update_data);
            }
        }
    }

    public function render()
    {
        $allClasses = SchoolClass::orderBy('numeric_name', 'asc')->get();
        $baseDestinations = $allClasses->where('id', '!=', $this->from_class_id);

        // Convert to a simple array to prevent any blade errors
        $promotionDestinations = $baseDestinations
            ->map(fn($class) => ['id' => $class->id, 'name' => $class->name])
            ->values()->toArray();

        // Add "Passed Out" option if the highest class is selected, using the correct property
        if (optional($this->fromClassModel)->numeric_name == self::HIGHEST_CLASS_NUMERIC_NAME) {
            $promotionDestinations[] = ['id' => 'passed_out', 'name' => 'Passed Out (Graduate)'];
        }

        return view('livewire.backend.student.promote', [
            'allClasses' => $allClasses,
            'promotionDestinations' => $promotionDestinations,
            'allDepartments' => Department::all(),
        ]);
    }
}
