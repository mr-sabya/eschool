<?php

namespace App\Livewire\Backend\ClassSubjectAssign;

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

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'refreshIndex' => '$refresh',
    ];

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

    public function render()
    {
        $query = ClassSubjectAssign::query()
            ->with(['schoolClass', 'classSection', 'department', 'academicSession'])
            ->when($this->search, function($q) {
                $searchTerm = '%' . $this->search . '%';
                $q->whereHas('schoolClass', fn($q) => $q->where('name', 'like', $searchTerm))
                  ->orWhereHas('classSection', fn($q) => $q->where('name', 'like', $searchTerm))
                  ->orWhereHas('department', fn($q) => $q->where('name', 'like', $searchTerm));
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        $subjectAssigns = $query->paginate($this->perPage);

        return view('livewire.backend.class-subject-assign.index', [
            'subjectAssigns' => $subjectAssigns,
        ]);
    }

}
