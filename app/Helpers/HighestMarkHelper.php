<?php

namespace App\Helpers;

use App\Models\ClassSubjectAssign;
use App\Models\FinalMarkConfiguration;
use App\Models\StudentMark;
use App\Models\SubjectMarkDistribution;

class HighestMarkHelper
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

            // Get all mark distributions for this subject in this class/section
            $distributions = SubjectMarkDistribution::where('subject_id', $subjectId)
                ->where('school_class_id', $classId)
                ->where('class_section_id', $sectionId)
                ->get();

            $totalMark = 0;
            $cityMark = 0;
            $other_mark = 0;

            foreach ($distributions as $distribution) {
                $studentMark = StudentMark::where('student_id', $student->id)
                    ->where('subject_id', $subjectId)
                    ->where('exam_id', $examId)
                    ->where('mark_distribution_id', $distribution->mark_distribution_id)
                    ->first();

                if (!$studentMark) {
                    continue;
                }

                if ($distribution->markDistribution['name'] == 'Class Test') {
                    // Add Class Test marks directly
                    $cityMark = $studentMark->marks_obtained;
                } else {
                    // Add weighted marks for other distributions
                    $other_mark = round(($studentMark->marks_obtained * $finalConfig->final_result_weight_percentage) / 100);
                }

                $totalMark = $cityMark + $other_mark;
            }


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
