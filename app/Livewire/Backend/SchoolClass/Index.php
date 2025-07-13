<?php

namespace App\Livewire\Backend\SchoolClass;

use App\Models\SchoolClass;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $numeric_name, $description, $order = 0, $is_active = true, $schoolClassId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10; // default number of records per page

    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->schoolClassId ?? 'NULL';

        return [
            'name' => 'required|unique:school_classes,name,' . $id,
            'numeric_name' => 'required|numeric|unique:school_classes,numeric_name,' . $id,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->schoolClassId) {
            $schoolClass = SchoolClass::findOrFail($this->schoolClassId);
            $schoolClass->update($data);
            $message = 'Class updated successfully.';
        } else {
            SchoolClass::create($data);
            $message = 'Class created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $class = SchoolClass::findOrFail($id);
        $this->schoolClassId = $class->id;
        $this->name = $class->name;
        $this->numeric_name = $class->numeric_name;
        $this->description = $class->description;
        $this->order = $class->order;
        $this->is_active = $class->is_active;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        SchoolClass::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Class deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'numeric_name', 'description', 'order', 'is_active', 'schoolClassId']);
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
        $classes = SchoolClass::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('numeric_name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.school-class.index', [
            'classes' => $classes
        ]);
    }
}
