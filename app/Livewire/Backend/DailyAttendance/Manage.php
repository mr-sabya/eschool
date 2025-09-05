<?php

namespace App\Livewire\Backend\DailyAttendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Shift;
use App\Models\Department;
use App\Models\DailyAttendance;
use App\Enums\AttendanceStatus;

class Manage extends Component
{
    use WithPagination;

    public $school_class_id;
    public $class_section_id;
    public $shift_id;
    public $department_id;
    public $attendance_date;

    public $attendances = []; // student_id => status
    public $sections = []; // dynamic sections for selected class

    public $search = '';
    public $sortField = 'roll_number';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Load sections dynamically on class change
    public function onClassChange($value)
    {
        $this->school_class_id = $value ?: null;
        $this->class_section_id = null;
        $this->attendances = [];
        $this->sections = $this->school_class_id ? ClassSection::where('school_class_id', $this->school_class_id)->get() : [];
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Class changed.']);
    }

    public function onSectionChange($value)
    {
        $this->class_section_id = $value ?: null;
        $this->attendances = [];
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Section changed.']);
    }

    public function onShiftChange($value)
    {
        $this->shift_id = $value ?: null;
        $this->attendances = [];
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Shift changed.']);
    }

    public function onDepartmentChange($value)
    {
        $this->department_id = $value ?: null;
        $this->attendances = [];
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Department changed.']);
    }

    public function onDateChange($value)
    {
        $this->attendance_date = $value ?: null;
        $this->prefillExisting();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Date changed.']);
    }

    protected function prefillExisting()
    {
        if (! $this->school_class_id || ! $this->attendance_date) return;

        $existing = DailyAttendance::query()
            ->where('school_class_id', $this->school_class_id)
            ->when($this->class_section_id, fn($q) => $q->where('class_section_id', $this->class_section_id))
            ->when($this->shift_id, fn($q) => $q->where('shift_id', $this->shift_id))
            ->when($this->department_id, fn($q) => $q->where('department_id', $this->department_id))
            ->whereDate('attendance_date', $this->attendance_date)
            ->pluck('status', 'student_id')
            ->toArray();

        $this->attendances = $existing;
    }

    public function updateAttendance($studentId, $status)
    {
        if (! $this->school_class_id || ! $this->attendance_date) return;

        DailyAttendance::updateOrCreate(
            [
                'student_id' => $studentId,
                'school_class_id' => $this->school_class_id,
                'class_section_id' => $this->class_section_id,
                'shift_id' => $this->shift_id,
                'department_id' => $this->department_id,
                'attendance_date' => $this->attendance_date
            ],
            ['status' => $status]
        );

        $this->attendances[$studentId] = $status;

        $student = Student::find($studentId);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Attendance updated for {$student->roll_number} ({$student->user->name})"
        ]);
    }

    public function getStudentsProperty()
    {
        if (!$this->school_class_id || !$this->attendance_date) {
            return collect();
        }

        return Student::query()
            ->where('school_class_id', $this->school_class_id)
            ->when($this->class_section_id, fn($q) => $q->where('class_section_id', $this->class_section_id))
            ->when($this->shift_id, fn($q) => $q->where('shift_id', $this->shift_id))
            ->when($this->department_id, fn($q) => $q->where('department_id', $this->department_id))
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();
    }

    public function markAll($status)
    {
        if (!$this->students) return;

        foreach ($this->students as $student) {
            $this->updateAttendance($student->id, $status);
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => "All students marked as {$status}"]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $students = collect();
        if ($this->school_class_id && $this->attendance_date) {
            $students = Student::query()
                ->where('school_class_id', $this->school_class_id)
                ->when($this->class_section_id, fn($q) => $q->where('class_section_id', $this->class_section_id))
                ->when($this->shift_id, fn($q) => $q->where('shift_id', $this->shift_id))
                ->when($this->department_id, fn($q) => $q->where('department_id', $this->department_id))
                ->where('roll_number', 'like', "%{$this->search}%")
                ->orderBy($this->sortField, $this->sortDirection)
                ->get();
        }

        return view('livewire.backend.daily-attendance.manage', [
            'students' => $students,
            'classes' => SchoolClass::all(),
            'shifts' => Shift::all(),
            'departments' => Department::all(),
            'statuses' => AttendanceStatus::cases(),
        ]);
    }
}
