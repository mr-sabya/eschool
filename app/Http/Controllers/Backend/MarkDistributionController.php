<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarkDistributionController extends Controller
{
    //
    public function index()
    {
        return view('backend.exam.mark-distribution.index');
    }
}
