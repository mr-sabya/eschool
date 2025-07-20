<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    // Show the classroom index page
    public function index()
    {
        return view('backend.academic.class-room.index');
    }
}
