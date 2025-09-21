<?php

namespace App\Livewire\Frontend\Student;

use App\Models\SchoolClass;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $classId;

    // mount
    public function mount($classId)
    {
        $this->classId = $classId;
    }

    public function render()
    {
        return view('livewire.frontend.student.index',[
            'students' => Student::with('user')->where('school_class_id', $this->classId)->paginate(16),
            'class' => SchoolClass::find($this->classId),
            'studentCount' => Student::where('school_class_id', $this->classId)->count(),
        ]);
    }
}
