<?php

namespace App\Livewire\Backend\Website\Media;

use App\Models\MediaGallery;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Manage extends Component
{
    use WithFileUploads;

    public $title, $type = 'photo', $category, $file;
    public $media_id;
    public $isEdit = false;

    protected function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'type' => 'required|in:photo,video',
            'category' => 'nullable|string',
            'file' => $this->isEdit ? 'nullable' : 'required' . ($this->type == 'photo' ? '|image|max:2048' : '|mimetypes:video/mp4,video/quicktime|max:20480'),
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'type' => $this->type,
            'category' => $this->category,
        ];

        if ($this->file) {
            $folder = $this->type == 'photo' ? 'photos' : 'videos';
            $data['file_path'] = $this->file->store($folder, 'public');
        }

        MediaGallery::create($data);

        $this->reset(['title', 'file', 'category']);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Media added successfully!']);
    }

    public function delete($id)
    {
        $media = MediaGallery::find($id);
        if ($media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
    }

    public function render()
    {
        return view('livewire.backend.website.media.manage', [
            'items' => MediaGallery::latest()->paginate(10)
        ]);
    }
}
