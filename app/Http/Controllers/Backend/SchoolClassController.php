<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    //
    public function index()
    {
        return view('backend.academic.class.index');
    }
}
