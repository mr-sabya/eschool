<?php

namespace App\Livewire\Backend\Accounts\ExpenseHead;

use App\Models\ExpenseHead;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $description, $expenseHeadId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->expenseHeadId ?? 'NULL';

        return [
            'name' => 'required|unique:expense_heads,name,' . $id,
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

        if ($this->expenseHeadId) {
            ExpenseHead::findOrFail($this->expenseHeadId)->update($data);
            $message = 'Expense Head updated successfully.';
        } else {
            ExpenseHead::create($data);
            $message = 'Expense Head created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $head = ExpenseHead::findOrFail($id);
        $this->expenseHeadId = $head->id;
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
        ExpenseHead::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Expense Head deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'expenseHeadId']);
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
        $heads = ExpenseHead::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.accounts.expense-head.index', [
            'heads' => $heads,
        ]);
    }
}
