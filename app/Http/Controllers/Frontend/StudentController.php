<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function index($numeric)
    {
        $class = SchoolClass::where('numeric_name', $numeric)->firstOrFail();
        return view('frontend.student.index', compact('class'));
    }
}
