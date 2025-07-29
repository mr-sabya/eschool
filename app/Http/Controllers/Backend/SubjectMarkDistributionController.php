<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SubjectMarkDistribution;
use Illuminate\Http\Request;

class SubjectMarkDistributionController extends Controller
{
    //
    public function index()
    {
        return view('backend.exam.subject-mark-distribution.index');
    }

    // create method is handled by Livewire component
    public function create()
    {
        return view('backend.exam.subject-mark-distribution.create');
    }

    // edit
    public function edit($id)
    {
        $subjectMarkDistribution = SubjectMarkDistribution::findOrFail($id);
        return view('backend.exam.subject-mark-distribution.edit', compact('subjectMarkDistribution'));
    }
}
