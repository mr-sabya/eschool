<?php

namespace App\Livewire\Backend\ClassSubjectAssign;

use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\ClassSubjectAssign;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\Subject;
use Livewire\Component;

class Create extends Component
{
    public $school_class_id;
    public $class_section_id;
    public $department_id;
    public $academic_session_id;

    public $rows = [];
    public $sections = [];
    public $classes = [];
    public $departments = [];
    public $subjects = [];
    public $academicSessions = [];

    public function mount()
    {
        $this->classes = SchoolClass::all();
        $this->departments = Department::all();
        $this->subjects = Subject::all();
        $this->academicSessions = AcademicSession::all();

        $this->addRow();
    }

    public function onClassChange()
    {
        $this->class_section_id = null;
        $this->sections = ClassSection::where('school_class_id', $this->school_class_id)->get();
    }

    public function addRow()
    {
        $this->rows[] = [
            'subject_id' => '',
            'is_added_to_result' => true,  // added default value
        ];
    }

    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function save()
    {
        $this->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'class_section_id' => 'nullable|exists:class_sections,id',
            'department_id' => 'nullable|exists:departments,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'rows.*.subject_id' => 'required|exists:subjects,id',
            'rows.*.is_added_to_result' => 'boolean',
        ]);

        // Save each subject assignment with is_added_to_result flag
        foreach ($this->rows as $row) {
            ClassSubjectAssign::updateOrCreate(
                [
                    'school_class_id' => $this->school_class_id,
                    'class_section_id' => $this->class_section_id,
                    'department_id' => $this->department_id,
                    'academic_session_id' => $this->academic_session_id,
                    'subject_id' => $row['subject_id'],
                ],
                [
                    'is_added_to_result' => $row['is_added_to_result'] ?? false,
                ]
            );
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Subjects assigned successfully.']);

        // Reset form
        $this->reset(['school_class_id', 'class_section_id', 'department_id', 'academic_session_id', 'rows', 'sections']);
        $this->addRow();
    }

    public function render()
    {
        return view('livewire.backend.class-subject-assign.create', [
            'classes' => $this->classes,
            'sections' => $this->sections,
            'departments' => $this->departments,
            'subjects' => $this->subjects,
            'academicSessions' => $this->academicSessions,
        ]);
    }
}
