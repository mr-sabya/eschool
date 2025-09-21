<?php

namespace App\Livewire\Backend\Routine\TimeSlot;

use App\Models\TimeSlot; // Import the TimeSlot model
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    // Form properties
    public $name, $start_time, $end_time, $timeSlotId;

    // Table properties
    public $search = '';
    public $sortField = 'start_time'; // Default sort by the start time
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
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

        if ($this->timeSlotId) {
            // Update existing time slot
            TimeSlot::findOrFail($this->timeSlotId)->update($data);
            $message = 'Time slot updated successfully.';
        } else {
            // Create new time slot
            TimeSlot::create($data);
            $message = 'Time slot created successfully.';
        }

        // Dispatch a browser event for a toast notification
        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        $this->timeSlotId = $timeSlot->id;
        $this->name = $timeSlot->name;
        $this->start_time = $timeSlot->start_time;
        $this->end_time = $timeSlot->end_time;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        TimeSlot::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Time slot deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['name', 'start_time', 'end_time', 'timeSlotId']);
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
        $timeSlots = TimeSlot::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.routine.time-slot.index', [
            'timeSlots' => $timeSlots,
        ]);
    }
}
