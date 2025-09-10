<?php

namespace App\Livewire\Backend\Library\BookIssue;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BookIssue;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
     use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $bookIssueId;

    protected $listeners = ['refreshBookIssues' => '$refresh'];

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
        $this->bookIssueId = $id;
    }

    public function deleteConfirmed()
    {
        BookIssue::findOrFail($this->bookIssueId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Book Issue deleted successfully']);
        $this->bookIssueId = null;
    }

    public function render()
    {
        $bookIssues = BookIssue::with(['book', 'member', 'issuedBy'])
            ->whereHas('book', function($q) {
                $q->where('title', 'like', '%'.$this->search.'%');
            })
            ->orWhereHas('member', function($q) {
                $q->where('library_member_id', 'like', '%'.$this->search.'%');
            })
            ->orWhereHas('issuedBy', function($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.library.book-issue.index', compact('bookIssues'));
    }

}
