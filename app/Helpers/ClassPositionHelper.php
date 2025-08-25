<?php

namespace App\Helpers;

use App\Models\ClassSubjectAssign;
use App\Models\FinalMarkConfiguration;
use App\Models\Grade;
use App\Models\MarkDistribution;
use App\Models\StudentMark;
use App\Models\Subject;
use App\Models\SubjectMarkDistribution;

class ClassPositionHelper
{
    public static function getClassResults($students, $examId)
    {
        $results = [];

        foreach ($students as $student) {
            $totalObtainedMarks = 0;
            $totalGradePoints = 0;
            $gpaSubjectCount = 0;
            $failCount = 0;

            // ✅ check if student has 4th subject
            $fourthSubjectMark = StudentMark::where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->where('is_fourth_subject', 1)
                ->first();

            $fourthSubjectId = $fourthSubjectMark ? $fourthSubjectMark->subject_id : null;

            // Get all subjects assigned to this student
            $subjectAssigns = ClassSubjectAssign::with('subject.markDistributions')
                ->where('academic_session_id', $student->academic_session_id)
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->get();

            foreach ($subjectAssigns as $assign) {
                $subject = Subject::where('id', $assign->subject_id)->first();

                // ✅ Skip if this is the 4th subject (we’ll handle later)
                if ($fourthSubjectId && $subject->id == $fourthSubjectId) {
                    continue;
                }

                $finalMarkConfiguration = FinalMarkConfiguration::where('school_class_id', $student->school_class_id)
                    ->where('subject_id', $subject->id)
                    ->first();

                $excludeFromGPA = $finalMarkConfiguration ? $finalMarkConfiguration->exclude_from_gpa : false;

                $failedAnyDistribution = false;
                $totalSubjectMark = 0;

                // ✅ Class test marks
                $ctMarkDistribution = MarkDistribution::where('name', 'Class Test')->first();
                $ctSubjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subject->id)
                    ->where('school_class_id', $student->school_class_id)
                    ->where('class_section_id', $student->class_section_id)
                    ->where('mark_distribution_id', $ctMarkDistribution?->id)
                    ->first();

                $classTestMark = null;
                if ($ctSubjectMarkDistribution) {
                    $classTestMark = StudentMark::where('student_id', $student->id)
                        ->where('subject_id', $subject->id)
                        ->where('school_class_id', $student->school_class_id)
                        ->where('exam_id', $examId)
                        ->where('mark_distribution_id', $ctMarkDistribution->id)
                        ->first();
                }

                if ($classTestMark && !$classTestMark->is_absent && $classTestMark->marks_obtained < $ctSubjectMarkDistribution->pass_mark) {
                    $failedAnyDistribution = true;
                }

                $finalClassTestMark = $classTestMark ? $classTestMark->marks_obtained : 0;

                // ✅ Other marks
                $otherMarkDistributions = MarkDistribution::where('name', '!=', 'Class Test')->get();

                foreach ($otherMarkDistributions as $distribution) {
                    $subjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subject->id)
                        ->where('school_class_id', $student->school_class_id)
                        ->where('class_section_id', $student->class_section_id)
                        ->where('mark_distribution_id', $distribution->id)
                        ->first();

                    $studentSubjectMark = null;
                    if ($subjectMarkDistribution) {
                        $studentSubjectMark = StudentMark::where('student_id', $student->id)
                            ->where('subject_id', $subject->id)
                            ->where('school_class_id', $student->school_class_id)
                            ->where('exam_id', $examId)
                            ->where('mark_distribution_id', $distribution->id)
                            ->first();
                    }

                    if ($studentSubjectMark && $studentSubjectMark->marks_obtained < $subjectMarkDistribution->pass_mark) {
                        $failedAnyDistribution = true;
                    }

                    $marksObtained = $studentSubjectMark ? $studentSubjectMark->marks_obtained : 0;
                    $totalSubjectMark += $marksObtained;
                }

                $calculatedMark = $finalMarkConfiguration
                    ? round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100)
                    : $totalSubjectMark;

