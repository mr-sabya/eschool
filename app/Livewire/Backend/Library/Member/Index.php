<?php

namespace App\Livewire\Backend\Library\Member;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\LibraryMember;
use App\Models\MemberCategory;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

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
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        LibraryMember::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Library Member deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $members = LibraryMember::with(['user', 'category'])
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('category', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.library.member.index', [
            'members' => $members,
        ]);
    }
}
