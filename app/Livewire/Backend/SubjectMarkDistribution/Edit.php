<?php

namespace App\Livewire\Backend\SubjectMarkDistribution;

use App\Models\SubjectMarkDistribution;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\MarkDistribution;
use Livewire\Component;

class Edit extends Component
{
    public $subjectMarkDistributionId;

    public $school_class_id;
    public $class_section_id;
    public $subject_id;
    public $mark_distribution_id;
    public $mark;
    public $pass_mark;

    public $sections = [];  // filtered sections for selected class

    public function mount($id)
    {
        $distribution = SubjectMarkDistribution::findOrFail($id);

        $this->subjectMarkDistributionId = $id;
        $this->school_class_id = $distribution->school_class_id;
        $this->class_section_id = $distribution->class_section_id;
        $this->subject_id = $distribution->subject_id;
        $this->mark_distribution_id = $distribution->mark_distribution_id;
        $this->mark = $distribution->mark;
        $this->pass_mark = $distribution->pass_mark;

        // Load sections for the initial class
        $this->sections = ClassSection::where('school_class_id', $this->school_class_id)->get();
    }

    public function updatedSchoolClassId($value)
    {
        $this->class_section_id = null; // reset selected section
        $this->sections = ClassSection::where('school_class_id', $value)->get();
    }

    public function update()
    {
        $this->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'mark_distribution_id' => 'required|exists:mark_distributions,id',
            'mark' => 'required|numeric|min:0',
            'pass_mark' => 'required|numeric|min:0|max:100',
        ]);

        $distribution = SubjectMarkDistribution::findOrFail($this->subjectMarkDistributionId);

        $distribution->update([
            'school_class_id' => $this->school_class_id,
            'class_section_id' => $this->class_section_id,
            'subject_id' => $this->subject_id,
            'mark_distribution_id' => $this->mark_distribution_id,
            'mark' => $this->mark,
            'pass_mark' => $this->pass_mark,
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Subject mark distribution updated successfully.']);
    }

    public function render()
    {
        return view('livewire.backend.subject-mark-distribution.edit', [
            'classes' => SchoolClass::all(),
            'subjects' => Subject::all(),
            'distributions' => MarkDistribution::all(),
            // don't send all sections anymore, use filtered sections property instead
        ]);
    }
}
