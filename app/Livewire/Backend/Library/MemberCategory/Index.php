<?php

namespace App\Livewire\Backend\Library\MemberCategory;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\MemberCategory;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $description, $duration_days = 365, $memberCategoryId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->memberCategoryId ?? 'NULL';

        return [
            'name' => 'required|unique:member_categories,name,' . $id,
            'description' => 'nullable|string',
            'duration_days' => 'nullable|integer|min:1',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->memberCategoryId) {
            MemberCategory::findOrFail($this->memberCategoryId)->update($data);
            $message = 'Member category updated successfully.';
        } else {
            MemberCategory::create($data);
            $message = 'Member category created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $category = MemberCategory::findOrFail($id);
        $this->memberCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->duration_days = $category->duration_days;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        MemberCategory::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Member category deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'duration_days', 'memberCategoryId']);
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
        $categories = MemberCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.library.member-category.index', [
            'categories' => $categories,
        ]);
    }

}
