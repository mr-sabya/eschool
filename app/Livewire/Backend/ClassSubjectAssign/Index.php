<?php

namespace App\Livewire\Backend\ClassSubjectAssign;

// Add these 'use' statements for the models you'll be using for filters
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\SchoolClass;
// ---

use App\Models\ClassSubjectAssign;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortAsc = true;

    public $confirmingDelete = false;
    public $deleteId = null;

    // --- START: NEW FILTER PROPERTIES ---
    public $sessions, $classes;
    public $sections = [], $departments = []; // Dependent dropdowns, start empty

    public $selectedSessionId = '';
    public $selectedClassId = '';
    public $selectedSectionId = '';
    public $selectedDepartmentId = '';
    // --- END: NEW FILTER PROPERTIES ---

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refreshIndex' => '$refresh',
    ];

    // The mount method runs once, when the component is initialized.
    public function mount()
    {
        // Pre-load the main filter options
        $this->sessions = AcademicSession::all();
        $this->classes = SchoolClass::all();
    }

    // --- START: NEW LIFECYCLE HOOKS for filters ---

    // When the selected class is changed, this method will run.
    public function updatedSelectedClassId($classId)
    {
        if (!empty($classId)) {
            // Load sections and departments based on the selected class
            $this->sections = ClassSection::where('school_class_id', $classId)->get();
            $this->departments = Department::where('school_class_id', $classId)->get();
        } else {
            // If class is deselected, empty the dependent dropdowns
            $this->sections = [];
            $this->departments = [];
        }

        // Reset the child dropdown selections
        $this->selectedSectionId = '';
        $this->selectedDepartmentId = '';
        $this->resetPage(); // Go back to the first page of results
    }

    // These methods reset pagination whenever a filter is changed.
    public function updatingSelectedSessionId()
    {
        $this->resetPage();
    }
    public function updatingSelectedSectionId()
    {
        $this->resetPage();
    }
    public function updatingSelectedDepartmentId()
    {
        $this->resetPage();
    }

    // --- END: NEW LIFECYCLE HOOKS for filters ---

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortField = $field;
            $this->sortAsc = true;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            ClassSubjectAssign::findOrFail($this->deleteId)->delete();
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Assignment deleted successfully.']);
            $this->confirmingDelete = false;
            $this->deleteId = null;
            $this->resetPage();
        }
    }

    // --- START: NEW METHOD to clear all filters ---
    public function resetFilters()
    {
        $this->selectedSessionId = '';
        $this->selectedClassId = '';
        $this->selectedSectionId = '';
        $this->selectedDepartmentId = '';
        // Also reset the dependent dropdown data
        $this->sections = [];
        $this->departments = [];
        $this->resetPage();
    }
    // --- END: NEW METHOD ---

    public function render()
    {
        $query = ClassSubjectAssign::query()
            ->with(['schoolClass', 'classSection', 'department', 'academicSession', 'subject'])

            // --- START: APPLY FILTERS TO THE QUERY ---
            ->when($this->selectedSessionId, function ($q) {
                $q->where('academic_session_id', $this->selectedSessionId);
            })
            ->when($this->selectedClassId, function ($q) {
                $q->where('school_class_id', $this->selectedClassId);
            })
            ->when($this->selectedSectionId, function ($q) {
                $q->where('class_section_id', $this->selectedSectionId);
            })
            ->when($this->selectedDepartmentId, function ($q) {
                $q->where('department_id', $this->selectedDepartmentId);
            })
            // --- END: APPLY FILTERS ---

            ->when($this->search, function ($q) {
                $searchTerm = '%' . $this->search . '%';
                // Search now includes the subject name
                $q->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->whereHas('schoolClass', fn($sq) => $sq->where('name', 'like', $searchTerm))
                        ->orWhereHas('classSection', fn($sq) => $sq->where('name', 'like', $searchTerm))
                        ->orWhereHas('department', fn($sq) => $sq->where('name', 'like', $searchTerm))
                        ->orWhereHas('subject', fn($sq) => $sq->where('name', 'like', $searchTerm));
                });
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        $subjectAssigns = $query->paginate($this->perPage);

        return view('livewire.backend.class-subject-assign.index', [
            'subjectAssigns' => $subjectAssigns,
        ]);
    }
}
