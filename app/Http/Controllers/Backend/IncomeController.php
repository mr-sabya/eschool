<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    //income heads
    public function incomeHeads()
    {
        return view('backend.accounts.income-head.index');
    }

    //incomes
    public function income()
    {
        return view('backend.accounts.income.index');
    }
}
