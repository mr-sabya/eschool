<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentMarkController extends Controller
{
    // This controller handles the management of student marks.
    public function index()
    {
        // Logic to display student marks
    }

    // create a new student mark entry
    public function create()
    {
        // Logic to show the form for creating a new student mark   
        return view('backend.exam.student-mark.create');
    }
}
