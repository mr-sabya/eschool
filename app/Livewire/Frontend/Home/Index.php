<?php

namespace App\Livewire\Frontend\Home;

use App\Models\Notice;
use App\Models\Setting;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.frontend.home.index', [
            'setting' => Setting::find(1),
            'notices' => Notice::where('is_active', 1)->orderBy('id')->get(),
        ]);
    }
}