                $totalCalculatedMark = $calculatedMark + $finalClassTestMark;

                $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
                    ->where('end_marks', '>=', $totalCalculatedMark)
                    ->where('grading_scale', $finalMarkConfiguration?->grading_scale)
                    ->first();

                $gradePoint = $grade ? $grade->grade_point : 0;

                if($grade && $grade->grade_point == 0) {
                    $failedAnyDistribution = true;
                }

                if ($failedAnyDistribution) {
                    $failCount++;
                }

                if (!$excludeFromGPA) {
                    $totalObtainedMarks += $totalCalculatedMark;
                    $totalGradePoints += $gradePoint;
                    $gpaSubjectCount++;
                }
            }

            // ✅ Handle 4th subject
            if ($fourthSubjectId) {
                // fetch marks same way
                $fourthTotalMarks = StudentMark::where('student_id', $student->id)
                    ->where('subject_id', $fourthSubjectId)
                    ->where('exam_id', $examId)
                    ->sum('marks_obtained');

                $finalMarkConfiguration = FinalMarkConfiguration::where('school_class_id', $student->school_class_id)
                    ->where('subject_id', $fourthSubjectId)
                    ->first();

                $grade = Grade::where('start_marks', '<=', $fourthTotalMarks)
                    ->where('end_marks', '>=', $fourthTotalMarks)
                    ->where('grading_scale', $finalMarkConfiguration?->grading_scale)
                    ->first();

                $gradePoint = $grade ? $grade->grade_point : 0;

                // add marks always
                $totalObtainedMarks += $fourthTotalMarks;

                // GPA adjustment rule
                if ($gradePoint >= 2.0) {
                    $totalGradePoints += ($gradePoint - 2.0);
                }
            }

            // ✅ Final GPA & Grade
            $finalgpa = $gpaSubjectCount > 0 ? round($totalGradePoints / $gpaSubjectCount, 2) : 0.00;

            $finalGrade = Grade::where('grade_point', '<=', $finalgpa)
                ->orderBy('grade_point', 'desc')
                ->first();

            $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';

            $finalResult = $failCount > 0 ? 'Fail' : 'Pass';
            if ($finalResult === 'Fail') {
                $letterGrade = 'F';
                $finalgpa = 0.00;
            }

            $results[] = [
                'student' => $student,
                'total_marks' => $totalObtainedMarks,
                'gpa' => $finalgpa,
                'letter_grade' => $letterGrade,
                'fail_count' => $failCount,
                'final_result' => $finalResult,
            ];
        }

        // Sort merit list: GPA → Total Marks → Fail Count
        usort($results, function ($a, $b) {
            if ($a['gpa'] != $b['gpa']) return $b['gpa'] <=> $a['gpa'];
            if ($a['total_marks'] != $b['total_marks']) return $b['total_marks'] <=> $a['total_marks'];
            return $a['fail_count'] <=> $b['fail_count'];
        });

        // Assign positions with tie handling
        $position = 1;
        $lastGPA = null;
        $lastMarks = null;
        $lastFail = null;
        $skip = 0;

        foreach ($results as $index => &$res) {
            if ($lastGPA === $res['gpa'] && $lastMarks === $res['total_marks'] && $lastFail === $res['fail_count']) {
                $res['position'] = $position;
                $skip++;
            } else {
                $position += $skip;
                $res['position'] = $position;
                $skip = 1;
            }

            $lastGPA = $res['gpa'];
            $lastMarks = $res['total_marks'];
            $lastFail = $res['fail_count'];
        }

        return $results;
    }

    public static function getStudentPosition($studentId, $students, $examId)
    {
        $results = self::getClassResults($students, $examId);

        foreach ($results as $res) {
            if ($res['student']->id == $studentId) return $res;
        }

        return null;
    }
}
