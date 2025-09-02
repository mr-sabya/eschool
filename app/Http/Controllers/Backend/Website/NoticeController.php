<?php

namespace App\Http\Controllers\Backend\Website;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    // index
    public function index()
    {
        return view('backend.website.notice.index');
    }

    // create
    public function create()
    {
        return view('backend.website.notice.create');
    }

    // edit
    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return view('backend.website.notice.edit', compact('notice'));
    }
}
