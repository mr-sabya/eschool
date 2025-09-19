<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //show login form
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }
}
