<?php

namespace App\Livewire\Backend\Grade;

use App\Models\Grade;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $grade_name, $grade_point, $start_marks, $end_marks, $remarks, $gradeId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'grade_name' => 'required|string|max:5',
            'grade_point' => 'required|numeric|between:0,5',
            'start_marks' => 'required|integer|between:0,100',
            'end_marks' => 'required|integer|between:0,100|gte:start_marks',
            'remarks' => 'nullable|string|max:255',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->gradeId) {
            Grade::findOrFail($this->gradeId)->update($data);
            $message = 'Grade updated successfully.';
        } else {
            Grade::create($data);
            $message = 'Grade created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        $this->gradeId = $grade->id;
        $this->grade_name = $grade->grade_name;
        $this->grade_point = $grade->grade_point;
        $this->start_marks = $grade->start_marks;
        $this->end_marks = $grade->end_marks;
        $this->remarks = $grade->remarks;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        Grade::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Grade deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'gradeId',
            'grade_name',
            'grade_point',
            'start_marks',
            'end_marks',
            'remarks'
        ]);
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
        $grades = Grade::where('grade_name', 'like', "%{$this->search}%")
            ->orWhere('remarks', 'like', "%{$this->search}%")
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.grade.index', compact('grades'));
    }
}
