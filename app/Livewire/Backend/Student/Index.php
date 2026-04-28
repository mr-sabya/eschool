<?php

namespace App\Livewire\Backend\Student;

use App\Exports\SeatPlanExport;
use App\Exports\StudentsExport;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\AcademicSession;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Search and Sorting
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 25;

    // Modals & IDs
    public $confirmingDelete = false;
    public $deleteId = null;
    public $confirmingBulkSessionUpdate = false;

    // Filter Properties
    public $filter_class_id = null;
    public $filter_section_id = null;
    public $filter_department_id = null;
    public $filter_session_id = null; // Filter by session
    public $new_session_id = null;    // Used for bulk update

    protected $listeners = ['student-saved' => '$refresh'];

    // Reset pagination when any filter changes
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedFilterClassId()
    {
        $this->resetPage();
        $this->filter_section_id = null;
    }
    public function updatedFilterSectionId()
    {
        $this->resetPage();
    }
    public function updatedFilterDepartmentId()
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

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            $user = User::findOrFail($this->deleteId);
            $user->delete();
            $this->dispatch('notify', 'Student deleted successfully!');
            $this->confirmingDelete = false;
            $this->deleteId = null;
        }
    }

    /**
     * Computed property to get sections for the selected class
     */
    public function getFilteredSectionsProperty()
    {
        if ($this->filter_class_id) {
            return ClassSection::where('school_class_id', $this->filter_class_id)->get();
        }
        return collect();
    }

    public function resetFilters()
    {
        $this->reset(['filter_class_id', 'filter_section_id', 'filter_department_id', 'filter_session_id', 'search']);
        $this->resetPage();
    }

    /**
     * Perform Bulk Update on filtered students
     */
    public function updateFilteredSessions()
    {
        if (!$this->new_session_id) {
            $this->dispatch('notify', 'Please select a target session.');
            return;
        }

        // Apply same filters as the main query to identify target students
        $query = Student::query();

        if ($this->filter_class_id) $query->where('school_class_id', $this->filter_class_id);
        if ($this->filter_section_id) $query->where('class_section_id', $this->filter_section_id);
        if ($this->filter_department_id) $query->where('department_id', $this->filter_department_id);
        if ($this->filter_session_id) $query->where('academic_session_id', $this->filter_session_id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('roll_number', 'like', "%{$this->search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%"));
            });
        }

        $count = $query->count();

        if ($count > 0) {
            $query->update(['academic_session_id' => $this->new_session_id]);
            $this->dispatch('notify', "Successfully updated $count students to the new session!");
            $this->confirmingBulkSessionUpdate = false;
            $this->new_session_id = null;
        } else {
            $this->dispatch('notify', 'No students found matching current filters.');
        }
    }

    public function export()
    {
        return Excel::download(new StudentsExport($this->search, $this->filter_class_id, $this->filter_section_id, $this->filter_department_id), 'students.xlsx');
    }

    public function exportSeatPlan()
    {
        return Excel::download(new SeatPlanExport($this->search, $this->filter_class_id, $this->filter_section_id, $this->filter_department_id), 'seat-plan.xlsx');
    }

    public function render()
    {
        $students = User::with(['student.schoolClass', 'student.academicSession', 'student.classSection'])
            ->whereHas('student', function ($query) {
                $query->where('is_passed_out', 0) // ✅ Added: Only fetch active students
                    ->when($this->filter_class_id, fn($q) => $q->where('school_class_id', $this->filter_class_id))
                    ->when($this->filter_section_id, fn($q) => $q->where('class_section_id', $this->filter_section_id))
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

        return view('livewire.backend.student.index', [
            'students' => $students,
            'allClasses' => SchoolClass::orderBy('numeric_name')->get(),
            'departments' => Department::all(),
            'sessions' => AcademicSession::orderBy('start_date', 'desc')->get(),
        ]);
    }
}
