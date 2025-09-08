<?php

namespace App\Livewire\Backend\Fee\Type;

use App\Models\FeeType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $description, $is_active = true, $feeTypeId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'boolean',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->feeTypeId) {
            FeeType::findOrFail($this->feeTypeId)->update($data);
            $message = 'Fee Type updated successfully.';
        } else {
            FeeType::create($data);
            $message = 'Fee Type created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $feeType = FeeType::findOrFail($id);

        $this->feeTypeId   = $feeType->id;
        $this->name        = $feeType->name;
        $this->description = $feeType->description;
        $this->is_active   = $feeType->is_active;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        FeeType::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Fee Type deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'is_active', 'feeTypeId']);
        $this->is_active = true;
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
        $feeTypes = FeeType::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.fee.type.index', compact('feeTypes'));
    }

}
