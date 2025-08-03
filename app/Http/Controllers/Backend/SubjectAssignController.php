<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectAssignController extends Controller
{
    //
    public function index()
    {
        return view('backend.academic.subject-assign.index');
    }

    public function create()
    {
        return view('backend.academic.subject-assign.create');
    }

    public function edit($subjectAssignId)
    {
        return view('backend.academic.subject-assign.edit', compact('subjectAssignId'));
    }
}
