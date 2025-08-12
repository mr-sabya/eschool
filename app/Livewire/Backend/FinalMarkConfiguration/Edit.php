<?php

namespace App\Livewire\Backend\FinalMarkConfiguration;

use App\Models\FinalMarkConfiguration;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Department;
use Livewire\Component;

class Edit extends Component
{
    public $editingId;

    public $school_class_id;
    public $department_id;
    public $subject_id;
    public $grading_scale;          // new
    public $class_test_total;
    public $other_parts_total;
    public $final_result_weight_percentage;
    public $exclude_from_gpa = false;

    public $classes;
    public $departments;
    public $subjects;

    public function mount($id)
    {
        $this->classes = SchoolClass::all();
        $this->departments = Department::all();
        $this->subjects = Subject::all();

        $config = FinalMarkConfiguration::findOrFail($id);

        $this->editingId = $id;
        $this->school_class_id = $config->school_class_id;
        $this->department_id = $config->department_id;
        $this->subject_id = $config->subject_id;
        $this->grading_scale = $config->grading_scale;                  // load
        $this->class_test_total = $config->class_test_total;
        $this->other_parts_total = $config->other_parts_total;
        $this->final_result_weight_percentage = $config->final_result_weight_percentage;
        $this->exclude_from_gpa = $config->exclude_from_gpa ?? false;
    }

    public function update()
    {
        $this->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'department_id' => 'nullable|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'grading_scale' => 'required|in:50,100',   // validate grading_scale
            'class_test_total' => 'required|integer|min:0',
            'other_parts_total' => 'required|integer|min:0',
            'final_result_weight_percentage' => 'required|integer|min:0|max:100',
            'exclude_from_gpa' => 'boolean',
        ]);

        $config = FinalMarkConfiguration::findOrFail($this->editingId);

        $config->update([
            'school_class_id' => $this->school_class_id,
            'department_id' => $this->department_id,
            'subject_id' => $this->subject_id,
            'grading_scale' => $this->grading_scale,              // update
            'class_test_total' => $this->class_test_total,
            'other_parts_total' => $this->other_parts_total,
            'final_result_weight_percentage' => $this->final_result_weight_percentage,
            'exclude_from_gpa' => $this->exclude_from_gpa,
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Final mark configuration updated successfully.']);
    }

    public function render()
    {
        return view('livewire.backend.final-mark-configuration.edit', [
            'classes' => $this->classes,
            'departments' => $this->departments,
            'subjects' => $this->subjects,
        ]);
    }
}
