<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    //
    public function index()
    {
        // Logic to display the list of exams
        return view('backend.exam.exam.index');
    }
}
