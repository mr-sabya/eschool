<?php

namespace App\Livewire\Frontend\Staff;

use App\Models\Staff;
use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedDepartment = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Staff::with(['designation', 'department', 'user'])
            ->whereHas('user', function ($q) {
                $q->where('status', 1) // Only show active users
                    ->where('role', 'staff'); // Ensure it's a staff member
            });

        // Search logic
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('staff_id', 'like', '%' . $this->search . '%');
            });
        }

        // Department Filter
        if (!empty($this->selectedDepartment)) {
            $query->where('department_id', $this->selectedDepartment);
        }

        return view('livewire.frontend.staff.index', [
            'staffs' => $query->orderBy('id', 'asc')->paginate(12),
            'departments' => Department::all(),
        ]);
    }
}
