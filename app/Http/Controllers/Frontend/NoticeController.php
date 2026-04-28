<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    //
    public function details($id)
    {
        $notice = Notice::findOrFail($id);
        return view('frontend.notice.details', compact('notice'));
    }
}
