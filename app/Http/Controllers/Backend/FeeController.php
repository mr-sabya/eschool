<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    // fee type management
    public function feeType()
    {
        return view('backend.fee.type.index');
    }


    // fee list management
    public function feeList()
    {
        return view('backend.fee.list.index');
    }
    
    // fee collection
    public function feeCollectionIndex()
    {
        return view('backend.fee.collection.index');
    }
    
    // add fee collect
    public function feeCollectionCreate()
    {
        return view('backend.fee.collection.create');
    }
}
