<?php

namespace App\Livewire\Frontend\Theme;

use App\Models\SchoolClass;
use Livewire\Component;

class Menu extends Component
{
    public function render()
    {
        return view('livewire.frontend.theme.menu',[
            'classes' => SchoolClass::all()
        ]);
    }
}
