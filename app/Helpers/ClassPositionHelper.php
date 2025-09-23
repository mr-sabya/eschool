<?php

namespace App\Helpers;

use App\Models\ClassSubjectAssign;
use App\Models\Exam; // Import the Exam model
use App\Models\FinalMarkConfiguration;
use App\Models\Grade;
use App\Models\MarkDistribution;
use App\Models\StudentMark;
use App\Models\Subject;
use App\Models\SubjectMarkDistribution;
use Illuminate\Support\Collection;

class ClassPositionHelper
{
    /**
     * Generates a full tabulation sheet, respecting the mark distributions allowed by the specific exam.
     * This is the primary, optimized function.
     *
     * @param \Illuminate\Database\Eloquent\Collection $students
     * @param int $examId
     * @return array
     */
    public static function getTabulationSheetResults($students, $examId)
    {
        if ($students->isEmpty()) {
            return [];
        }

        // --- CORE LOGIC: Fetch the Exam and its allowed distributions first ---
        $exam = Exam::with('markDistributionTypes')->find($examId);
        if (!$exam) {
            return [];
        }
        $allowedDistributions = $exam->markDistributionTypes;
        $allowedDistributionIds = $allowedDistributions->pluck('id');

        // 1. EFFICIENT DATA FETCHING
        $studentIds = $students->pluck('id');
        $firstStudent = $students->first();
        $classId = $firstStudent->school_class_id;
        $sectionId = $firstStudent->class_section_id;
        $sessionId = $firstStudent->academic_session_id;

        $assignedSubjects = ClassSubjectAssign::with('subject')->where('academic_session_id', $sessionId)
            ->where('school_class_id', $classId)->where('class_section_id', $sectionId)
            ->when($firstStudent->department_id, fn($q) => $q->where('department_id', $firstStudent->department_id))
            ->get()->keyBy('subject_id');
        $subjectIds = $assignedSubjects->pluck('subject_id');

        $allStudentMarks = StudentMark::where('exam_id', $examId)->whereIn('student_id', $studentIds)
            ->whereIn('subject_id', $subjectIds)->get()->groupBy(['student_id', 'subject_id']);

        $finalMarkConfigs = FinalMarkConfiguration::with('subject')->where('school_class_id', $classId)
            ->whereIn('subject_id', $subjectIds)->get()->keyBy('subject_id');

        $subjectMarkDistributions = SubjectMarkDistribution::where('school_class_id', $classId)
            ->whereIn('subject_id', $subjectIds)->get();

        $grades = Grade::all();

        // 2. PROCESS RESULTS FOR EACH STUDENT
        $results = [];
        foreach ($students as $student) {
            $totalObtainedMarks = 0;
            $totalGradePoints = 0;
            $gpaSubjectCount = 0;
            $failCount = 0;
            $subjectResults = [];

            $studentMarks = $allStudentMarks->get($student->id) ?? collect();
            $fourthSubjectId = $studentMarks->first(fn($sub) => $sub->first()->is_fourth_subject)?->first()->subject_id ?? null;

            foreach ($assignedSubjects as $subjectId => $assignedSubject) {
                $config = $finalMarkConfigs->get($subjectId);
                $marksForSubject = $studentMarks->get($subjectId);

                if (!$config || !$marksForSubject) continue;

                $subjectSpecificDistributions = $subjectMarkDistributions->where('subject_id', $subjectId);

                $subjectResult = self::calculateDetailedSubjectResult($marksForSubject, $config, $subjectSpecificDistributions, $grades, $allowedDistributions, $allowedDistributionIds);

                $subjectResults[] = $subjectResult;

                if ($subjectResult['is_fail']) $failCount++;
                $totalObtainedMarks += $subjectResult['total_calculated_marks'];

                if ($config->exclude_from_gpa) continue;

                $isFourthSubject = ($subjectId === $fourthSubjectId);
                if ($isFourthSubject) {
                    if ($subjectResult['grade_point'] >= 2.0 && !$subjectResult['is_fail']) {
                        $totalGradePoints += ($subjectResult['grade_point'] - 2.0);
                    }
                } else {
                    $totalGradePoints += $subjectResult['grade_point'];
                    $gpaSubjectCount++;
                }
            }

            // 3. FINAL GPA & GRADE CALCULATION
            $isFail = $failCount > 0;
            $finalgpa = $gpaSubjectCount > 0 ? (float) number_format($totalGradePoints / $gpaSubjectCount, 2, '.', '') : 0.00;
            if ($isFail) $finalgpa = 0.00;
            $finalGradeModel = $grades->where('grade_point', '<=', $finalgpa)->sortByDesc('grade_point')->first();
            $letterGrade = $isFail ? 'F' : ($finalGradeModel->grade_name ?? 'N/A');

            $results[] = [
                'student' => $student,
                'subjects' => $subjectResults,
                'total_marks' => $totalObtainedMarks,
                'final_gpa' => $finalgpa,
                'final_grade' => $letterGrade,
                'fail_count' => $failCount,
                'is_fail' => $isFail,
            ];
        }

        // 4. SORTING AND POSITIONING
        usort($results, function ($a, $b) {
            if ($a['is_fail'] != $b['is_fail']) return $a['is_fail'] <=> $b['is_fail'];
            if ($a['final_gpa'] != $b['final_gpa']) return $b['final_gpa'] <=> $a['final_gpa'];
            return $b['total_marks'] <=> $a['total_marks'];
        });

        $position = 0;
        $lastResult = null;
        foreach ($results as $index => &$res) {
            if (is_null($lastResult) || $res['is_fail'] != $lastResult['is_fail'] || $res['final_gpa'] != $lastResult['final_gpa'] || $res['total_marks'] != $lastResult['total_marks']) {
                $position = $index + 1;
            }
            $res['position_in_section'] = $position;
            $res['position_in_class'] = $position;
            $lastResult = $res;
        }

        return $results;
    }

