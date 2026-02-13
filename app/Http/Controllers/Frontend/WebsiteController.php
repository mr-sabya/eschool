<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    // governing body page
    public function governingBody()
    {
        return view('frontend.pages.governing-body');
    }

    // staff page
    public function staff()
    {
        return view('frontend.pages.staff');
    }
}
