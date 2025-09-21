<?php

namespace App\Livewire\Backend\Routine\Day;

use App\Models\Day; // Make sure to import your Day model
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Form properties
    public $name, $order, $dayId;

    // Table properties
    public $search = '';
    public $sortField = 'order'; // Default sort by order
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        // The unique rule ignores the current day's ID when updating
        $id = $this->dayId ?? 'NULL';

        return [
            'name' => 'required|string|max:255|unique:days,name,' . $id,
            'order' => 'required|integer|min:1',
        ];
    }

    public function updated($property)
    {
        // Real-time validation
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->dayId) {
            // Update existing day
            Day::findOrFail($this->dayId)->update($data);
            $message = 'Day updated successfully.';
        } else {
            // Create new day
            Day::create($data);
            $message = 'Day created successfully.';
        }

        // Dispatch a browser event for a toast notification
        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $day = Day::findOrFail($id);
        $this->dayId = $day->id;
        $this->name = $day->name;
        $this->order = $day->order;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        Day::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Day deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'order', 'dayId']);
        $this->resetErrorBag();
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
        $days = Day::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.routine.day.index', [
            'days' => $days,
        ]);
    }
}
