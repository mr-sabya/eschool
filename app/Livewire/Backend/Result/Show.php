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
        // Load student and exam data
        $this->student = Student::with(['user', 'schoolClass', 'classSection'])->findOrFail($this->studentId);
        $this->exam = Exam::findOrFail($this->examId);

        // Load subject assignments
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

        // Get all student IDs in this class and section for highest mark calc
        $studentIds = Student::where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->pluck('id');

        foreach ($subjectAssign->items as $item) {
            $subject = $item->subject;

            // Load FinalMarkConfiguration for weighting
            $finalConfig = FinalMarkConfiguration::where('school_class_id', $this->classId)
                ->where('subject_id', $subject->id)
                ->first();

            // Load all mark distributions for this subject
            $distributions = SubjectMarkDistribution::with('markDistribution')
                ->where('school_class_id', $this->classId)
                ->where('class_section_id', $this->sectionId)
                ->where('subject_id', $subject->id)
                ->get();

            // Full mark: use other_parts_total if config exists, else sum of distribution marks
            $fullMark = $finalConfig ? $finalConfig->other_parts_total : $distributions->sum('mark');

            // Pass mark minimum
            $passMark = $distributions->min('pass_mark');

            // Calculate student's own marks (CT and Annual)
            $ctMark = 0;
            $annualMark = 0;

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
            }

            // Apply weighting on annual mark if config exists
            $annualMarkWeighted = $finalConfig
                ? $annualMark * ($finalConfig->final_result_weight_percentage / 100)
                : $annualMark;

            $total = $ctMark + $annualMarkWeighted;

            // Get grade info for student total
            $gradeData = Grade::where('start_marks', '<=', $total)
                ->where('end_marks', '>=', $total)
                ->first();

            $gradeName = $gradeData->grade_name ?? 'N/A';
            $gradePoint = $gradeData->grade_point ?? 0;
            $remarks = $gradeData->remarks ?? '';

            if ($total < $passMark) {
                $failFlag = true;
            }

            // Calculate highest mark among all students for this subject
            $studentsTotalMarks = [];

            foreach ($studentIds as $studId) {
                $studCT = 0;
                $studAnnual = 0;

                foreach ($distributions as $dist) {
                    $studMark = StudentMark::where([
                        'student_id' => $studId,
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

            // Get grade data for highest mark
            $highestGradeData = Grade::where('start_marks', '<=', $highest)
                ->where('end_marks', '>=', $highest)
                ->first();

            $highestGPA = $highestGradeData->grade_point ?? 0;
            $highestGradeName = $this->getLetterGrade($highestGPA);
            $highestRemarks = $highestGradeData->remarks ?? '';

            // Add to subjects array for blade
            $this->subjects[] = [
                'name' => $subject->name,
                'full_mark' => $fullMark,
                'ct' => $ctMark,
                'annual' => $annualMark,
                'cal_ct' => $ctMark,
                'cal_annual' => round($annualMarkWeighted, 2),
                'total' => round($total, 2),
                'gpa' => $gradePoint,
                'grade' => $gradeName,
                'result' => $total >= $passMark ? 'Pass' : 'Fail',
                'remarks' => $remarks,
                'highest' => round($highest, 2),
                'highest_gpa' => $highestGPA,
                'highest_grade' => $highestGradeName,
                'highest_remarks' => $highestRemarks,
            ];

            $totalMarksObtained += $total;
            $totalGradePoints += $gradePoint;
            $subjectCount++;
        }

        $averageGPA = $subjectCount > 0 ? round($totalGradePoints / $subjectCount, 2) : 0;

        $this->summary = [
            'total' => round($totalMarksObtained, 2),
            'grade' => $failFlag ? 'F' : $this->getLetterGrade($averageGPA),
            'gpa' => $averageGPA,
            'result' => $failFlag ? 'Fail' : 'Pass',
            'position' => 0, // You may want to calculate actual position separately
            'comment' => '', // You can fill comments dynamically if needed
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
