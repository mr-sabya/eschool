<?php

namespace App\Livewire\Backend\Student;

// ADD the new SeatPlanExport class to the use statements
use App\Exports\SeatPlanExport;
use App\Exports\StudentsExport;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 25;
    public $confirmingDelete = false;
    public $deleteId = null;

    public $filter_class_id = null;
    public $filter_section_id = null;
    public $filter_department_id = null;

    protected $listeners = ['student-saved' => '$refresh'];

    public function updatingSearch()
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

    public function getFilteredSectionsProperty()
    {
        if ($this->filter_class_id) {
            return ClassSection::where('school_class_id', $this->filter_class_id)->get();
        }
        return collect();
    }

    public function departmentFilter()
    {
        $this->resetPage();
    }

    public function export()
    {
        $filename = 'students-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new StudentsExport(
                $this->search,
                $this->filter_class_id,
                $this->filter_section_id,
                $this->filter_department_id
            ),
            $filename
        );
    }

    // ✅ ADD THIS NEW METHOD FOR SEAT PLAN EXPORT
    public function exportSeatPlan()
    {
        $filename = 'seat-plan-' . now()->format('Y-m-d') . '.xlsx';

        // Use the new SeatPlanExport class
        return Excel::download(
            new SeatPlanExport(
                $this->search,
                $this->filter_class_id,
                $this->filter_section_id,
                $this->filter_department_id
            ),
            $filename
        );
    }


    public function render()
    {
        $students = User::with('student.schoolClass')
            ->whereHas('student', function ($query) {
                $query->when($this->filter_class_id, fn($q) => $q->where('school_class_id', $this->filter_class_id))
                    ->when($this->filter_section_id, fn($q) => $q->where('class_section_id', $this->filter_section_id))
                    ->when($this->filter_department_id, fn($q) => $q->where('department_id', $this->filter_department_id));
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
            'allClasses' => SchoolClass::all(),
            'departments' => Department::all(),
        ]);
    }
}
