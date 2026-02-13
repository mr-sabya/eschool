<?php

namespace App\Livewire\Frontend\GoverningBody;

use App\Models\GoverningBody;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $members = GoverningBody::where('is_active', true)
            ->where('type', 'current')
            ->orderBy('rank', 'asc')
            ->get();

        return view('livewire.frontend.governing-body.index', compact('members'));
    }
}
