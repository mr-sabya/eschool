<?php

namespace App\Livewire\Backend\SubjectAssign;

use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectAssign;
use App\Models\SubjectAssignItem;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public $subjectAssignId;
    public $school_class_id;
    public $class_section_id;
    public $rows = [];

    public $classes = [];
    public $sections = [];
    public $subjects = [];
    public $teachers = [];

    public function mount($subjectAssignId)
    {
        $this->subjectAssignId = $subjectAssignId;
        $assign = SubjectAssign::with('items')->findOrFail($subjectAssignId);

        $this->school_class_id = $assign->school_class_id;
        $this->class_section_id = $assign->class_section_id;

        $this->classes = SchoolClass::all();
        $this->sections = ClassSection::where('school_class_id', $this->school_class_id)->get();
        $this->subjects = Subject::all();
        $this->teachers = User::where(function ($query) {
            $query->where('role', 'teacher')
                ->orWhere('is_admin', true);
        })->get();

        $this->rows = $assign->items->map(function ($item) {
            return [
                'subject_id' => $item->subject_id,
                'teacher_id' => $item->teacher_id,
            ];
        })->toArray();
    }

    public function updatedSchoolClassIdManually()
    {
        if ($this->school_class_id) {
            $this->sections = ClassSection::where('school_class_id', $this->school_class_id)->get();
        } else {
            $this->sections = [];
            $this->class_section_id = null;
        }
    }

    public function addRow()
    {
        $this->rows[] = ['subject_id' => null, 'teacher_id' => null];
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
            'rows.*.teacher_id' => 'nullable|exists:users,id',
        ]);

        $assign = SubjectAssign::findOrFail($this->subjectAssignId);
        $assign->update([
            'school_class_id' => $this->school_class_id,
            'class_section_id' => $this->class_section_id,
        ]);

        $assign->items()->delete();

        foreach ($this->rows as $row) {
            SubjectAssignItem::create([
                'subject_assign_id' => $assign->id,
                'subject_id' => $row['subject_id'],
                'teacher_id' => $row['teacher_id'] ?? null,
            ]);
        }

        session()->flash('success', 'Subject assignment updated successfully.');
        return redirect()->route('subject-assign.index');
    }

    public function render()
    {
        return view('livewire.backend.subject-assign.edit');
    }
}
