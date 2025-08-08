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
    public $department_id;  // added department_id
    public $subject_id;
    public $class_test_total;
    public $other_parts_total;
    public $final_result_weight_percentage;

    public $classes;
    public $departments;   // for department dropdown
    public $subjects;

    public function mount($id)
    {
        $this->classes = SchoolClass::all();
        $this->departments = Department::all();  // load departments
        $this->subjects = Subject::all();

        $config = FinalMarkConfiguration::findOrFail($id);

        $this->editingId = $id;
        $this->school_class_id = $config->school_class_id;
        $this->department_id = $config->department_id; // set department_id
        $this->subject_id = $config->subject_id;
        $this->class_test_total = $config->class_test_total;
        $this->other_parts_total = $config->other_parts_total;
        $this->final_result_weight_percentage = $config->final_result_weight_percentage;
    }

    public function update()
    {
        $this->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'department_id' => 'nullable|exists:departments,id',  // nullable validation
            'subject_id' => 'required|exists:subjects,id',
            'class_test_total' => 'required|integer|min:0',
            'other_parts_total' => 'required|integer|min:0',
            'final_result_weight_percentage' => 'required|integer|min:0|max:100',
        ]);

        $config = FinalMarkConfiguration::findOrFail($this->editingId);

        $config->update([
            'school_class_id' => $this->school_class_id,
            'department_id' => $this->department_id, // update department_id
            'subject_id' => $this->subject_id,
            'class_test_total' => $this->class_test_total,
            'other_parts_total' => $this->other_parts_total,
            'final_result_weight_percentage' => $this->final_result_weight_percentage,
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