    private static function calculateDetailedSubjectResult(Collection $studentMarks, FinalMarkConfiguration $config, Collection $subjectDistributions, Collection $allGrades, Collection $allowedDistributions, Collection $allowedDistributionIds): array
    {
        $validStudentMarks = $studentMarks->whereIn('mark_distribution_id', $allowedDistributionIds);
        $failedAnyDistribution = false;
        $obtainedMarksByDistribution = [];
        $marksByDistribution = $validStudentMarks->keyBy('mark_distribution_id');
        $classTestMark = 0;
        $otherMarksTotal = 0;
        $ctMarkDistributionId = MarkDistribution::where('name', 'Class Test')->first()->id ?? null;

        foreach ($allowedDistributions as $dist) {
            $distConfig = $subjectDistributions->where('mark_distribution_id', $dist->id)->first();
            if (!$distConfig) {
                $obtainedMarksByDistribution[$dist->id] = null;
                continue;
            }
            $mark = $marksByDistribution->get($dist->id);
            if ($mark) {
                $marksObtained = $mark->marks_obtained;
                $obtainedMarksByDistribution[$dist->id] = $mark->is_absent ? 'Ab' : $marksObtained;
                if (!$mark->is_absent && $marksObtained < $distConfig->pass_mark) {
                    $failedAnyDistribution = true;
                }
                if ($dist->id == $ctMarkDistributionId) {
                    $classTestMark = $marksObtained;
                } else {
                    $otherMarksTotal += $marksObtained;
                }
            } else {
                $obtainedMarksByDistribution[$dist->id] = 0;
            }
        }

        $weightedOtherMarks = round(($otherMarksTotal * $config->final_result_weight_percentage) / 100);
        $totalCalculatedMark = $weightedOtherMarks + $classTestMark;
        $grade = $allGrades->where('grading_scale', $config->grading_scale)
            ->where('start_marks', '<=', $totalCalculatedMark)
            ->where('end_marks', '>=', $totalCalculatedMark)->first();
        $isFail = $failedAnyDistribution || ($grade && $grade->grade_point == 0);

        return [
            'subject_id' => $config->subject_id,
            'obtained_marks_by_distribution' => $obtainedMarksByDistribution,
            'total_calculated_marks' => $totalCalculatedMark,
            'grade_point' => $isFail ? 0.00 : ($grade->grade_point ?? 0.00),
            'grade_name' => $isFail ? 'F' : ($grade->grade_name ?? 'N/A'),
            'is_fail' => $isFail,
        ];
    }

