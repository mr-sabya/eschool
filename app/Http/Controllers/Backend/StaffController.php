<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    //
    public function index()
    {
        return view('backend.staff.index');
    }

    public function create()
    {
        return view('backend.staff.create');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.staff.edit', compact('user'));
    }
}
