<?php

namespace App\Helpers;

use App\Models\ClassSubjectAssign;
use App\Models\FinalMarkConfiguration;
use App\Models\MarkDistribution;
use App\Models\StudentMark;
use App\Models\SubjectMarkDistribution;

class SchoolHighestMarkHelper
{
    public static function getHighestMark($students, $subjectId, $classId, $sectionId, $examId)
    {
        $highestMark = 0;
        $topStudent = null;

        foreach ($students as $student) {
            if ($student->school_class_id != $classId || $student->class_section_id != $sectionId) {
                continue;
            }

            // Get final mark configuration for this subject
            $finalConfig = FinalMarkConfiguration::where('school_class_id', $classId)
                ->where('subject_id', $subjectId)
                ->first();

            if (!$finalConfig) {
                continue;
            }


            // class test
            $markDistribution = MarkDistribution::where('name', 'Class Test')->first();

            $classTestMark = StudentMark::where('student_id', $student->id)
                ->where('subject_id', $subjectId)
                ->where('school_class_id', $student->school_class_id)
                ->where('exam_id', $examId)
                ->where('mark_distribution_id', $markDistribution->id)
                ->first();

            $finalClassTestMark = $classTestMark ? $classTestMark->marks_obtained : 0;

            $otherMarkDistributions = MarkDistribution::where('name', '!=', 'Class Test')->get();

            $totalSubjectMark = 0;
            foreach ($otherMarkDistributions as $distribution) {
                $markDistribution = MarkDistribution::where('name', $distribution->name)->first();

                $studentSubjectMark = StudentMark::where('student_id', $student->id)
                    ->where('subject_id', $subjectId)
                    ->where('school_class_id', $student->school_class_id)
                    ->where('exam_id', $examId)
                    ->where('mark_distribution_id', $markDistribution->id)
                    ->first();
                $marksObtained = $studentSubjectMark ? $studentSubjectMark->marks_obtained : 0;
                $totalSubjectMark += $marksObtained;
            }

            $calculatedMark = round(($totalSubjectMark * $finalConfig->final_result_weight_percentage) / 100);

            $totalMark = $calculatedMark + $finalClassTestMark;


            if ($totalMark > $highestMark) {
                $highestMark = $totalMark;
                $topStudent = $student;
            }
        }

        return [
            'highest_mark' => $highestMark,
            'student' => $topStudent
        ];
    }
}
