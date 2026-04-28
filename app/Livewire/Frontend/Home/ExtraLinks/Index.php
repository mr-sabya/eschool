<?php

namespace App\Livewire\Frontend\Home\ExtraLinks;

use App\Models\LinkCategory;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        // We fetch categories with their related links efficiently
        $categories = LinkCategory::with('links')
            ->orderBy('order', 'asc')
            ->get();

        return view('livewire.frontend.home.extra-links.index', [
            'categories' => $categories
        ]);
    }
}
