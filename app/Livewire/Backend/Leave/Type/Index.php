<?php

namespace App\Livewire\Backend\Leave\Type;

use App\Models\LeaveType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $description, $leaveTypeId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->leaveTypeId ?? 'NULL';

        return [
            'name' => 'required|unique:leave_types,name,' . $id,
            'description' => 'nullable|string',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->leaveTypeId) {
            LeaveType::findOrFail($this->leaveTypeId)->update($data);
            $message = 'Leave type updated successfully.';
        } else {
            LeaveType::create($data);
            $message = 'Leave type created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        $this->leaveTypeId = $leaveType->id;
        $this->name = $leaveType->name;
        $this->description = $leaveType->description;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        LeaveType::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Leave type deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'leaveTypeId']);
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
        $leaveTypes = LeaveType::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.leave.type.index', [
            'leaveTypes' => $leaveTypes,
        ]);
    }
}
