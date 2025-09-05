<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    // leave type
    public function leaveType()
    {
        return view('backend.leave.type.index');
    }


    // student leave
    public function studentLeave()
    {
        return view('backend.leave.student-leave.index');
    }
}
