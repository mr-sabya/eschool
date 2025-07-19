<?php

namespace App\Livewire\Backend\Guardian;

use App\Models\Guardian;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $confirmingDelete = false;
    public $deleteId = null;

    protected $listeners = ['guardian-saved' => '$refresh'];

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

            // Delete user, related guardian will cascade delete
            $user->delete();

            $this->dispatch('notify', 'Guardian deleted successfully!');
            $this->confirmingDelete = false;
            $this->deleteId = null;
        }
    }

    public function render()
    {
        $guardians = User::with('guardian')
            ->whereHas('guardian') // Only users who are guardians
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('guardian', function ($q) {
                        // Search inside guardian fields (phone, occupation etc.)
                        $q->where('phone', 'like', "%{$this->search}%")
                            ->orWhere('occupation', 'like', "%{$this->search}%");
                    });
            })
            ->where('is_parent', 1) // Assuming guardians are parents
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.guardian.index', compact('guardians'));
    }
}
