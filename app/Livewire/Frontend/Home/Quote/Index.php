<?php

namespace App\Livewire\Frontend\Home\Quote;

use App\Models\Quote;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        // Fetch active quotes ordered by the defined 'order' column
        $quotes = Quote::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('livewire.frontend.home.quote.index', [
            'quotes' => $quotes
        ]);
    }
}
