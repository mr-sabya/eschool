<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ClassSubjectAssign;
use Illuminate\Http\Request;

class ClassSubjectAssignController extends Controller
{
    //
    public function index()
    {
        return view('backend.class-subject-assign.index');
    }

    public function create()
    {
        return view('backend.class-subject-assign.create');
    }

    // edit
    public function edit($id)
    {
        $assignment = ClassSubjectAssign::findOrFail($id);
        return view('backend.class-subject-assign.edit', compact('assignment'));
    }
}
