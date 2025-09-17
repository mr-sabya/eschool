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
            $ctMarkDistribution = MarkDistribution::where('name', 'Class Test')->first();

            $ctSubjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subjectId)
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->where('mark_distribution_id', $ctMarkDistribution ? $ctMarkDistribution->id : null)
                ->first();


            if ($ctSubjectMarkDistribution) {
                $classTestMark = StudentMark::where('student_id', $student->id)
                    ->where('subject_id', $subjectId)
                    ->where('school_class_id', $student->school_class_id)
                    ->where('exam_id', $examId)
                    ->where('mark_distribution_id', $ctMarkDistribution->id)
                    ->first();
            } else {
                $classTestMark = null;
            }



            $finalClassTestMark = $classTestMark ? $classTestMark->marks_obtained : 0;

            $otherMarkDistributions = MarkDistribution::where('name', '!=', 'Class Test')->get();

            $totalSubjectMark = 0;
            foreach ($otherMarkDistributions as $distribution) {
                $getMarkDistribution = MarkDistribution::where('name', $distribution->name)->first();


                $subjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subjectId)
                    ->where('school_class_id', $student->school_class_id)
                    ->where('class_section_id', $student->class_section_id)
                    ->where('mark_distribution_id', $getMarkDistribution ? $getMarkDistribution->id : null)
                    ->first();

                $studentSubjectMark = null;

                if ($subjectMarkDistribution) {
                    $studentSubjectMark = StudentMark::where('student_id', $student->id)
                        ->where('subject_id', $subjectId)
                        ->where('school_class_id', $student->school_class_id)
                        ->where('exam_id', $examId)
                        ->where('mark_distribution_id', $getMarkDistribution->id)
                        ->first();
                }

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

    public static function getStudentSubjectMarks($studentId, $subjectId, $classId, $sectionId, $examId)
    {
        // defaults (always present)
        $result = [
            'total' => 0,
            'calculated' => 0,
            'class_test' => 0,
            'other_total' => 0,
            'is_absent' => false,
        ];

        $finalConfig = FinalMarkConfiguration::where('school_class_id', $classId)
            ->where('subject_id', $subjectId)
            ->first();

        if (!$finalConfig) {
            return $result;
        }

        // --- Class Test part ---
        $ctDistribution = MarkDistribution::where('name', 'Class Test')->first();
        if ($ctDistribution) {
            $ctSubjectDistribution = SubjectMarkDistribution::where('subject_id', $subjectId)
                ->where('school_class_id', $classId)
                ->where('class_section_id', $sectionId)
                ->where('mark_distribution_id', $ctDistribution->id)
                ->first();

            if ($ctSubjectDistribution) {
                $ctMark = StudentMark::where('student_id', $studentId)
                    ->where('subject_id', $subjectId)
                    ->where('exam_id', $examId)
                    ->where('mark_distribution_id', $ctDistribution->id)
                    ->first();

                if ($ctMark) {
                    $result['class_test'] = $ctMark->is_absent ? 0 : ($ctMark->marks_obtained ?? 0);
                    if ($ctMark->is_absent) {
                        $result['is_absent'] = true;
                    }
                }
            }
        }

        // --- Other distributions (non CT) ---
        $otherDistributions = MarkDistribution::where('name', '!=', 'Class Test')->get();
        foreach ($otherDistributions as $distribution) {
            $subDist = SubjectMarkDistribution::where('subject_id', $subjectId)
                ->where('school_class_id', $classId)
                ->where('class_section_id', $sectionId)
                ->where('mark_distribution_id', $distribution->id)
                ->first();

            if (!$subDist) {
                continue;
            }

            $studentMark = StudentMark::where('student_id', $studentId)
                ->where('subject_id', $subjectId)
                ->where('exam_id', $examId)
                ->where('mark_distribution_id', $distribution->id)
                ->first();

            if ($studentMark) {
                if ($studentMark->is_absent) {
                    // flag absent, treat as 0 for totals (you may change to mark fail if needed)
                    $result['is_absent'] = true;
                    // still add 0 to other_total
                } else {
                    $result['other_total'] += ($studentMark->marks_obtained ?? 0);
                }
            }
        }

        // --- final calculations ---
        $result['calculated'] = round(($result['other_total'] * ($finalConfig->final_result_weight_percentage ?? 0)) / 100);
        $result['total'] = $result['calculated'] + $result['class_test'];

        return $result;
    }
}
