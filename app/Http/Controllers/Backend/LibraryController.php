<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    // book category
    public function bookCategory()
    {
        return view('backend.library.book-category.index');
    }


    // book
    public function book()
    {
        return view('backend.library.book.index');
    }

    // create new book
    public function createBook()
    {
        return view('backend.library.book.create');
    }

    // member category 
    public function memberCategory()
    {
        return view('backend.library.member-category.index');
    }


    // member
    public function member()
    {
        return view('backend.library.member.index');
    }

    // create new member
    public function createMember()
    {
        return view('backend.library.member.create');
    }


    // book issue
    public function bookIssue()
    {
        return view('backend.library.book-issue.index');
    }

    // create new book issue/issue book
    public function createBookIssue()
    {
        return view('backend.library.book-issue.create');
    }
}
