<?php

namespace App\Helpers;

use App\Models\ClassSubjectAssign;
use App\Models\FinalMarkConfiguration;
use App\Models\Grade;
use App\Models\MarkDistribution;
use App\Models\StudentMark;
use App\Models\Subject;
use App\Models\SubjectMarkDistribution;

class ResultHelper
{
    public static function getClassResults($students, $examId)
    {
        $results = [];

        foreach ($students as $student) {
            $totalObtainedMarks = 0;
            $totalGradePoints = 0;
            $gpaSubjectCount = 0;
            $failCount = 0;

            // Get all subjects assigned to this student
            $subjectAssigns = ClassSubjectAssign::with('subject.markDistributions')
                ->where('academic_session_id', $student->academic_session_id)
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->get();

            foreach ($subjectAssigns as $assign) {
                $subject = Subject::where('id', $assign->subject_id)->first();

                $finalMarkConfiguration = FinalMarkConfiguration::where('school_class_id', $student->school_class_id)
                    ->where('subject_id', $subject->id)
                    ->first();

                $annualFullMark = $finalMarkConfiguration ? $finalMarkConfiguration->other_parts_total : 0;

                // Check if any mark distribution for this subject excludes it from GPA
                $excludeFromGPA = $finalMarkConfiguration ? $finalMarkConfiguration->exclude_from_gpa : false;


                $markdistributions = SubjectMarkDistribution::where('school_class_id', $student->school_class_id)
                    ->where('class_section_id', $student->class_section_id)
                    ->where('subject_id', $subject->id)
                    ->get();

                $failedAnyDistribution = false;
                $totalCalculatedMark = 0;
                foreach ($markdistributions as $distribution) {

                    $markDistribution = MarkDistribution::where('name', $distribution->markDistribution['name'])->first();

                    $subjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subject->id)
                        ->where('school_class_id', $student->school_class_id)
                        ->where('class_section_id', $student->class_section_id)
                        ->where('mark_distribution_id', $markDistribution ? $markDistribution->id : null)
                        ->first();

                    $studentSubjectMark = null;
                    $studentClassTestMark = null;
                    if ($subjectMarkDistribution) {
                        if ($distribution->markDistribution['name'] == 'Class Test') {
                            $studentClassTestMark = StudentMark::where('student_id', $student->id)
                                ->where('subject_id', $subject->id)
                                ->where('school_class_id', $student->school_class_id)
                                ->where('exam_id', $examId)
                                ->where('mark_distribution_id', $markDistribution->id)
                                ->first();
                        } else {
                            $studentSubjectMark = StudentMark::where('student_id', $student->id)
                                ->where('subject_id', $subject->id)
                                ->where('school_class_id', $student->school_class_id)
                                ->where('exam_id', $examId)
                                ->where('mark_distribution_id', $markDistribution->id)
                                ->first();
                        }
                    }

                    $calculatedMark = 0;


                    if ($studentClassTestMark) {
                        $draftMark = $studentClassTestMark->marks_obtained;
                    } else {
                        if($studentSubjectMark){
                            $draftMark = round(($studentSubjectMark->marks_obtained * $finalMarkConfiguration->final_result_weight_percentage) / 100);
                        }else {
                            $draftMark = 0;
                        }
                    }

                    // fail count
                    if ($draftMark < $distribution->pass_mark) {
                        $failedAnyDistribution = true;
                    }


                    $totalCalculatedMark += $draftMark;

                    // calculate per subject gpa
                    $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
                        ->where('end_marks', '>=', $totalCalculatedMark)
                        ->where('grading_scale', $finalMarkConfiguration->grading_scale)
                        ->first();

                    $gradeName = $grade ? $grade->grade_name : 'N/A';
                    $gradePoint = $grade ? $grade->grade_point : 0;
                }

                // Check if the total calculated mark is less than the pass mark

                if ($failedAnyDistribution) {
                    $failCount++;
                }

                if (!$excludeFromGPA) {
                    $totalObtainedMarks += $totalCalculatedMark;
                    $totalGradePoints += $gradePoint;
                    $gpaSubjectCount++;
                }
            }

            // dd($subjectAssigns);

            // dd($totalGradePoints);

            $finalgpa = $gpaSubjectCount > 0 ? round($totalGradePoints / $gpaSubjectCount, 2) : 0.00;

            $finalGrade = Grade::where('grade_point', '<=', $finalgpa)
                ->orderBy('grade_point', 'desc')
                ->first();

            $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';

            // Override if failed any subject
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
