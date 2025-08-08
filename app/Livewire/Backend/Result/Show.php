<?php

namespace App\Livewire\Backend\Result;

use Livewire\Component;
use App\Models\{
    Student,
    Exam,
    SubjectAssign,
    SubjectMarkDistribution,
    StudentMark,
    Grade,
    FinalMarkConfiguration
};
use Illuminate\Support\Facades\Log;

class Show extends Component
{
    public $studentId, $examId, $classId, $sectionId, $sessionId;
    public $student, $exam;
    public $subjects = [];
    public $summary = [];

    public function mount($studentId, $examId, $classId, $sectionId, $sessionId)
    {
        $this->studentId = $studentId;
        $this->examId = $examId;
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->sessionId = $sessionId;

        $this->loadResultData();
    }

    protected function loadResultData()
    {
        $this->student = Student::with(['user', 'schoolClass', 'classSection'])->findOrFail($this->studentId);
        $this->exam = Exam::findOrFail($this->examId);

        $subjectAssign = SubjectAssign::where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->with('items.subject')
            ->first();

        if (!$subjectAssign) {
            $this->subjects = [];
            return;
        }

        $this->subjects = [];
        $totalMarksObtained = 0;
        $totalGradePoints = 0;
        $subjectCount = 0;
        $failFlag = false;

        $studentsInClassSection = Student::where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->get();

        foreach ($subjectAssign->items as $item) {
            $subject = $item->subject;

            $finalConfig = FinalMarkConfiguration::where('school_class_id', $this->classId)
                ->where('subject_id', $subject->id)
                ->first();

            $distributions = SubjectMarkDistribution::with('markDistribution')
                ->where('school_class_id', $this->classId)
                ->where('class_section_id', $this->sectionId)
                ->where('subject_id', $subject->id)
                ->get();

            $fullMark = $finalConfig ? $finalConfig->other_parts_total : $distributions->sum('mark');

            // Track sum of marks for calculations
            $ctMarkSum = 0;
            $annualMarkSum = 0;

            // For display: array of per-distribution marks or "Absent"
            $ctDisplayArr = [];
            $annualDisplayArr = [];

            $isFailInAnyDistribution = false;

            foreach ($distributions as $dist) {
                $mark = StudentMark::where([
                    'student_id' => $this->studentId,
                    'exam_id' => $this->examId,
                    'school_class_id' => $this->classId,
                    'class_section_id' => $this->sectionId,
                    'subject_id' => $subject->id,
                    'mark_distribution_id' => $dist->mark_distribution_id,
                ])->first();

                $isAbsent = $mark ? (bool)$mark->is_absent : true;
                $markValue = (!$isAbsent && $mark) ? ($mark->marks_obtained ?? 0) : 0;

                // Log::info("Subject {$subject->name} Dist {$dist->markDistribution->name} student absent? " . ($isAbsent ? 'Yes' : 'No'));

                $isCT = str($dist->markDistribution->name)->contains(['ct', 'class test'], true);

                if ($isCT) {
                    $ctMarkSum += $markValue;
                    $ctDisplayArr[] = $isAbsent ? 'Absent' : $markValue;
                } else {
                    $annualMarkSum += $markValue;
                    $annualDisplayArr[] = $isAbsent ? 'Absent' : $markValue;
                }

                if (!$isAbsent && $markValue < $dist->pass_mark) {
                    $isFailInAnyDistribution = true;
                }
            }

            // Determine if fully absent in CT or Annual parts
            $ctIsFullyAbsent = count($ctDisplayArr) > 0 && count(array_filter($ctDisplayArr, fn($v) => $v === 'Absent')) === count($ctDisplayArr);
            $annualIsFullyAbsent = count($annualDisplayArr) > 0 && count(array_filter($annualDisplayArr, fn($v) => $v === 'Absent')) === count($annualDisplayArr);

            // For display, show 'Absent' if all distributions absent in that category, else sum
            $displayCT = $ctIsFullyAbsent ? 'Absent' : $ctMarkSum;
            $displayAnnual = $annualIsFullyAbsent ? 'Absent' : $annualMarkSum;

            $annualMarkWeighted = $finalConfig
                ? $annualMarkSum * ($finalConfig->final_result_weight_percentage / 100)
                : $annualMarkSum;

            $total = $ctMarkSum + $annualMarkWeighted;

            $gradeData = Grade::where('start_marks', '<=', $total)
                ->where('end_marks', '>=', $total)
                ->first();

            $gradeName = $gradeData->grade_name ?? 'N/A';
            $gradePoint = $gradeData->grade_point ?? 0;
            $remarks = $gradeData->remarks ?? '';

            if ($isFailInAnyDistribution) {
                $failFlag = true;
            }

            // If fully absent in both CT and Annual, consider subject fully absent
            $isFullyAbsent = $ctIsFullyAbsent && $annualIsFullyAbsent;
            if ($isFullyAbsent) {
                $failFlag = true;
                $gradeName = 'F';
                $gradePoint = 0.00;
                $remarks = 'Absent';
            }

            // Calculate highest marks among students in this subject
            $studentsTotalMarks = [];
            foreach ($studentsInClassSection as $stud) {
                $studCT = 0;
                $studAnnual = 0;

                foreach ($distributions as $dist) {
                    $studMark = StudentMark::where([
                        'student_id' => $stud->id,
                        'exam_id' => $this->examId,
                        'school_class_id' => $this->classId,
                        'class_section_id' => $this->sectionId,
                        'subject_id' => $subject->id,
                        'mark_distribution_id' => $dist->mark_distribution_id,
                    ])->first();

                    $isAbsent = $studMark ? (bool)$studMark->is_absent : true;
                    $markValue = (!$isAbsent && $studMark) ? ($studMark->marks_obtained ?? 0) : 0;

                    $isCT = str($dist->markDistribution->name)->contains(['ct', 'class test'], true);

                    if ($isCT) {
                        $studCT += $markValue;
                    } else {
                        $studAnnual += $markValue;
                    }
                }

                $studAnnualWeighted = $finalConfig
                    ? $studAnnual * ($finalConfig->final_result_weight_percentage / 100)
                    : $studAnnual;

                $studentsTotalMarks[] = $studCT + $studAnnualWeighted;
            }

            $highest = $studentsTotalMarks ? max($studentsTotalMarks) : 0;

            $highestGradeData = Grade::where('start_marks', '<=', $highest)
                ->where('end_marks', '>=', $highest)
                ->first();

            $highestGPA = $highestGradeData->grade_point ?? 0;
            $highestGradeName = $this->getLetterGrade($highestGPA);
            $highestRemarks = $highestGradeData->remarks ?? '';

            $this->subjects[] = [
                'name' => $subject->name,
                'full_mark' => $fullMark,
                'ct' => $displayCT,
                'annual' => $displayAnnual,
                'cal_ct' => $ctMarkSum,
                'cal_annual' => round($annualMarkWeighted, 2),
                'total' => round($total, 2),
                'gpa' => number_format($gradePoint, 2),
                'grade' => $gradeName,
                'result' => $isFailInAnyDistribution ? 'Fail' : 'Pass',
                'remarks' => $remarks,
                'highest' => round($highest, 2),
                'highest_gpa' => $highestGPA,
                'highest_grade' => $highestGradeName,
                'highest_remarks' => $highestRemarks,
            ];

            $totalMarksObtained += $isFullyAbsent ? 0 : $total;

            $excludedFromGPA = in_array((int)$this->classId, [3, 4, 5]) && strtolower($subject->name) === 'art';

            if (!$excludedFromGPA && !$isFullyAbsent) {
                $totalGradePoints += $isFailInAnyDistribution ? 0 : $gradePoint;
                $subjectCount++;
            }
        }

        $averageGPA = $failFlag ? 0.00 : ($subjectCount > 0 ? round($totalGradePoints / $subjectCount, 2) : 0.00);

        $position = $this->calculateClassPosition($subjectAssign, $studentsInClassSection);

        $this->summary = [
            'total' => round($totalMarksObtained, 2),
            'grade' => $failFlag ? 'F' : $this->getLetterGrade($averageGPA),
            'gpa' => number_format($averageGPA, 2),
            'result' => $failFlag ? 'Fail' : 'Pass',
            'position' => $position,
            'comment' => '',
        ];
    }

