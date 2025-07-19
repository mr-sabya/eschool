<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Http\Controllers\Controller;

class GuardianController extends Controller
{
    //
    public function index()
    {
        return view('backend.guardian.index');
    }

    // create
    public function create()
    {
        return view('backend.guardian.create');
    }

    // show edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.guardian.edit', compact('user'));
    }
}
