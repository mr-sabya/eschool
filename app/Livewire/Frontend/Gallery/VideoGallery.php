<?php

namespace App\Livewire\Frontend\Gallery;

use App\Models\MediaGallery;
use Livewire\Component;
use Livewire\WithPagination;

class VideoGallery extends Component
{
    use WithPagination;

    public function render()
    {
        $videos = MediaGallery::where('type', 'video')
            ->where('is_active', true)
            ->latest()
            ->paginate(6);

        return view('livewire.frontend.gallery.video-gallery', [
            'videos' => $videos
        ]);
    }
}
