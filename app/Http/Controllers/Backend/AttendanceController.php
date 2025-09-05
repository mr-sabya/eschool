<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //subject attendance manage
    public function subjectAttendaceManage()
    {
        return view('backend.attendance.subject.manage');
    }


    // daily attendance manage
    public function dailyAttendaceManage()
    {
        return view('backend.attendance.daily.manage');
    }
}
