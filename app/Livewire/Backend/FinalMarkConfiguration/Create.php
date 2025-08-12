<?php

namespace App\Livewire\Backend\FinalMarkConfiguration;

use App\Models\FinalMarkConfiguration;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Department;
use Livewire\Component;

class Create extends Component
{
    public $school_class_id;
    public $department_id;

    public $rows = [];

    public $classes;
    public $subjects;
    public $departments;

    public function mount()
    {
        $this->classes = SchoolClass::all();
        $this->subjects = Subject::all();
        $this->departments = Department::all();

        $this->addRow();
    }

    public function addRow()
    {
        $this->rows[] = [
            'subject_id' => '',
            'class_test_total' => 20,
            'other_parts_total' => 100,
            'final_result_weight_percentage' => 80,
            'grading_scale' => 100,        // new field with default
            'exclude_from_gpa' => false,   // new field with default
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
            'department_id' => 'nullable|exists:departments,id',
            'rows.*.subject_id' => 'required|exists:subjects,id',
            'rows.*.class_test_total' => 'required|integer|min:0',
            'rows.*.other_parts_total' => 'required|integer|min:0',
            'rows.*.final_result_weight_percentage' => 'required|integer|min:0|max:100',
            'rows.*.grading_scale' => 'required|integer|in:50,100',
            'rows.*.exclude_from_gpa' => 'boolean',
        ]);

        foreach ($this->rows as $row) {
            FinalMarkConfiguration::updateOrCreate(
                [
                    'school_class_id' => $this->school_class_id,
                    'subject_id' => $row['subject_id'],
                ],
                [
                    'department_id' => $this->department_id,
                    'class_test_total' => $row['class_test_total'],
                    'other_parts_total' => $row['other_parts_total'],
                    'final_result_weight_percentage' => $row['final_result_weight_percentage'],
                    'grading_scale' => $row['grading_scale'],
                    'exclude_from_gpa' => $row['exclude_from_gpa'],
                ]
            );
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Final mark configurations saved successfully.']);
        $this->reset(['school_class_id', 'department_id', 'rows']);
        $this->addRow();
    }

    public function render()
    {
        return view('livewire.backend.final-mark-configuration.create', [
            'classes' => $this->classes,
            'subjects' => $this->subjects,
            'departments' => $this->departments,
        ]);
    }
}
