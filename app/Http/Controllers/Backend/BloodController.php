<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BloodController extends Controller
{
    //
    public function index()
    {
        return view('backend.app-setting.blood.index');
    }
}
