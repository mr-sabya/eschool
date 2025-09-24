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

        $finalMarkConfigs = FinalMarkConfiguration::where('school_class_id', $classId)
            ->whereIn('subject_id', $subjectIds)
            ->when($firstStudent->department_id, function ($query) use ($firstStudent) {
                $query->where(function ($subQuery) use ($firstStudent) {
                    $subQuery->where('department_id', $firstStudent->department_id)
                        ->orWhereNull('department_id');
                });
            }, function ($query) {
                $query->whereNull('department_id');
            })
            ->get()
            ->sortBy('department_id') // Ensures specific configs overwrite general ones
            ->keyBy('subject_id');

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

                // ============================ START: THE FIX ============================
                // This is the reliable way to check if marks exist for a subject.
                // It directly checks if the key exists in the student's grouped marks collection.
                if (!$config || !$studentMarks->has($subjectId)) {
                    continue;
                }
                $marksForSubject = $studentMarks->get($subjectId);
                // ============================ END: THE FIX ============================

                $subjectSpecificDistributions = $subjectMarkDistributions->where('subject_id', $subjectId);
                $subjectResult = self::calculateDetailedSubjectResult($marksForSubject, $config, $subjectSpecificDistributions, $grades, $allowedDistributions, $allowedDistributionIds);
                $subjectResults[] = $subjectResult;

                if ($subjectResult['is_fail']) $failCount++;
                $totalObtainedMarks += $subjectResult['total_calculated_marks'];

                if ($config->exclude_from_gpa) continue;

                $isFourthSubject = ($subjectId === $fourthSubjectId);
                if ($isFourthSubject) {
                    $currentGPA = $gpaSubjectCount > 0 ? ($totalGradePoints / $gpaSubjectCount) : 0;
                    if ($currentGPA < 5.0) {
                        if ($subjectResult['grade_point'] >= 2.0 && !$subjectResult['is_fail']) {
                            $totalGradePoints += ($subjectResult['grade_point'] - 2.0);
                        }
                    }
                } else {
                    $totalGradePoints += $subjectResult['grade_point'];
                    $gpaSubjectCount++;
                }
            }

            // 3. FINAL GPA & GRADE CALCULATION
            $isFail = $failCount > 0;
            $finalgpa = $gpaSubjectCount > 0 ? (float) number_format($totalGradePoints / $gpaSubjectCount, 2, '.', '') : 0.00;
            if ($finalgpa > 5.00) $finalgpa = 5.00;
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
        unset($res);

        // 5. FINAL SORT BY ROLL NUMBER
        usort($results, function ($a, $b) {
            return $a['student']->roll_number <=> $b['student']->roll_number;
        });

        return $results;
    }

    private static function calculateDetailedSubjectResult(Collection $studentMarks, FinalMarkConfiguration $config, Collection $subjectDistributions, Collection $allGrades, Collection $allowedDistributions, Collection $allowedDistributionIds): array
    {
        // This function is unchanged
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
        // This function is unchanged structurally, but the filter inside is fixed.
        $exam = Exam::with('markDistributionTypes')->find($examId);
        if (!$exam || $students->isEmpty()) {
            return [];
        }
        $allowedDistributionIds = $exam->markDistributionTypes->pluck('id');

        $firstStudent = $students->first();
        $classId = $firstStudent->school_class_id;
        $sectionId = $firstStudent->class_section_id;
        $sessionId = $firstStudent->academic_session_id;
        $allPossibleSubjectIds = ClassSubjectAssign::where('academic_session_id', $sessionId)
            ->where('school_class_id', $classId)
            ->where('class_section_id', $sectionId)
            ->pluck('subject_id')->unique();
        $allFinalMarkConfigs = FinalMarkConfiguration::where('school_class_id', $classId)
            ->whereIn('subject_id', $allPossibleSubjectIds)
            ->when($firstStudent->department_id, function ($query) use ($firstStudent) {
                $query->where(function ($subQuery) use ($firstStudent) {
                    $subQuery->where('department_id', $firstStudent->department_id)
                        ->orWhereNull('department_id');
                });
            }, function ($query) {
                $query->whereNull('department_id');
            })
            ->get()
            ->sortBy('department_id')
            ->keyBy('subject_id');
        $allSubjectDistConfigs = SubjectMarkDistribution::where('school_class_id', $classId)->get();
        $ctMarkDistributionId = MarkDistribution::where('name', 'Class Test')->first()->id ?? null;

        $results = [];
        foreach ($students as $student) {
            $totalObtainedMarks = 0;
            $totalGradePoints = 0;
            $gpaSubjectCount = 0;
            $failCount = 0;

            $allValidMarksForStudent = StudentMark::where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->whereIn('mark_distribution_id', $allowedDistributionIds)
                ->get();

            // Group marks by subject ID for a reliable key check.
            $marksBySubject = $allValidMarksForStudent->groupBy('subject_id');

            $fourthSubjectId = $allValidMarksForStudent->where('is_fourth_subject', 1)->first()->subject_id ?? null;

            $subjectAssigns = ClassSubjectAssign::with('subject')
                ->where('academic_session_id', $student->academic_session_id)
                ->where('school_class_id', $student->school_class_id)
                ->where('class_section_id', $student->class_section_id)
                ->when($student->department_id, fn($q) => $q->where('department_id', $student->department_id))
                ->get();

            // Process main subjects
            foreach ($subjectAssigns as $assign) {
                $subject = $assign->subject;
                if ($fourthSubjectId && $subject->id == $fourthSubjectId) continue;

                // ============================ START: THE FIX ============================
                // Use the reliable key check on the grouped collection.
                if (!$marksBySubject->has($subject->id)) {
                    continue;
                }
                $marksForSubject = $marksBySubject->get($subject->id);
                // ============================ END: THE FIX ============================

                $finalMarkConfiguration = $allFinalMarkConfigs->get($subject->id);
                if (!$finalMarkConfiguration) continue;

                $failedAnyDistribution = false;
                $totalSubjectMark = 0;
                $finalClassTestMark = 0;
                $subjectDistributionConfigs = $allSubjectDistConfigs->where('subject_id', $subject->id);

                foreach ($marksForSubject as $mark) {
                    // ... (rest of the loop remains the same)
                    $distConfig = $subjectDistributionConfigs->where('mark_distribution_id', $mark->mark_distribution_id)->first();
                    if (!$distConfig) continue;
                    if (!$mark->is_absent && $mark->marks_obtained < $distConfig->pass_mark) $failedAnyDistribution = true;
                    if ($mark->mark_distribution_id == $ctMarkDistributionId) {
                        $finalClassTestMark = $mark->marks_obtained;
                    } else {
                        $totalSubjectMark += $mark->marks_obtained;
                    }
                }
                // ... (rest of the calculation remains the same)
                $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);
                $totalCalculatedMark = $calculatedMark + $finalClassTestMark;
                $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)->where('end_marks', '>=', $totalCalculatedMark)->where('grading_scale', $finalMarkConfiguration->grading_scale)->first();
                $gradePoint = $grade ? $grade->grade_point : 0;

                if (($grade && $grade->grade_point == 0) || $failedAnyDistribution) {
                    $failCount++;
                    $gradePoint = 0;
                }

                if (!$finalMarkConfiguration->exclude_from_gpa) {
                    $totalObtainedMarks += $totalCalculatedMark;
                    $totalGradePoints += $gradePoint;
                    $gpaSubjectCount++;
                }
            }

            // --- Process 4th SUBJECT LOGIC ---
            if ($fourthSubjectId) {
                // ============================ START: THE FIX ============================
                // Apply the same reliable key check for the 4th subject.
                if ($marksBySubject->has($fourthSubjectId)) {
                    $marksForSubject = $marksBySubject->get($fourthSubjectId);
                    $finalMarkConfiguration = $allFinalMarkConfigs->get($fourthSubjectId);

                    if ($finalMarkConfiguration) {
                        // ... (the rest of the 4th subject logic goes inside this block)
                        $failedAnyDistribution = false;
                        $totalSubjectMark = 0;
                        $finalClassTestMark = 0;
                        $subjectDistributionConfigs = $allSubjectDistConfigs->where('subject_id', $fourthSubjectId);
                        foreach ($marksForSubject as $mark) {
                            $distConfig = $subjectDistributionConfigs->where('mark_distribution_id', $mark->mark_distribution_id)->first();
                            if (!$distConfig) continue;
                            if (!$mark->is_absent && $mark->marks_obtained < $distConfig->pass_mark) $failedAnyDistribution = true;
                            if ($mark->mark_distribution_id == $ctMarkDistributionId) {
                                $finalClassTestMark = $mark->marks_obtained;
                            } else {
                                $totalSubjectMark += $mark->marks_obtained;
                            }
                        }
                        $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);
                        $totalCalculatedMark = $calculatedMark + $finalClassTestMark;
                        $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)->where('end_marks', '>=', $totalCalculatedMark)->where('grading_scale', $finalMarkConfiguration->grading_scale)->first();
                        $gradePoint = $grade ? $grade->grade_point : 0;
                        $totalObtainedMarks += $totalCalculatedMark;

                        if ($failedAnyDistribution || ($grade && $grade->grade_point == 0)) {
                            $failCount++;
                        } else {
                            $currentGPA = $gpaSubjectCount > 0 ? ($totalGradePoints / $gpaSubjectCount) : 0;
                            if ($currentGPA < 5.0 && $gradePoint >= 2.0) {
                                $totalGradePoints += ($gradePoint - 2.0);
                            }
                        }
                    }
                }
                // ============================ END: THE FIX ============================
            }

            // ... (Final GPA calculation and result formatting is unchanged)
            $isFail = $failCount > 0;
            $finalgpa = $gpaSubjectCount > 0 ? (float) number_format($totalGradePoints / $gpaSubjectCount, 2, '.', '') : 0.00;
            if ($finalgpa > 5.00) $finalgpa = 5.00;
            if ($isFail) {
                $letterGrade = 'F';
                $finalgpa = 0.00;
            } else {
                $finalGrade = Grade::where('grade_point', '<=', $finalgpa)->orderBy('grade_point', 'desc')->first();
                $letterGrade = $finalGrade ? $finalGrade->grade_name : 'N/A';
            }
            $results[] = [
                'student' => $student,
                'total_marks' => $totalObtainedMarks,
                'gpa' => $finalgpa,
                'letter_grade' => $letterGrade,
                'fail_count' => $failCount,
                'final_result' => $isFail ? 'Fail' : 'Pass',
            ];
        }

        // ... (Sorting and positioning logic is unchanged)
        usort($results, function ($a, $b) {
            if ($a['final_result'] !== $b['final_result']) return $a['final_result'] === 'Fail' ? 1 : -1;
            if ($a['gpa'] != $b['gpa']) return $b['gpa'] <=> $a['gpa'];
            return $b['total_marks'] <=> $a['total_marks'];
        });
        $position = 0;
        $lastResult = null;
        foreach ($results as $index => &$res) {
            if (is_null($lastResult) || $res['gpa'] != $lastResult['gpa'] || $res['total_marks'] != $lastResult['total_marks']) {
                $position = $index + 1;
            }
            $res['position'] = $position;
            $lastResult = $res;
        }
        unset($res);
        return $results;
    }


    public static function getStudentPosition($studentId, $students, $examId)
    {
        // This function is unchanged
        $results = self::getClassResults($students, $examId);
        foreach ($results as $res) {
            if ($res['student']->id == $studentId) return $res;
        }
        return null;
    }

    public static function getResultSummary(array $results): array
    {
        // This function is unchanged
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
