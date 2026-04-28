<?php

namespace App\Livewire\Frontend\Home\Slider;

use App\Models\Banner;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        // Fetch active banners ordered by the 'order' field
        $banners = Banner::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('livewire.frontend.home.slider.index', [
            'banners' => $banners
        ]);
    }
}