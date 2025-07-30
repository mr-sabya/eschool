<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            ['grade_name' => 'A+', 'grade_point' => 5.00, 'start_marks' => 80, 'end_marks' => 100, 'remarks' => 'Excellent'],
            ['grade_name' => 'A', 'grade_point' => 4.00, 'start_marks' => 70, 'end_marks' => 79, 'remarks' => 'Very Good'],
            ['grade_name' => 'A-', 'grade_point' => 3.50, 'start_marks' => 60, 'end_marks' => 69, 'remarks' => 'Good'],
            ['grade_name' => 'B', 'grade_point' => 3.00, 'start_marks' => 50, 'end_marks' => 59, 'remarks' => 'Satisfactory'],
            ['grade_name' => 'C', 'grade_point' => 2.00, 'start_marks' => 40, 'end_marks' => 49, 'remarks' => 'Pass'],
            ['grade_name' => 'D', 'grade_point' => 1.00, 'start_marks' => 33, 'end_marks' => 39, 'remarks' => 'Weak'],
            ['grade_name' => 'F', 'grade_point' => 0.00, 'start_marks' => 0, 'end_marks' => 32, 'remarks' => 'Fail'],
        ];

        foreach ($grades as $grade) {
            Grade::create($grade);
        }
    }
}
