<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // expense head
    public function expenseHead()
    {
        return view('backend.accounts.expense-head.index');
    }

    // expense
    public function expense()
    {
        return view('backend.accounts.expense.index');
    }
}
