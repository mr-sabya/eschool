<?php

namespace App\Livewire\Backend\SubjectMarkDistribution;

use App\Models\ClassSection;
use App\Models\MarkDistribution;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectMarkDistribution;
use Livewire\Component;

class Create extends Component
{
    public $school_class_id;
    public $class_section_id;

    public $rows = [];

    public function mount()
    {
        $this->addRow();
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
            'rows.*.subject_id' => 'required|exists:subjects,id',
            'rows.*.mark_distribution_id' => 'required|exists:mark_distributions,id',
            'rows.*.mark' => 'required|numeric|min:0',
            'rows.*.pass_mark' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($this->rows as $row) {
            SubjectMarkDistribution::create([
                'school_class_id' => $this->school_class_id,
                'class_section_id' => $this->class_section_id,
                'subject_id' => $row['subject_id'],
                'mark_distribution_id' => $row['mark_distribution_id'],
                'mark' => $row['mark'],
                'pass_mark' => $row['pass_mark'],
            ]);
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Subject mark distributions saved successfully.']);

        $this->reset(['school_class_id', 'class_section_id', 'rows']);
        $this->addRow();
    }

    public function render()
    {
        return view('livewire.backend.subject-mark-distribution.create', [
            'classes' => SchoolClass::all(),
            'sections' => ClassSection::all(),
            'subjects' => Subject::all(),
            'distributions' => MarkDistribution::all(),
        ]);
    }
}
