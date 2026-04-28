<?php

namespace App\Livewire\Backend\Student;

use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PassedOutIndex extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;

    // Filters
    public $filter_class_id = null;
    public $filter_department_id = null;
    public $filter_session_id = null;

    protected $listeners = ['student-restored' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatedFilterClassId()
    {
        $this->resetPage();
    }
    public function updatedFilterSessionId()
    {
        $this->resetPage();
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

    /**
     * Restore a student to active status
     */
    public function restoreStudent($userId)
    {
        $student = Student::where('user_id', $userId)->first();
        if ($student) {
            $student->update(['is_passed_out' => false]);
            $this->dispatch('notify', type: 'success', message: 'Student restored to active list!');
        }
    }

    public function render()
    {
        $students = User::with(['student.schoolClass', 'student.academicSession'])
            ->whereHas('student', function ($query) {
                $query->where('is_passed_out', 1) // ✅ Filter for Passed Out
                    ->when($this->filter_class_id, fn($q) => $q->where('school_class_id', $this->filter_class_id))
                    ->when($this->filter_department_id, fn($q) => $q->where('department_id', $this->filter_department_id))
                    ->when($this->filter_session_id, fn($q) => $q->where('academic_session_id', $this->filter_session_id));
            })
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('student', function ($query) {
                        $query->where('roll_number', 'like', "%{$this->search}%")
                            ->orWhere('phone', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.student.passed-out-index', [
            'students' => $students,
            'allClasses' => SchoolClass::orderBy('numeric_name')->get(),
            'departments' => Department::all(),
            'sessions' => AcademicSession::orderBy('start_date', 'desc')->get(),
        ]);
    }
}
