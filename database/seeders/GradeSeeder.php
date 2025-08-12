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
        // Grading scale 100 marks
        $grades100 = [
            ['grade_name' => 'A+', 'grade_point' => 5.00, 'start_marks' => 80, 'end_marks' => 100, 'remarks' => 'Excellent', 'grading_scale' => 100],
            ['grade_name' => 'A', 'grade_point' => 4.00, 'start_marks' => 70, 'end_marks' => 79, 'remarks' => 'Very Good', 'grading_scale' => 100],
            ['grade_name' => 'A-', 'grade_point' => 3.50, 'start_marks' => 60, 'end_marks' => 69, 'remarks' => 'Good', 'grading_scale' => 100],
            ['grade_name' => 'B', 'grade_point' => 3.00, 'start_marks' => 50, 'end_marks' => 59, 'remarks' => 'Satisfactory', 'grading_scale' => 100],
            ['grade_name' => 'C', 'grade_point' => 2.00, 'start_marks' => 40, 'end_marks' => 49, 'remarks' => 'Pass', 'grading_scale' => 100],
            ['grade_name' => 'D', 'grade_point' => 1.00, 'start_marks' => 33, 'end_marks' => 39, 'remarks' => 'Weak', 'grading_scale' => 100],
            ['grade_name' => 'F', 'grade_point' => 0.00, 'start_marks' => 0, 'end_marks' => 32, 'remarks' => 'Fail', 'grading_scale' => 100],
        ];

        // Grading scale 50 marks (according to the text you shared)
        $grades50 = [
            ['grade_name' => 'A+', 'grade_point' => 5.00, 'start_marks' => 40, 'end_marks' => 50, 'remarks' => 'Excellent', 'grading_scale' => 50],
            ['grade_name' => 'A', 'grade_point' => 4.00, 'start_marks' => 35, 'end_marks' => 39, 'remarks' => 'Very Good', 'grading_scale' => 50],
            ['grade_name' => 'A-', 'grade_point' => 3.50, 'start_marks' => 30, 'end_marks' => 34, 'remarks' => 'Good', 'grading_scale' => 50],
            ['grade_name' => 'B', 'grade_point' => 3.00, 'start_marks' => 25, 'end_marks' => 29, 'remarks' => 'Satisfactory', 'grading_scale' => 50],
            ['grade_name' => 'C', 'grade_point' => 2.00, 'start_marks' => 20, 'end_marks' => 24, 'remarks' => 'Pass', 'grading_scale' => 50],
            ['grade_name' => 'F', 'grade_point' => 0.00, 'start_marks' => 0, 'end_marks' => 19, 'remarks' => 'Fail', 'grading_scale' => 50],
        ];

        // Insert all grades
        foreach (array_merge($grades100, $grades50) as $grade) {
            Grade::create($grade);
        }
    }
}
