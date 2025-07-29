<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FinalMarkConfiguration;
use Illuminate\Http\Request;

class FinalMarkConfigurationController extends Controller
{
    //
    public function index()
    {
        return view('backend.exam.final-mark-configuration.index');
    }

    // This method is for creating a new final mark configuration
    public function create()
    {
        return view('backend.exam.final-mark-configuration.create');
    }

    // edit method can be added here if needed
    public function edit($id)
    {
        // Logic for editing a final mark configuration
        $finalMarkConfiguration = FinalMarkConfiguration::findOrFail($id);
        return view('backend.exam.final-mark-configuration.edit', compact('finalMarkConfiguration'));
    }
}
