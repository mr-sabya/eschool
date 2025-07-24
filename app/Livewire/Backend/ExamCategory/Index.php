<?php

namespace App\Livewire\Backend\ExamCategory;

use App\Models\ExamCategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $slug, $examCategoryId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->examCategoryId ?? 'NULL';

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:exam_categories,slug,' . $id,
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'name' && empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save()
    {
        $data = $this->validate();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if ($this->examCategoryId) {
            ExamCategory::findOrFail($this->examCategoryId)->update($data);
            $message = 'Exam Category updated successfully.';
        } else {
            ExamCategory::create($data);
            $message = 'Exam Category created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $category = ExamCategory::findOrFail($id);
        $this->examCategoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        ExamCategory::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Exam Category deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'slug', 'examCategoryId']);
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
        $categories = ExamCategory::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.exam-category.index', [
            'categories' => $categories,
        ]);
    }
}
