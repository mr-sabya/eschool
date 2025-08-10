<?php

namespace App\Livewire\Backend\ClassSubjectAssign;

use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\ClassSubjectAssign;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\Subject;
use Livewire\Component;

class Edit extends Component
{
    public $assignmentId;

    public $school_class_id;
    public $class_section_id;
    public $department_id;
    public $academic_session_id;
    public $subject_id;
    public $is_added_to_result = false;  // new property

    public $sections = [];
    public $classes = [];
    public $departments = [];
    public $subjects = [];
    public $academicSessions = [];

    public function mount($id)
    {
        $this->assignmentId = $id;

        $this->classes = SchoolClass::all();
        $this->departments = Department::all();
        $this->subjects = Subject::all();
        $this->academicSessions = AcademicSession::all();

        $assignment = ClassSubjectAssign::findOrFail($id);

        $this->school_class_id = $assignment->school_class_id;
        $this->class_section_id = $assignment->class_section_id;
        $this->department_id = $assignment->department_id;
        $this->academic_session_id = $assignment->academic_session_id;
        $this->subject_id = $assignment->subject_id;
        $this->is_added_to_result = (bool) $assignment->is_added_to_result;

        $this->loadSections();
    }

    public function loadSections()
    {
        if ($this->school_class_id) {
            $this->sections = ClassSection::where('school_class_id', $this->school_class_id)->get();
        } else {
            $this->sections = [];
            $this->class_section_id = null;
        }
    }

    public function updatedSchoolClassId()
    {
        $this->loadSections();
        $this->class_section_id = null;
    }

    public function save()
    {
        $this->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'class_section_id' => 'nullable|exists:class_sections,id',
            'department_id' => 'nullable|exists:departments,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'subject_id' => 'required|exists:subjects,id',
            'is_added_to_result' => 'boolean',  // validate the new field
        ]);

        $assignment = ClassSubjectAssign::findOrFail($this->assignmentId);

        $assignment->update([
            'school_class_id' => $this->school_class_id,
            'class_section_id' => $this->class_section_id,
            'department_id' => $this->department_id,
            'academic_session_id' => $this->academic_session_id,
            'subject_id' => $this->subject_id,
            'is_added_to_result' => $this->is_added_to_result,  // update flag
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Assignment updated successfully.']);

        // Optional: redirect or reset if needed
    }

    public function render()
    {
        return view('livewire.backend.class-subject-assign.edit', [
            'classes' => $this->classes,
            'sections' => $this->sections,
            'departments' => $this->departments,
            'subjects' => $this->subjects,
            'academicSessions' => $this->academicSessions,
        ]);
    }
}
