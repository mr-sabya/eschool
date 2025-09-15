<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // teacher list
    public function teacherList()
    {
        return view('frontend.teacher.index');
    }
}
