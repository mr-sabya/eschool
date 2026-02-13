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

    // admission info
    public function admissionInfo()
    {
        return view('backend.website.admission.index');
    }

    // mdeia management is handled in Livewire component
    public function media()
    {
        return view('backend.website.media.index');
    }

    // govorning body management is handled in Livewire component
    public function governingBody()
    {
        return view('backend.website.governing-body.index');
    }

    // former headmaster management is handled in Livewire component
    public function formerHeadmaster()
    {
        return view('backend.website.former-headmaster.index');
    }
}
