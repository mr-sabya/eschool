<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    //
    public function index()
    {
        return view('backend.academic.shift.index');
    }
}
