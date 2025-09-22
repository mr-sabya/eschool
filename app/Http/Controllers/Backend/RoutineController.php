<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    // day
    public function day()
    {
        return view('backend.routine.day.index');
    }

    // time slot
    public function timeSlot()
    {
        return view('backend.routine.time-slot.index');
    }

    // routine
    public function index()
    {
        return view('backend.routine.routine.index');
    }

    // exam routine
    public function examRoutine()
    {
        return view('backend.routine.exam-routine.index');
    }
}
