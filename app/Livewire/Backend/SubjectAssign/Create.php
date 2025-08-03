<?php

namespace App\Livewire\Backend\SubjectAssign;

use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\Shift;
use App\Models\Subject;
use App\Models\SubjectAssign;
use App\Models\SubjectAssignItem;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $school_class_id;
    public $class_section_id;
    public $shift_id;

    public $classes = [];
    public $sections;
    public $shifts = [];
    public $subjects = [];

    public $rows = [];

    protected $rules = [
        'school_class_id' => 'required|exists:school_classes,id',
        'class_section_id' => 'required|exists:class_sections,id',
        'shift_id' => 'nullable|exists:shifts,id',
        'rows' => 'required|array|min:1',
        'rows.*.subject_id' => 'required|exists:subjects,id',
        'rows.*.teacher_id' => 'nullable|exists:users,id',
    ];

    public function mount()
    {
        $this->classes = SchoolClass::orderBy('id')->get();
        $this->shifts = Shift::orderBy('id')->get();
        $this->rows = [
            ['subject_id' => '', 'teacher_id' => ''],
        ];
        $this->sections = collect();
    }

    public function onClassChange($classId)
    {
        $this->school_class_id = $classId;
        $this->sections = ClassSection::where('school_class_id', $classId)->get();
    }

    public function addRow()
    {
        $this->rows[] = ['subject_id' => '', 'teacher_id' => ''];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // reindex array
    }

    public function save()
    {
        $this->validate();

        // Delete existing assignments for this class-section-shift
        SubjectAssign::where('school_class_id', $this->school_class_id)
            ->where('class_section_id', $this->class_section_id)
            ->where('shift_id', $this->shift_id)
            ->delete();

        // Create new SubjectAssign
        $assign = SubjectAssign::create([
            'school_class_id' => $this->school_class_id,
            'class_section_id' => $this->class_section_id,
            'shift_id' => $this->shift_id,
            'status' => 1,
        ]);

        // Create SubjectAssignItems
        foreach ($this->rows as $row) {
            SubjectAssignItem::create([
                'subject_assign_id' => $assign->id,
                'subject_id' => $row['subject_id'],
                'teacher_id' => !empty($row['teacher_id']) ? $row['teacher_id'] : null,
            ]);
        }


        $this->dispatch('notify', ['type' => 'success', 'message' => 'Subjects assigned successfully.']);

        // Reset form
        $this->reset(['school_class_id', 'class_section_id', 'shift_id', 'rows']);
        $this->rows = [['subject_id' => '', 'teacher_id' => '']];
        $this->sections = [];
    }

    public function render()
    {
        // Load subjects and teachers for dropdowns
        $this->subjects = Subject::orderBy('name')->get();
        $teachers = User::all()
            ->filter(fn($user) => $user->isTeacher() || $user->isAdmin())
            ->sortBy('name');

        return view('livewire.backend.subject-assign.create', [
            'teachers' => $teachers,
        ]);
    }
}
