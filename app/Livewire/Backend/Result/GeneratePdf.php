<?php

namespace App\Livewire\Backend\Result;

use Livewire\Component;
use App\Models\{
    Student,
    Exam,
    SchoolClass,
    ClassSection,
    SubjectAssign,
    SubjectMarkDistribution,
    StudentMark,
    Grade,
    FinalMarkConfiguration
};
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class GeneratePdf extends Component
{
    public $school_class_id;
    public $class_section_id;
    public $exam_id;

    public $classes = [];
    public $sections = [];
    public $exams = [];

    public function mount()
    {
        $this->classes = SchoolClass::all();
        $this->exams = Exam::all();
        $this->sections = [];
    }

    public function loadSections($classId)
    {
        $this->class_section_id = null;
        $this->sections = ClassSection::where('school_class_id', $classId)->get();
    }

    public function downloadPdf()
    {
        $this->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        $students = Student::with(['user', 'schoolClass', 'classSection'])
            ->where('school_class_id', $this->school_class_id)
            ->where('class_section_id', $this->class_section_id)
            ->get();

        $studentIds = $students->pluck('id')->toArray();

        $subjectAssign = SubjectAssign::where('school_class_id', $this->school_class_id)
            ->where('class_section_id', $this->class_section_id)
            ->with('items.subject')
            ->first();

        if (!$subjectAssign) {
            session()->flash('error', 'No subjects assigned for this class and section.');
            return;
        }

        $results = [];

        // First calculate totals and fail flags for all students (for position calculation)
        $studentTotals = [];

        foreach ($students as $student) {
            $totalMarksObtained = 0;
            $totalGradePoints = 0;
            $subjectCount = 0;
            $failFlag = false;

            foreach ($subjectAssign->items as $item) {
                $subject = $item->subject;

                $finalConfig = FinalMarkConfiguration::where('school_class_id', $this->school_class_id)
                    ->where('subject_id', $subject->id)
                    ->first();

                $distributions = SubjectMarkDistribution::where('school_class_id', $this->school_class_id)
                    ->where('class_section_id', $this->class_section_id)
                    ->where('subject_id', $subject->id)
                    ->get();

                $ctMark = 0;
                $annualMark = 0;
                $isFailInAnyDistribution = false;

                $absentCount = 0;
                $totalDistributions = $distributions->count();

                foreach ($distributions as $dist) {
                    $markRecord = StudentMark::where([
                        'student_id' => $student->id,
                        'exam_id' => $this->exam_id,
                        'school_class_id' => $this->school_class_id,
                        'class_section_id' => $this->class_section_id,
                        'subject_id' => $subject->id,
                        'mark_distribution_id' => $dist->mark_distribution_id,
                    ])->first();

                    $isAbsent = $markRecord ? (bool) $markRecord->is_absent : true;
                    $mark = (!$isAbsent && $markRecord) ? ($markRecord->marks_obtained ?? 0) : 0;

                    if ($isAbsent) {
                        $absentCount++;
                    }

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

                // If all distributions are absent, mark as absent for the subject
                $isFullyAbsent = $absentCount === $totalDistributions;

                if ($isFullyAbsent) {
                    $failFlag = true;
                    $isFailInAnyDistribution = true;
                    $total = 0;
                    $gradePoint = 0;
                    $gradeName = 'F';
                } else {
                    $gradeData = Grade::where('start_marks', '<=', $total)
                        ->where('end_marks', '>=', $total)
                        ->first();

                    $gradePoint = $gradeData->grade_point ?? 0;
                    $gradeName = $gradeData->grade_name ?? 'N/A';
                }

                if ($isFailInAnyDistribution) {
                    $failFlag = true;
                }

                // Exclude Art from GPA for class 3,4,5
                $excludedFromGPA = in_array((int) $this->school_class_id, [3, 4, 5]) &&
                    strtolower($subject->name) === 'art';

                if (!$excludedFromGPA) {
                    $totalGradePoints += $isFailInAnyDistribution ? 0 : $gradePoint;
                    $subjectCount++;
                }

                $totalMarksObtained += $total;
            }

            $averageGPA = $failFlag ? 0.00 : ($subjectCount > 0 ? round($totalGradePoints / $subjectCount, 2) : 0.00);

            $studentTotals[] = [
                'student_id' => $student->id,
                'total' => $totalMarksObtained,
                'fail' => $failFlag,
                'gpa' => $averageGPA,
            ];
        }

        // Sort students descending by total marks for position calculation
        usort($studentTotals, fn($a, $b) => $b['total'] <=> $a['total']);

        // Assign positions with tie handling
        $positions = [];
        $lastTotal = null;
        $rank = 0;

        foreach ($studentTotals as $index => $data) {
            if ($lastTotal === null || $data['total'] != $lastTotal) {
                $rank = $index + 1;
            }
            $positions[$data['student_id']] = $rank;
            $lastTotal = $data['total'];
        }

        // Now prepare detailed results with subjects and summary including position
        foreach ($students as $student) {
            $subjects = [];
            $totalMarksObtained = 0;
            $totalGradePoints = 0;
            $subjectCount = 0;
            $failFlag = false;

            foreach ($subjectAssign->items as $item) {
                $subject = $item->subject;

                $finalConfig = FinalMarkConfiguration::where('school_class_id', $this->school_class_id)
                    ->where('subject_id', $subject->id)
                    ->first();

                $distributions = SubjectMarkDistribution::with('markDistribution')
                    ->where('school_class_id', $this->school_class_id)
                    ->where('class_section_id', $this->class_section_id)
                    ->where('subject_id', $subject->id)
                    ->get();

                $ctMark = 0;
                $annualMark = 0;
                $isFailInAnyDistribution = false;

                $absentCount = 0;
                $totalDistributions = $distributions->count();

                $ctDisplayArr = [];
                $annualDisplayArr = [];

                foreach ($distributions as $dist) {
                    $markRecord = StudentMark::where([
                        'student_id' => $student->id,
                        'exam_id' => $this->exam_id,
                        'school_class_id' => $this->school_class_id,
                        'class_section_id' => $this->class_section_id,
                        'subject_id' => $subject->id,
                        'mark_distribution_id' => $dist->mark_distribution_id,
                    ])->first();

                    $isAbsent = $markRecord ? (bool) $markRecord->is_absent : true;
                    $mark = (!$isAbsent && $markRecord) ? ($markRecord->marks_obtained ?? 0) : 0;

                    if ($isAbsent) {
                        $absentCount++;
                    }

                    $isCT = str($dist->markDistribution->name)->contains(['ct', 'class test'], true);

                    if ($isCT) {
                        $ctMark += $mark;
                        $ctDisplayArr[] = $isAbsent ? 'Absent' : $mark;
                    } else {
                        $annualMark += $mark;
                        $annualDisplayArr[] = $isAbsent ? 'Absent' : $mark;
                    }

                    if ($mark < $dist->pass_mark) {
                        $isFailInAnyDistribution = true;
                    }
                }

                $annualMarkWeighted = $finalConfig
                    ? $annualMark * ($finalConfig->final_result_weight_percentage / 100)
                    : $annualMark;

                $total = $ctMark + $annualMarkWeighted;

                $isFullyAbsent = $absentCount === $totalDistributions;

                if ($isFullyAbsent) {
                    $failFlag = true;
                    $isFailInAnyDistribution = true;
                    $gradePoint = 0;
                    $gradeName = 'F';
                    $total = 0;
                } else {
                    $gradeData = Grade::where('start_marks', '<=', $total)
                        ->where('end_marks', '>=', $total)
                        ->first();

                    $gradePoint = $gradeData->grade_point ?? 0;
                    $gradeName = $gradeData->grade_name ?? 'N/A';
                }

                if ($isFailInAnyDistribution) {
                    $failFlag = true;
                }

                // Calculate highest mark among all students for this subject
                $studentsTotalMarks = [];
                foreach ($studentTotals as $studTotal) {
                    $studId = $studTotal['student_id'];

                    $studCT = 0;
                    $studAnnual = 0;

                    foreach ($distributions as $dist) {
                        $studMarkRecord = StudentMark::where([
                            'student_id' => $studId,
                            'exam_id' => $this->exam_id,
                            'school_class_id' => $this->school_class_id,
                            'class_section_id' => $this->class_section_id,
                            'subject_id' => $subject->id,
                            'mark_distribution_id' => $dist->mark_distribution_id,
                        ])->first();

                        $studIsAbsent = $studMarkRecord ? (bool) $studMarkRecord->is_absent : true;
                        $studMark = (!$studIsAbsent && $studMarkRecord) ? ($studMarkRecord->marks_obtained ?? 0) : 0;

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

                $highestGradeName = $this->getLetterGrade($highestGradeData->grade_point ?? 0);

                $subjects[] = [
                    'name' => $subject->name,
                    'full_mark' => $finalConfig ? $finalConfig->other_parts_total : $distributions->sum('mark'),
                    'ct' => $ctDisplayArr,          // You can adjust in your PDF view for better display
                    'annual' => $annualDisplayArr,  // Same here
                    'cal_ct' => $ctMark,
                    'cal_annual' => round($annualMarkWeighted, 2),
                    'total' => round($total, 2),
                    'gpa' => $isFailInAnyDistribution ? number_format(0, 2) : number_format($gradePoint, 2),
                    'grade' => $isFailInAnyDistribution ? 'F' : $gradeName,
                    'result' => $isFailInAnyDistribution ? 'Fail' : 'Pass',
                    'highest' => round($highest, 2),
                    'highest_grade' => $highestGradeName,
                ];

                $totalMarksObtained += $total;

                // Exclude Art from GPA for class 3,4,5
                $excludedFromGPA = in_array((int) $this->school_class_id, [3, 4, 5]) &&
                    strtolower($subject->name) === 'art';

                if (!$excludedFromGPA) {
                    $totalGradePoints += $isFailInAnyDistribution ? 0 : $gradePoint;
                    $subjectCount++;
                }
            }

            $averageGPA = $failFlag ? 0.00 : ($subjectCount > 0 ? round($totalGradePoints / $subjectCount, 2) : 0.00);

            $summary = [
                'total' => round($totalMarksObtained, 2),
                'grade' => $failFlag ? 'F' : $this->getLetterGrade($averageGPA),
                'gpa' => $failFlag ? number_format(0, 2) : number_format($averageGPA, 2),
                'result' => $failFlag ? 'Fail' : 'Pass',
                'position' => $positions[$student->id] ?? 0,
                'comment' => '',
            ];

            $results[] = [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->user->name ?? '',
                    'class' => $student->schoolClass->name ?? '',
                    'section' => $student->classSection->name ?? '',
                    'roll' => $student->roll_number ?? '',
                ],
                'subjects' => $subjects,
                'summary' => $summary,
            ];
        }

        $pdf = FacadePdf::loadView('backend.result.pdf', [
            'results' => $results,
            'className' => optional(SchoolClass::find($this->school_class_id))->name,
            'sectionName' => optional(ClassSection::find($this->class_section_id))->name,
            'exam' => optional(Exam::find($this->exam_id)),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            "Results_{$this->school_class_id}_{$this->class_section_id}_{$this->exam_id}.pdf"
        );
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
        return view('livewire.backend.result.generate-pdf', [
            'classes' => $this->classes,
            'sections' => $this->sections,
            'exams' => $this->exams,
        ]);
    }
}
