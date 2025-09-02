<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    //
    public function index()
    {
        return view('backend.website.banner.index');
    }

    // create
    public function create()
    {
        return view('backend.website.banner.create');
    }

    // edit
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('backend.website.banner.edit', compact('banner'));
    }
}
