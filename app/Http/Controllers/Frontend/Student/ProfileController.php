<?php

namespace App\Http\Controllers\Frontend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // profile
    public function index()
    {
        return view('frontend.student.profile.index');
    }
}
