<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    //
    public function index()
    {
        return view('backend.app-setting.designation.index');
    }
}