    // =======================================================================================
    // LEGACY FUNCTION - REFACATORED FOR PERFORMANCE AND ACCURACY
    // =======================================================================================

    public static function getClassResults($students, $examId)
    {
        $exam = Exam::with('markDistributionTypes')->find($examId);
        if (!$exam) {
            return [];
        }
        $allowedDistributionIds = $exam->markDistributionTypes->pluck('id');

        // --- Pre-fetch all configurations for the class to avoid queries in loop ---
        $firstStudent = $students->first();
        if (!$firstStudent) return [];
        $classId = $firstStudent->school_class_id;
        $allFinalMarkConfigs = FinalMarkConfiguration::where('school_class_id', $classId)->get()->keyBy('subject_id');
        $allSubjectDistConfigs = SubjectMarkDistribution::where('school_class_id', $classId)->get();
        $ctMarkDistributionId = MarkDistribution::where('name', 'Class Test')->first()->id ?? null;

        $results = [];
        foreach ($students as $student) {
            $totalObtainedMarks = 0;
            $totalGradePoints = 0;
            $gpaSubjectCount = 0;
            $failCount = 0;

            // --- PERFORMANCE OPTIMIZATION: Fetch all VALID marks for the student at once ---
            $allValidMarksForStudent = StudentMark::where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->whereIn('mark_distribution_id', $allowedDistributionIds)
                ->get();

            $fourthSubjectMark = $allValidMarksForStudent->where('is_fourth_subject', 1)->first();
            $fourthSubjectId = $fourthSubjectMark ? $fourthSubjectMark->subject_id : null;

            $subjectAssigns = ClassSubjectAssign::with('subject')
                ->where('academic_session_id', $student->academic_session_id)
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->when($student->department_id, fn($q) => $q->where('department_id', $student->department_id))
                ->get();

            foreach ($subjectAssigns as $assign) {
                $subject = $assign->subject;
                if ($fourthSubjectId && $subject->id == $fourthSubjectId) continue;

                $finalMarkConfiguration = $allFinalMarkConfigs->get($subject->id);
                if (!$finalMarkConfiguration) continue;

                $excludeFromGPA = $finalMarkConfiguration->exclude_from_gpa;
                $failedAnyDistribution = false;
                $totalSubjectMark = 0; // Raw total of non-CT marks
                $finalClassTestMark = 0;

                $marksForSubject = $allValidMarksForStudent->where('subject_id', $subject->id);
                $subjectDistributionConfigs = $allSubjectDistConfigs->where('subject_id', $subject->id);

                foreach ($marksForSubject as $mark) {
                    $distConfig = $subjectDistributionConfigs->where('mark_distribution_id', $mark->mark_distribution_id)->first();
                    if (!$distConfig) continue;
                    if (!$mark->is_absent && $mark->marks_obtained < $distConfig->pass_mark) {
                        $failedAnyDistribution = true;
                    }
                    if ($mark->mark_distribution_id == $ctMarkDistributionId) {
                        $finalClassTestMark = $mark->marks_obtained;
                    } else {
                        $totalSubjectMark += $mark->marks_obtained;
                    }
                }

                $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);
                $totalCalculatedMark = $calculatedMark + $finalClassTestMark;
                $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
                    ->where('end_marks', '>=', $totalCalculatedMark)
                    ->where('grading_scale', $finalMarkConfiguration->grading_scale)
                    ->first();
                $gradePoint = $grade ? $grade->grade_point : 0;

                if ($grade && $grade->grade_point == 0) $failedAnyDistribution = true;
                if ($failedAnyDistribution || $gradePoint == 0) $failCount++;

                if (!$excludeFromGPA) {
                    $totalObtainedMarks += $totalCalculatedMark;
                    $totalGradePoints += $gradePoint;
                    $gpaSubjectCount++;
                }
            }

            // --- FULLY IMPLEMENTED 4th SUBJECT LOGIC ---
            if ($fourthSubjectId) {
                $finalMarkConfiguration = $allFinalMarkConfigs->get($fourthSubjectId);
                if ($finalMarkConfiguration) {
                    $failedAnyDistribution = false;
                    $totalSubjectMark = 0;
                    $finalClassTestMark = 0;

                    $marksForSubject = $allValidMarksForStudent->where('subject_id', $fourthSubjectId);
                    $subjectDistributionConfigs = $allSubjectDistConfigs->where('subject_id', $fourthSubjectId);

                    foreach ($marksForSubject as $mark) {
                        $distConfig = $subjectDistributionConfigs->where('mark_distribution_id', $mark->mark_distribution_id)->first();
                        if (!$distConfig) continue;
                        if (!$mark->is_absent && $mark->marks_obtained < $distConfig->pass_mark) {
                            $failedAnyDistribution = true;
                        }
                        if ($mark->mark_distribution_id == $ctMarkDistributionId) {
                            $finalClassTestMark = $mark->marks_obtained;
                        } else {
                            $totalSubjectMark += $mark->marks_obtained;
                        }
                    }

                    $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);
                    $totalCalculatedMark = $calculatedMark + $finalClassTestMark;
                    $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
                        ->where('end_marks', '>=', $totalCalculatedMark)
                        ->where('grading_scale', $finalMarkConfiguration->grading_scale)
                        ->first();
                    $gradePoint = $grade ? $grade->grade_point : 0;

                    $totalObtainedMarks += $totalCalculatedMark;
                    if ($failedAnyDistribution) {
                        $failCount++;
                    }
                    if (!$failedAnyDistribution && $gradePoint >= 2.0) {
                        $totalGradePoints += ($gradePoint - 2.0);
                    }
                }
            }

