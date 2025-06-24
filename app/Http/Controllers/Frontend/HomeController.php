<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        // This method can be used to return the home view or perform any logic needed for the home page
        return view('frontend.home.index');
    }
}
