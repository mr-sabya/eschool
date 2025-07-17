<?php

namespace App\Livewire\Backend\Student;

use App\Models\Student;
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

            // ✅ Delete user, related student will cascade delete
            $user->delete();

            $this->dispatch('notify', 'Student deleted successfully!');
            $this->confirmingDelete = false;
            $this->deleteId = null;
        }
    }

    public function render()
    {
        $students = User::with('student.schoolClass')
            ->whereHas('student') // ✅ Only users who are students
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('student', function ($q2) {
                        $q2->where('first_name', 'like', "%{$this->search}%")
                            ->orWhere('last_name', 'like', "%{$this->search}%");
                    });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.student.index', compact('students'));
    }
}
