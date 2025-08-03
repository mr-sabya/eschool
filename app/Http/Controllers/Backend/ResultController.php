<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //
    public function index()
    {
        return view('backend.result.index');
    }

    // show
    public function show($studentId, $examId, $classId, $sectionId, $sessionId)
    {
        return view('backend.result.show', compact('studentId', 'examId', 'classId', 'sectionId', 'sessionId'));
    }

    // generate PDF
    public function generatePdf()
    {
        return view('backend.result.generate');
    }
}
