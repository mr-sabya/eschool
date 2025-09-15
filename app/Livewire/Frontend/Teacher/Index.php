<?php

namespace App\Livewire\Frontend\Teacher;

use App\Models\Staff;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;


    public function render()
    {
        return view('livewire.frontend.teacher.index', [
            'teachers' => Staff::with('user')
                ->whereHas('user', function ($q) {
                    $q->where('is_staff', true)
                        ->where('role', 'teacher')
                        ->where('status', true); // only active teachers
                })
                ->paginate(8),
        ]);
    }
}
