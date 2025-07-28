<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectMarkDistributionController extends Controller
{
    //
    public function index()
    {
        return view('backend.exam.subject-mark-distribution.index');
    }
}
