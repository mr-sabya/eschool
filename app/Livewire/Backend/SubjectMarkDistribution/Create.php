<?php

namespace App\Livewire\Backend\SubjectMarkDistribution;

use App\Models\ClassSection;
use App\Models\MarkDistribution;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectMarkDistribution;
use App\Models\Department;
use Livewire\Component;

class Create extends Component
{
    public $school_class_id;
    public $class_section_id;
    public $department_id;  // nullable

    public $rows = [];
    public $sections = [];
    public $classes = [];
    public $departments = [];

    public function mount()
    {
        $this->departments = Department::all();
        $this->classes = SchoolClass::all();
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
            'mark_distribution_id' => '',
            'mark' => '',
            'pass_mark' => '',
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
            'class_section_id' => 'required|exists:class_sections,id',
            'department_id' => 'nullable|exists:departments,id',
            'rows.*.subject_id' => 'required|exists:subjects,id',
            'rows.*.mark_distribution_id' => 'required|exists:mark_distributions,id',
            'rows.*.mark' => 'required|numeric|min:0',
            'rows.*.pass_mark' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($this->rows as $row) {
            SubjectMarkDistribution::create([
                'department_id' => $this->department_id,
                'school_class_id' => $this->school_class_id,
                'class_section_id' => $this->class_section_id,
                'subject_id' => $row['subject_id'],
                'mark_distribution_id' => $row['mark_distribution_id'],
                'mark' => $row['mark'],
                'pass_mark' => $row['pass_mark'],
            ]);
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Subject mark distributions saved successfully.']);

        $this->reset(['department_id', 'school_class_id', 'class_section_id', 'rows', 'sections']);
        $this->departments = Department::all();
        $this->classes = SchoolClass::all();
        $this->addRow();
    }

    public function render()
    {
        return view('livewire.backend.subject-mark-distribution.create', [
            'subjects' => Subject::all(),
            'distributions' => MarkDistribution::all(),
            'departments' => $this->departments,
            'classes' => $this->classes,
            'sections' => $this->sections,
        ]);
    }
}
