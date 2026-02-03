<?php

namespace App\Livewire\Backend\Website\History;

use App\Models\History;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $history_id;
    public $title, $description;
    public $image, $new_image;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'new_image' => 'nullable|image|max:2048', // 2MB limit
        ];
    }

    public function mount()
    {
        // We target the first record (ID 1)
        $history = History::first();

        if ($history) {
            $this->history_id = $history->id;
            $this->title = $history->title;
            $this->description = $history->description;
            $this->image = $history->image;
        }
    }

    public function save()
    {
        $this->validate();

        $imagePath = $this->image;

        if ($this->new_image) {
            $imagePath = $this->new_image->store('history', 'public');
        }

        // updateOrCreate ensures we either update ID 1 or create it if it doesn't exist
        History::updateOrCreate(
            ['id' => 1],
            [
                'title' => $this->title,
                'description' => $this->description,
                'image' => $imagePath,
            ]
        );

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'History updated successfully!',
        ]);

        // Refresh state
        $this->image = $imagePath;
        $this->new_image = null;
    }

    public function render()
    {
        return view('livewire.backend.website.history.manage');
    }
}
