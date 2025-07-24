<?php

namespace App\Livewire\Backend\AcademicSession;

use App\Models\AcademicSession;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination, WithoutUrlPagination;

    public $name, $start_date, $end_date, $is_active = true, $academicSessionId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->academicSessionId ?? 'NULL';

        return [
            'name' => 'required|string|max:255|unique:academic_sessions,name,' . $id,
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
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

        if ($this->academicSessionId) {
            AcademicSession::findOrFail($this->academicSessionId)->update($data);
            $message = 'Academic Session updated successfully.';
        } else {
            AcademicSession::create($data);
            $message = 'Academic Session created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $session = AcademicSession::findOrFail($id);

        $this->academicSessionId = $session->id;
        $this->name = $session->name;
        $this->start_date = $session->start_date;
        $this->end_date = $session->end_date;
        $this->is_active = $session->is_active;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        AcademicSession::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Academic Session deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'start_date', 'end_date', 'is_active', 'academicSessionId']);
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
        $sessions = AcademicSession::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.academic-session.index', [
            'sessions' => $sessions,
        ]);
    }

}
