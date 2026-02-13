<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    // photo gallery
    public function photoGallery()
    {
        return view('frontend.media.image');
    }

    // video gallery
    public function videoGallery()
    {
        return view('frontend.media.video');
    }
}
