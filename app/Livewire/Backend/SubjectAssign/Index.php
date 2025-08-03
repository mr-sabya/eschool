<?php

namespace App\Livewire\Backend\SubjectAssign;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SubjectAssign;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Shift;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $confirmingDelete = false;
    public $deleteId = null;

    protected $paginationTheme = 'bootstrap';

    // Reset pagination on search or perPage change
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
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
        $this->confirmingDelete = true;
        $this->deleteId = $id;
    }

    public function delete()
    {
        if ($this->deleteId) {
            SubjectAssign::find($this->deleteId)?->delete();
            $this->confirmingDelete = false;
            $this->deleteId = null;
            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Assignment deleted successfully']);
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = SubjectAssign::query()
            ->with(['schoolClass', 'classSection', 'shift']);

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('schoolClass', function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            })
            ->orWhereHas('classSection', function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm);
            });
        }

        $subjectAssigns = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.subject-assign.index', [
            'subjectAssigns' => $subjectAssigns,
        ]);
    }
    
}
