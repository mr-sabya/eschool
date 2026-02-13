<?php

namespace App\Livewire\Frontend\FormerHeadmaster;

use App\Models\FormerHeadmaster;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.frontend.former-headmaster.index', [
            'headmasters' => FormerHeadmaster::where('is_active', true)->orderBy('rank', 'asc')->get()
        ]);
    }
}