            // Final GPA & Grade Calculation
            $finalgpa = $gpaSubjectCount > 0 ? (float) number_format($totalGradePoints / $gpaSubjectCount, 2, '.', '') : 0.00;
            $finalGrade = Grade::where('grade_point', '<=', $finalgpa)->orderBy('grade_point', 'desc')->first();
            $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';
            $finalResult = $failCount > 0 ? 'Fail' : 'Pass';
            if ($finalResult === 'Fail') {
                $letterGrade = 'F';
                $finalgpa = 0.00;
            }

            $results[] = ['student' => $student, 'total_marks' => $totalObtainedMarks, 'gpa' => $finalgpa, 'letter_grade' => $letterGrade, 'fail_count' => $failCount, 'final_result' => $finalResult,];
        }

        // Sort and assign positions
        usort($results, function ($a, $b) {
            if ($a['gpa'] != $b['gpa']) return $b['gpa'] <=> $a['gpa'];
            if ($a['total_marks'] != $b['total_marks']) return $b['total_marks'] <=> $a['total_marks'];
            return $a['fail_count'] <=> $b['fail_count'];
        });
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

    public static function getResultSummary(array $results): array
    {
        $summary = [
            'total_students' => count($results),
            'passed_students' => 0,
            'failed_students' => 0,
            'grades' => ['A+' => 0, 'A' => 0, 'A-' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0],
        ];
        foreach ($results as $result) {
            if ($result['final_result'] === 'Pass') {
                $summary['passed_students']++;
            } else {
                $summary['failed_students']++;
            }
            $grade = $result['letter_grade'];
            if (isset($summary['grades'][$grade])) {
                $summary['grades'][$grade]++;
            }
        }
        return $summary;
    }
}
