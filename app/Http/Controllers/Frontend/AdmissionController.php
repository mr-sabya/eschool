<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    // admission info
    public function info()
    {
        return view('frontend.admission.info.index');
    }


    // admission form
    public function form()
    {
        return view('frontend.admission.form.index');
    }
}
