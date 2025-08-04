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

        // Get all students in class & section for position & highest marks calculation
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

            $ctMark = 0;
            $annualMark = 0;
            $isFailInAnyDistribution = false;

            foreach ($distributions as $dist) {
                $mark = StudentMark::where([
                    'student_id' => $this->studentId,
                    'exam_id' => $this->examId,
                    'school_class_id' => $this->classId,
                    'class_section_id' => $this->sectionId,
                    'subject_id' => $subject->id,
                    'mark_distribution_id' => $dist->mark_distribution_id,
                ])->value('marks_obtained') ?? 0;

                $isCT = str($dist->markDistribution->name)->contains(['ct', 'class test'], true);
                if ($isCT) {
                    $ctMark += $mark;
                } else {
                    $annualMark += $mark;
                }

                if ($mark < $dist->pass_mark) {
                    $isFailInAnyDistribution = true;
                }
            }

            $annualMarkWeighted = $finalConfig
                ? $annualMark * ($finalConfig->final_result_weight_percentage / 100)
                : $annualMark;

            $total = $ctMark + $annualMarkWeighted;

            $gradeData = Grade::where('start_marks', '<=', $total)
                ->where('end_marks', '>=', $total)
                ->first();

            $gradeName = $gradeData->grade_name ?? 'N/A';
            $gradePoint = $gradeData->grade_point ?? 0;
            $remarks = $gradeData->remarks ?? '';

            if ($isFailInAnyDistribution) {
                $failFlag = true;
            }

            // Calculate highest mark among all students for this subject
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
                    ])->value('marks_obtained') ?? 0;

                    $isCT = str($dist->markDistribution->name)->contains(['ct', 'class test'], true);
                    if ($isCT) {
                        $studCT += $studMark;
                    } else {
                        $studAnnual += $studMark;
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
                'ct' => $ctMark,
                'annual' => $annualMark,
                'cal_ct' => $ctMark,
                'cal_annual' => round($annualMarkWeighted, 2),
                'total' => round($total, 2),
                'gpa' => $isFailInAnyDistribution ? number_format(0, 2) : number_format($gradePoint, 2),
                'grade' => $isFailInAnyDistribution ? 'F' : $gradeName,
                'result' => $isFailInAnyDistribution ? 'Fail' : 'Pass',
                'remarks' => $remarks,
                'highest' => round($highest, 2),
                'highest_gpa' => $highestGPA,
                'highest_grade' => $highestGradeName,
                'highest_remarks' => $highestRemarks,
            ];

            $totalMarksObtained += $total;

            $excludedFromGPA = in_array((int) $this->classId, [3, 4, 5]) && strtolower($subject->name) === 'art';

            if (!$excludedFromGPA) {
                $totalGradePoints += $isFailInAnyDistribution ? 0 : $gradePoint;
                $subjectCount++;
            }
        }

        $averageGPA = $failFlag ? 0.00 : ($subjectCount > 0 ? round($totalGradePoints / $subjectCount, 2) : 0.00);

        // Calculate position by total marks for all students in this class and section
        $studentsTotals = [];
        foreach ($studentsInClassSection as $stud) {
            $studTotal = 0;
            $studFail = false;

            // Calculate student's total marks and check if fail in any subject
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
                $studFailInSubject = false;

                foreach ($distributions as $dist) {
                    $studMark = StudentMark::where([
                        'student_id' => $stud->id,
                        'exam_id' => $this->examId,
                        'school_class_id' => $this->classId,
                        'class_section_id' => $this->sectionId,
                        'subject_id' => $subject->id,
                        'mark_distribution_id' => $dist->mark_distribution_id,
                    ])->value('marks_obtained') ?? 0;

                    $isCT = str($dist->markDistribution->name)->contains(['ct', 'class test'], true);
                    if ($isCT) {
                        $studCT += $studMark;
                    } else {
                        $studAnnual += $studMark;
                    }

                    if ($studMark < $dist->pass_mark) {
                        $studFailInSubject = true;
                    }
                }

                $studAnnualWeighted = $finalConfig
                    ? $studAnnual * ($finalConfig->final_result_weight_percentage / 100)
                    : $studAnnual;

                $studTotal += $studCT + $studAnnualWeighted;

                if ($studFailInSubject) {
                    $studFail = true;
                }
            }

            $studentsTotals[] = [
                'student_id' => $stud->id,
                'total' => $studTotal,
                'fail' => $studFail,
            ];
        }

        // Sort descending by total marks
        usort($studentsTotals, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Find position of current student
        $position = 0;
        $lastTotal = null;
        $rank = 0;
        $sameRankCount = 1;

        foreach ($studentsTotals as $index => $data) {
            if ($lastTotal === null || $data['total'] != $lastTotal) {
                $rank = $index + 1;
                $sameRankCount = 1;
            } else {
                $sameRankCount++;
            }

            if ($data['student_id'] == $this->studentId) {
                $position = $rank;
                break;
            }

            $lastTotal = $data['total'];
        }

        $this->summary = [
            'total' => round($totalMarksObtained, 2),
            'grade' => $failFlag ? 'F' : $this->getLetterGrade($averageGPA),
            'gpa' => $failFlag ? number_format(0, 2) : number_format($averageGPA, 2),
            'result' => $failFlag ? 'Fail' : 'Pass',
            'position' => $position,
            'comment' => '',
        ];
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
