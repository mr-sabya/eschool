<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamCategoryController extends Controller
{
    //
    public function index()
    {
        return view('backend.exam.category.index');
    }
}