    protected function calculateClassPosition($subjectAssign, $students)
    {
        $studentsTotals = [];

        foreach ($students as $stud) {
            $studTotal = 0;
            $studFail = false;

            foreach ($subjectAssign->items as $item) {
                $subject = $item->subject;

                $finalConfig = FinalMarkConfiguration::where('school_class_id', $this->classId)
                    ->where('subject_id', $subject->id)
                    ->first();

                $distributions = SubjectMarkDistribution::where('school_class_id', $this->classId)
                    ->where('class_section_id', $this->sectionId)
                    ->where('subject_id', $subject->id)
                    ->get();

                $studCT = 0;
                $studAnnual = 0;
                $isFullyAbsent = true;
                $studFailInSubject = false;

                foreach ($distributions as $dist) {
                    $studMark = StudentMark::where([
                        'student_id' => $stud->id,
                        'exam_id' => $this->examId,
                        'school_class_id' => $this->classId,
                        'class_section_id' => $this->sectionId,
                        'subject_id' => $subject->id,
                        'mark_distribution_id' => $dist->mark_distribution_id,
                    ])->first();

                    $isAbsent = $studMark ? (bool)$studMark->is_absent : true;
                    $markValue = (!$isAbsent && $studMark) ? ($studMark->marks_obtained ?? 0) : 0;

                    if (!$isAbsent) {
                        $isFullyAbsent = false;
                    }

                    $isCT = str($dist->markDistribution->name)->contains(['ct', 'class test'], true);

                    if ($isCT) {
                        $studCT += $markValue;
                    } else {
                        $studAnnual += $markValue;
                    }

                    if ($markValue < $dist->pass_mark) {
                        $studFailInSubject = true;
                    }
                }

                $studAnnualWeighted = $finalConfig
                    ? $studAnnual * ($finalConfig->final_result_weight_percentage / 100)
                    : $studAnnual;

                $studTotal += $isFullyAbsent ? 0 : $studCT + $studAnnualWeighted;

                if ($studFailInSubject || $isFullyAbsent) {
                    $studFail = true;
                }
            }

            $studentsTotals[] = [
                'student_id' => $stud->id,
                'total' => $studTotal,
                'fail' => $studFail,
            ];
        }

        usort($studentsTotals, fn($a, $b) => $b['total'] <=> $a['total']);

        $position = 0;
        $lastTotal = null;
        $rank = 0;

        foreach ($studentsTotals as $index => $data) {
            if ($lastTotal === null || $data['total'] != $lastTotal) {
                $rank = $index + 1;
            }

            if ($data['student_id'] == $this->studentId) {
                $position = $rank;
                break;
            }

            $lastTotal = $data['total'];
        }

        return $position;
    }

    protected function getLetterGrade($gpa)
    {
        return match (true) {
            $gpa >= 5.0 => 'A+',
            $gpa >= 4.0 => 'A',
            $gpa >= 3.5 => 'A-',
            $gpa >= 3.0 => 'B',
            $gpa >= 2.0 => 'C',
            $gpa >= 1.0 => 'D',
            default => 'F',
        };
    }

    public function render()
    {
        return view('livewire.backend.result.show', [
            'student' => [
                'name' => $this->student->user->name,
                'id' => $this->student->id,
                'class' => $this->student->schoolClass->name ?? '',
                'section' => $this->student->classSection->name ?? '',
                'roll' => $this->student->roll_number ?? '',
            ],
            'exam' => $this->exam,
            'subjects' => $this->subjects,
            'summary' => $this->summary,
        ]);
    }
}
