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

    // import staff
    public function import()
    {
        return view('backend.staff.import');
    }

    public function download()
    {
        $path = storage_path('app/public/staff_template.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template not found');
        }

        return response()->download($path, 'staff_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
