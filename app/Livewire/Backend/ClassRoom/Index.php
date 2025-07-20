<?php

namespace App\Livewire\Backend\ClassRoom;

use App\Models\ClassRoom;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $room_number, $room_name, $room_type = 'Lecture', $capacity = 30, $location, $is_active = true, $classRoomId;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    public $confirmingDeleteId = null;

    protected function rules()
    {
        $id = $this->classRoomId ?? 'NULL';

        return [
            'room_number' => 'required|integer|unique:class_rooms,room_number,' . $id,
            'room_name' => 'nullable|string|max:255',
            'room_type' => 'required|in:Lecture,Laboratory,Seminar,Conference',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
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

        if ($this->classRoomId) {
            $classRoom = ClassRoom::findOrFail($this->classRoomId);
            $classRoom->update($data);
            $message = 'ClassRoom updated successfully.';
        } else {
            ClassRoom::create($data);
            $message = 'ClassRoom created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $room = ClassRoom::findOrFail($id);
        $this->classRoomId = $room->id;
        $this->room_number = $room->room_number;
        $this->room_name = $room->room_name;
        $this->room_type = $room->room_type;
        $this->capacity = $room->capacity;
        $this->location = $room->location;
        $this->is_active = $room->is_active;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        ClassRoom::findOrFail($this->confirmingDeleteId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'ClassRoom deleted successfully.']);
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['room_number', 'room_name', 'room_type', 'capacity', 'location', 'is_active', 'classRoomId']);
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
        $rooms = ClassRoom::where('room_name', 'like', '%' . $this->search . '%')
            ->orWhere('room_number', 'like', '%' . $this->search . '%')
            ->orWhere('room_type', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.class-room.index', [
            'rooms' => $rooms,
        ]);
    }


}
