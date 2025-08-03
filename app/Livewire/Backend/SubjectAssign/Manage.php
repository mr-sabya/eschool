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

class Manage extends Component
{
    public $schoolClasses;
    public $classSections = [];
    public $shifts;

    public $subjects;
    public $teachers;

    public $selectedClass = null;
    public $selectedSection = null;
    public $selectedShift = null;

    public $subjectAssignId = null;

    // For assigning subjects
    public $assignedSubjects = []; // array of subject_id keys
    public $subjectTeachers = []; // [subject_id => teacher_id]

    protected $rules = [
        'selectedClass' => 'required|exists:school_classes,id',
        'selectedSection' => 'required|exists:class_sections,id',
        'selectedShift' => 'nullable|exists:shifts,id',
        'assignedSubjects' => 'required|array|min:1',
        'assignedSubjects.*' => 'exists:subjects,id',
        'subjectTeachers.*' => 'nullable|exists:users,id',
    ];

    public function mount()
    {
        $this->schoolClasses = SchoolClass::all();
        $this->shifts = Shift::all();
        $this->subjects = Subject::all();
        $this->teachers = User::where('role', 'teacher')->get(); // Adjust role filter if needed
    }

    public function onClassChange()
    {
        if ($this->selectedClass) {
            $this->classSections = ClassSection::where('school_class_id', $this->selectedClass)->get();
        } else {
            $this->classSections = collect();
        }
        $this->selectedSection = null;
        $this->loadAssignment();
    }

    public function onSectionChange()
    {
        $this->loadAssignment();
    }

    public function onShiftChange()
    {
        $this->loadAssignment();
    }

    public function loadAssignment()
    {
        if (!$this->selectedClass || !$this->selectedSection) {
            $this->resetAssignment();
            return;
        }

        $assign = SubjectAssign::with('items')->where('school_class_id', $this->selectedClass)
            ->where('class_section_id', $this->selectedSection)
            ->when($this->selectedShift, fn($q) => $q->where('shift_id', $this->selectedShift))
            ->first();

        if ($assign) {
            $this->subjectAssignId = $assign->id;
            $this->assignedSubjects = $assign->items->pluck('subject_id')->toArray();
            $this->subjectTeachers = $assign->items->pluck('teacher_id', 'subject_id')->toArray();
        } else {
            $this->resetAssignment();
        }
    }

    public function resetAssignment()
    {
        $this->subjectAssignId = null;
        $this->assignedSubjects = [];
        $this->subjectTeachers = [];
    }

    public function save()
    {
        $this->validate();

        $assign = SubjectAssign::updateOrCreate(
            [
                'school_class_id' => $this->selectedClass,
                'class_section_id' => $this->selectedSection,
                'shift_id' => $this->selectedShift,
            ],
            ['status' => 1]
        );

        $this->subjectAssignId = $assign->id;

        // Remove unassigned subjects
        SubjectAssignItem::where('subject_assign_id', $assign->id)
            ->whereNotIn('subject_id', $this->assignedSubjects)
            ->delete();

        // Add/update assigned subjects with teachers
        foreach ($this->assignedSubjects as $subjectId) {
            SubjectAssignItem::updateOrCreate(
                [
                    'subject_assign_id' => $assign->id,
                    'subject_id' => $subjectId,
                ],
                [
                    'teacher_id' => $this->subjectTeachers[$subjectId] ?? null,
                ]
            );
        }

        session()->flash('message', 'Subjects assigned successfully!');
    }

    public function render()
    {
        return view('livewire.backend.subject-assign.manage');
    }
}
