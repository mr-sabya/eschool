<?php

namespace App\Livewire\Backend\Library\BookCategory;

use App\Models\BookCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $bookCategoryId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->bookCategoryId ?? 'NULL';

        return [
            'name' => 'required|unique:book_categories,name,' . $id,
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->bookCategoryId) {
            BookCategory::findOrFail($this->bookCategoryId)->update($data);
            $message = 'Book category updated successfully.';
        } else {
            BookCategory::create($data);
            $message = 'Book category created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $category = BookCategory::findOrFail($id);
        $this->bookCategoryId = $category->id;
        $this->name = $category->name;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        BookCategory::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Book category deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'bookCategoryId']);
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
        $categories = BookCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.library.book-category.index', [
            'categories' => $categories,
        ]);
    }

}
