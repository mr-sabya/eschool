<?php

namespace App\Livewire\Frontend\Theme;

use App\Models\Notice;
use Livewire\Component;

class Header extends Component
{
    public function render()
    {
        return view('livewire.frontend.theme.header', [
            'notices' => Notice::where('is_active', 1)->orderBy('id')->get(),
        ]);
    }
}
