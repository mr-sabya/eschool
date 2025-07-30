<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //
    public function downloadPdf()
    {
        // Logic to display results
        $student = [
            'name' => 'ADRIKA ZARA',
            'id' => '270739',
            'class' => 'Eight',
            'section' => 'General',
            'roll' => '8001'
        ];

        $subjects = [
            [
                'name' => 'Bangla',
                'full_mark' => 100,
                'ct' => 6,
                'a1' => 8,
                'a2' => 8,
                'annual' => 41,
                'cal_ct' => 3.00,
                'cal_a1' => 8.00,
                'cal_a2' => 8.00,
                'cal_annual' => 28.70,
                'total' => 47.70,
                'highest' => 71.90,
                'gpa' => 2.0,
                'grade' => 'C',
                'result' => 'Passed'
            ],
            [
                'name' => 'English',
                'full_mark' => 100,
                'ct' => 12,
                'a1' => 7,
                'a2' => 10,
                'annual' => 60,
                'cal_ct' => 6.00,
                'cal_a1' => 7.00,
                'cal_a2' => 10.00,
                'cal_annual' => 42.35,
                'total' => 65.35,
                'highest' => 89.75,
                'gpa' => 3.5,
                'grade' => 'A-',
                'result' => 'Passed'
            ],
            [
                'name' => 'Mathematics',
                'full_mark' => 100,
                'ct' => 17,
                'a1' => 10,
                'a2' => 10,
                'annual' => 70,
                'cal_ct' => 8.50,
                'cal_a1' => 10.00,
                'cal_a2' => 10.00,
                'cal_annual' => 49.00,
                'total' => 77.50,
                'highest' => 96.90,
                'gpa' => 4.0,
                'grade' => 'A',
                'result' => 'Passed'
            ],
            [
                'name' => 'Science',
                'full_mark' => 100,
                'ct' => 15,
                'a1' => 10,
                'a2' => 10,
                'annual' => 60,
                'cal_ct' => 7.50,
                'cal_a1' => 10.00,
                'cal_a2' => 10.00,
                'cal_annual' => 42.00,
                'total' => 69.50,
                'highest' => 92.50,
                'gpa' => 3.5,
                'grade' => 'A-',
                'result' => 'Passed'
            ],
            [
                'name' => 'Religious and Moral Education',
                'full_mark' => 100,
                'ct' => 14,
                'a1' => 10,
                'a2' => 5,
                'annual' => 44,
                'cal_ct' => 7.00,
                'cal_a1' => 10.00,
                'cal_a2' => 5.00,
                'cal_annual' => 30.80,
                'total' => 52.80,
                'highest' => 81.70,
                'gpa' => 3.0,
                'grade' => 'B',
                'result' => 'Passed'
            ],
            [
                'name' => 'History & Social Science',
                'full_mark' => 100,
                'ct' => 11,
                'a1' => 10,
                'a2' => 10,
                'annual' => 39,
                'cal_ct' => 5.50,
                'cal_a1' => 10.00,
                'cal_a2' => 10.00,
                'cal_annual' => 27.30,
                'total' => 52.80,
                'highest' => 85.60,
                'gpa' => 3.0,
                'grade' => 'B',
                'result' => 'Passed'
            ],
            [
                'name' => 'Digital Technology',
                'full_mark' => 100,
                'ct' => 16,
                'a1' => 10,
                'a2' => 10,
                'annual' => 70,
                'cal_ct' => 8.00,
                'cal_a1' => 10.00,
                'cal_a2' => 10.00,
                'cal_annual' => 49.00,
                'total' => 77.00,
                'highest' => 93.00,
                'gpa' => 4.0,
                'grade' => 'A',
                'result' => 'Passed'
            ],
            [
                'name' => 'Wellbeing',
                'full_mark' => 100,
                'ct' => 13,
                'a1' => 10,
                'a2' => 10,
                'annual' => 64,
                'cal_ct' => 6.50,
                'cal_a1' => 10.00,
                'cal_a2' => 10.00,
                'cal_annual' => 44.80,
                'total' => 71.30,
                'highest' => 93.90,
                'gpa' => 4.0,
                'grade' => 'A',
                'result' => 'Passed'
            ],
            [
                'name' => 'Life & Livelihood',
                'full_mark' => 100,
                'ct' => 13,
                'a1' => 10,
                'a2' => 10,
                'annual' => 71,
                'cal_ct' => 6.50,
                'cal_a1' => 10.00,
                'cal_a2' => 10.00,
                'cal_annual' => 49.70,
                'total' => 76.20,
                'highest' => 93.00,
                'gpa' => 4.0,
                'grade' => 'A',
                'result' => 'Passed'
            ],
            [
                'name' => 'Art & Culture',
                'full_mark' => 100,
                'ct' => 7,
                'a1' => 6,
                'a2' => 5,
                'annual' => 57,
                'cal_ct' => 3.50,
                'cal_a1' => 6.00,
                'cal_a2' => 5.00,
                'cal_annual' => 39.90,
                'total' => 54.40,
                'highest' => 89.70,
                'gpa' => 3.0,
                'grade' => 'B',
                'result' => 'Passed'
            ]
        ];


        $summary = [
            'total' => 645,
            'grade' => 'B',
            'gpa' => 3.4,
            'result' => 'PASSED',
            'position' => '35th',
            'comment' => 'Need Improvement'
        ];

        return view('backend.result.pdf', compact('student', 'subjects', 'summary'));
    }
}
