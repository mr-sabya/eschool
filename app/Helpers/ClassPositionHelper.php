<?php

namespace App\Helpers;

use App\Models\ClassSubjectAssign;
use App\Models\FinalMarkConfiguration;
use App\Models\Grade;
use App\Models\MarkDistribution;
use App\Models\StudentMark;
use App\Models\Subject;
use App\Models\SubjectMarkDistribution;
use Illuminate\Support\Collection;

class ClassPositionHelper
{

    // =======================================================================================
    // NEW FUNCTION FOR TABULATION SHEET ONLY
    // This function is highly optimized and returns a detailed breakdown of marks.
    // =======================================================================================

    public static function getTabulationSheetResults($students, $examId, Collection $markDistributions)
    {
        if ($students->isEmpty()) {
            return [];
        }

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
                $subjectResult = self::calculateDetailedSubjectResult($marksForSubject, $config, $subjectSpecificDistributions, $grades, $markDistributions);
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

    private static function calculateDetailedSubjectResult(Collection $studentMarks, FinalMarkConfiguration $config, Collection $subjectDistributions, Collection $allGrades, Collection $allPossibleDistributions): array
    {
        $failedAnyDistribution = false;
        $obtainedMarksByDistribution = [];
        $marksByDistribution = $studentMarks->keyBy('mark_distribution_id');

        // --- NEW LOGIC START ---
        $classTestMark = 0;
        $otherMarksTotal = 0; // Raw sum of non-CT marks
        $ctMarkDistributionId = MarkDistribution::where('name', 'Class Test')->first()->id ?? null;
        // --- NEW LOGIC END ---

        foreach ($allPossibleDistributions as $dist) {
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

                // --- NEW LOGIC: Separate CT from other marks ---
                if ($dist->id == $ctMarkDistributionId) {
                    $classTestMark = $marksObtained;
                } else {
                    $otherMarksTotal += $marksObtained;
                }
                // --- END NEW LOGIC ---

            } else {
                $obtainedMarksByDistribution[$dist->id] = 0;
            }
        }

        // --- THIS IS THE CORRECTED CALCULATION ---
        // Apply weighting only to "other marks"
        $weightedOtherMarks = round(($otherMarksTotal * $config->final_result_weight_percentage) / 100);
        // The final total is the weighted part + the raw class test mark
        $totalCalculatedMark = $weightedOtherMarks + $classTestMark;
        // --- END CORRECTION ---

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
                ->when($student->department_id, function ($query) use ($student) {
                    $query->where('department_id', $student->department_id);
                })
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

                    if ($classTestMark && !$classTestMark->is_absent && $classTestMark->marks_obtained < $ctSubjectMarkDistribution->pass_mark) {
                        $failedAnyDistribution = true;
                    }
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

                    $marksObtained = $studentSubjectMark ? $studentSubjectMark->marks_obtained : 0;

                    if ($studentSubjectMark && $studentSubjectMark->marks_obtained < $subjectMarkDistribution->pass_mark) {
                        $failedAnyDistribution = true;
                    }

                    $totalSubjectMark += $marksObtained;
                }

