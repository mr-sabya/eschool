<?php

namespace App\Livewire\Backend\Accounts\IncomeHead;

use App\Models\IncomeHead;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $description, $incomeHeadId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->incomeHeadId ?? 'NULL';

        return [
            'name' => 'required|unique:income_heads,name,' . $id,
            'description' => 'nullable|string|max:255',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->incomeHeadId) {
            IncomeHead::findOrFail($this->incomeHeadId)->update($data);
            $message = 'Income Head updated successfully.';
        } else {
            IncomeHead::create($data);
            $message = 'Income Head created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $head = IncomeHead::findOrFail($id);
        $this->incomeHeadId = $head->id;
        $this->name = $head->name;
        $this->description = $head->description;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        IncomeHead::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Income Head deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'incomeHeadId']);
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
        $incomeHeads = IncomeHead::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.accounts.income-head.index', [
            'incomeHeads' => $incomeHeads,
        ]);
    }

}
