<?php

namespace App\Livewire\Backend\ClassSubjectAssign;

use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\ClassSubjectAssign;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 50;
    public $sortField = 'id';
    public $sortAsc = true;

    public $confirmingDelete = false;
    public $deleteId = null;

    // Filter properties
    public $sessions, $classes;
    public $sections = [], $departments = []; 

    public $selectedSessionId = '';
    public $selectedClassId = '';
    public $selectedSectionId = '';
    public $selectedDepartmentId = '';

    // ✅ NEW: Property for Copy feature
    public $copyToSessionId = '';

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refreshIndex' => '$refresh'];

    public function mount()
    {
        $this->sessions = AcademicSession::all();
        $this->classes = SchoolClass::all();
        // Load all departments by default since they usually aren't tied to a specific class ID in the DB
        $this->departments = Department::all();
    }

    public function updatedSelectedClassId($classId)
    {
        if (!empty($classId)) {
            // FIX: Only filter sections by class. Departments are usually global.
            $this->sections = ClassSection::where('school_class_id', $classId)->get();
        } else {
            $this->sections = [];
        }

        $this->selectedSectionId = '';
        $this->resetPage();
    }

    // ✅ NEW: Method to copy filtered data to a new session
    public function copyAssignments()
    {
        if (empty($this->selectedSessionId) || empty($this->copyToSessionId)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Select source and target sessions.']);
            return;
        }

        if ($this->selectedSessionId == $this->copyToSessionId) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Source and Target sessions are the same.']);
            return;
        }

        // Get all assignments matching the CURRENT filters
        $assignments = ClassSubjectAssign::query()
            ->where('academic_session_id', $this->selectedSessionId)
            ->when($this->selectedClassId, fn($q) => $q->where('school_class_id', $this->selectedClassId))
            ->when($this->selectedSectionId, fn($q) => $q->where('class_section_id', $this->selectedSectionId))
            ->when($this->selectedDepartmentId, fn($q) => $q->where('department_id', $this->selectedDepartmentId))
            ->get();

        if ($assignments->isEmpty()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No assignments found to copy.']);
            return;
        }

        $count = 0;
        foreach ($assignments as $item) {
            // Duplicate the record into the new session
            ClassSubjectAssign::updateOrCreate([
                'academic_session_id' => $this->copyToSessionId,
                'school_class_id'    => $item->school_class_id,
                'class_section_id'   => $item->class_section_id,
                'department_id'      => $item->department_id,
                'subject_id'         => $item->subject_id,
            ], [
                'is_added_to_result' => $item->is_added_to_result,
            ]);
            $count++;
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => "Successfully copied $count assignments!"]);
        $this->copyToSessionId = '';
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }
    public function updatingSelectedSessionId() { $this->resetPage(); }
    public function updatingSelectedSectionId() { $this->resetPage(); }
    public function updatingSelectedDepartmentId() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortField = $field;
            $this->sortAsc = true;
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
            ClassSubjectAssign::findOrFail($this->deleteId)->delete();
            $this->confirmingDelete = false;
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->selectedSessionId = '';
        $this->selectedClassId = '';
        $this->selectedSectionId = '';
        $this->selectedDepartmentId = '';
        $this->sections = [];
        $this->resetPage();
    }

    public function render()
    {
        $query = ClassSubjectAssign::query()
            ->with(['schoolClass', 'classSection', 'department', 'academicSession', 'subject'])
            ->when($this->selectedSessionId, fn($q) => $q->where('academic_session_id', $this->selectedSessionId))
            ->when($this->selectedClassId, fn($q) => $q->where('school_class_id', $this->selectedClassId))
            ->when($this->selectedSectionId, fn($q) => $q->where('class_section_id', $this->selectedSectionId))
            ->when($this->selectedDepartmentId, fn($q) => $q->where('department_id', $this->selectedDepartmentId))
            ->when($this->search, function ($q) {
                $searchTerm = '%' . $this->search . '%';
                $q->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->whereHas('schoolClass', fn($sq) => $sq->where('name', 'like', $searchTerm))
                        ->orWhereHas('subject', fn($sq) => $sq->where('name', 'like', $searchTerm));
                });
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        return view('livewire.backend.class-subject-assign.index', [
            'subjectAssigns' => $query->paginate($this->perPage),
        ]);
    }
}