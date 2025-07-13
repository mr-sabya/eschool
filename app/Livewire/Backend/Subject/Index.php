<?php

namespace App\Livewire\Backend\Subject;

use App\Models\Subject;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $name, $code, $type = 'theory', $subjectId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->subjectId ?? 'NULL';

        return [
            'name' => 'required|unique:subjects,name,' . $id,
            'code' => 'nullable|unique:subjects,code,' . $id,
            'type' => 'required|in:theory,practical',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->subjectId) {
            Subject::findOrFail($this->subjectId)->update($data);
            $message = 'Subject updated successfully.';
        } else {
            Subject::create($data);
            $message = 'Subject created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $this->subjectId = $subject->id;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->type = $subject->type;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        Subject::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Subject deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'code', 'type', 'subjectId']);
        $this->type = 'theory';
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
        $subjects = Subject::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.subject.index', [
            'subjects' => $subjects,
        ]);
    }

}
