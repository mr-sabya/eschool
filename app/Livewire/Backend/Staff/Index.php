<?php

namespace App\Livewire\Backend\Staff;

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

    protected $listeners = ['staffSaved' => '$refresh'];

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

            // Delete user, related staff cascades or set null
            $user->delete();

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Staff deleted successfully!']);
            $this->confirmingDelete = false;
            $this->deleteId = null;
        }
    }

    public function render()
    {
        $staffUsers = User::with('staff.designation')
            ->where('is_staff', true) // All staff users have is_admin true
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhereHas('staff', function ($q2) {
                      $q2->where('first_name', 'like', "%{$this->search}%")
                         ->orWhere('last_name', 'like', "%{$this->search}%")
                         ->orWhere('email', 'like', "%{$this->search}%");
                  });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.staff.index', [
            'staffUsers' => $staffUsers,
        ]);
    }
}
