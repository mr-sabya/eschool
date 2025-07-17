<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function index()
    {
        return view('backend.student.index');
    }

    // create student
    public function create()
    {
        return view('backend.student.create');
    }
}
