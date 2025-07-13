<?php

namespace App\Livewire\Backend\ClassSection;

use App\Models\ClassSection;
use App\Models\SchoolClass;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $school_class_id, $name, $numeric_name, $classSectionId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->classSectionId ?? 'NULL';

        return [
            'school_class_id' => 'required|exists:school_classes,id',
            'name' => 'required|string|unique:class_sections,name,' . $id,
            'numeric_name' => 'nullable|integer',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->classSectionId) {
            ClassSection::findOrFail($this->classSectionId)->update($data);
            $message = 'Class section updated successfully.';
        } else {
            ClassSection::create($data);
            $message = 'Class section created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $section = ClassSection::findOrFail($id);
        $this->classSectionId = $section->id;
        $this->school_class_id = $section->school_class_id;
        $this->name = $section->name;
        $this->numeric_name = $section->numeric_name;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        ClassSection::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Class section deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['school_class_id', 'name', 'numeric_name', 'classSectionId']);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $sections = ClassSection::with('schoolClass')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('schoolClass', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $schoolClasses = SchoolClass::orderBy('name')->get();

        return view('livewire.backend.class-section.index', [
            'sections' => $sections,
            'schoolClasses' => $schoolClasses,
        ]);
    }

}
