<?php

namespace App\Livewire\Frontend\Gallery;

use App\Models\MediaGallery;
use Livewire\Component;
use Livewire\WithPagination;

class PhotoGallery extends Component
{
    use WithPagination;

    public function render()
    {
        $photos = MediaGallery::where('type', 'photo')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('livewire.frontend.gallery.photo-gallery', [
            'photos' => $photos
        ]);
    }
}
