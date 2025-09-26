<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // financial reports
    public function finalcialReports()
    {
        return view('backend.report.financial-report');
    }

    // fee collection reprts
    public function feeCollectionReports()
    {
        return view('backend.report.fee-collection-report');
    }


    // daily attendance reprts
    public function dailyAttendaceReports()
    {
        return view('backend.report.daily-attendance-report');
    }

    // daily attendance reprts
    public function subjectAttendaceReports()
    {
        return view('backend.report.subject-attendance-report');
    }
}
