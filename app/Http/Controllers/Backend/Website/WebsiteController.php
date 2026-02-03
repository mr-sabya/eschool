<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    //history management is handled in Livewire component
    public function history()
    {
        return view('backend.website.history.index');
    }

    // quote management is handled in Livewire component
    public function quote()
    {
        return view('backend.website.quote.index');
    }
}