                $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);

                $totalCalculatedMark = $calculatedMark + $finalClassTestMark;

                $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
                    ->where('end_marks', '>=', $totalCalculatedMark)
                    ->where('grading_scale', $finalMarkConfiguration?->grading_scale)
                    ->first();

                $gradePoint = $grade ? $grade->grade_point : 0;

                if ($grade && $grade->grade_point == 0) {
                    $failedAnyDistribution = true;
                }

                if ($failedAnyDistribution || $gradePoint == 0) {
                    $failCount++;
                }

                if (!$excludeFromGPA) {
                    $totalObtainedMarks += $totalCalculatedMark;
                    $totalGradePoints += $gradePoint;
                    $gpaSubjectCount++;
                }
            }

            // ✅ Handle 4th subject
            // ✅ Handle 4th subject same as main subjects
            if ($fourthSubjectId) {
                $subject = Subject::find($fourthSubjectId);
                $finalMarkConfiguration = FinalMarkConfiguration::where('school_class_id', $student->school_class_id)
                    ->where('subject_id', $fourthSubjectId)
                    ->first();

                if ($finalMarkConfiguration) {
                    $failedAnyDistribution = false; // reset per subject
                    $totalSubjectMark = 0;

                    // ✅ Class test marks
                    $ctMarkDistribution = MarkDistribution::where('name', 'Class Test')->first();
                    $ctSubjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $fourthSubjectId)
                        ->where('school_class_id', $student->school_class_id)
                        ->where('class_section_id', $student->class_section_id)
                        ->where('mark_distribution_id', $ctMarkDistribution?->id)
                        ->first();

                    $classTestMark = null;
                    if ($ctSubjectMarkDistribution) {
                        $classTestMark = StudentMark::where('student_id', $student->id)
                            ->where('subject_id', $fourthSubjectId)
                            ->where('school_class_id', $student->school_class_id)
                            ->where('exam_id', $examId)
                            ->where('mark_distribution_id', $ctMarkDistribution->id)
                            ->first();

                        if ($classTestMark && !$classTestMark->is_absent && $classTestMark->marks_obtained < $ctSubjectMarkDistribution->pass_mark) {
                            $failedAnyDistribution = true;
                        }
                    }

                    $finalClassTestMark = $classTestMark ? $classTestMark->marks_obtained : 0;

                    // ✅ Other marks
                    $otherMarkDistributions = MarkDistribution::where('name', '!=', 'Class Test')->get();

                    foreach ($otherMarkDistributions as $distribution) {
                        $subjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $fourthSubjectId)
                            ->where('school_class_id', $student->school_class_id)
                            ->where('class_section_id', $student->class_section_id)
                            ->where('mark_distribution_id', $distribution->id)
                            ->first();

                        $studentSubjectMark = null;
                        if ($subjectMarkDistribution) {
                            $studentSubjectMark = StudentMark::where('student_id', $student->id)
                                ->where('subject_id', $fourthSubjectId)
                                ->where('school_class_id', $student->school_class_id)
                                ->where('exam_id', $examId)
                                ->where('mark_distribution_id', $distribution->id)
                                ->first();
                        }

                        $marksObtained = $studentSubjectMark ? $studentSubjectMark->marks_obtained : 0;

                        if ($studentSubjectMark && !$studentSubjectMark->is_absent && $marksObtained < $subjectMarkDistribution->pass_mark) {
                            $failedAnyDistribution = true;
                        }

                        $totalSubjectMark += $marksObtained;
                    }

                    // ✅ Apply final mark weight
                    $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);
                    $totalCalculatedMark = $calculatedMark + $finalClassTestMark;

                    // ✅ Grade and GPA
                    $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
                        ->where('end_marks', '>=', $totalCalculatedMark)
                        ->where('grading_scale', $finalMarkConfiguration->grading_scale)
                        ->first();

                    $gradePoint = $grade ? $grade->grade_point : 0;

                    // Add to totals
                    $totalObtainedMarks += $totalCalculatedMark;

                    // GPA adjustment: subtract 2.0 if >= 2.0
                    if (!$failedAnyDistribution && $gradePoint >= 2.0) {
                        $totalGradePoints += ($gradePoint - 2.0);
                    }

                    // Fail count if failed in 4th subject
                    if ($failedAnyDistribution) {
                        $failCount++;
                    }
                }
            }




            // ✅ Final GPA & Grade
            $finalgpa = $gpaSubjectCount > 0 ? (float) number_format($totalGradePoints / $gpaSubjectCount, 2, '.', '') : 0.00;

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


    public static function getResultSummary(array $results): array
    {
        // This function processes the output of the OLD getClassResults
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
