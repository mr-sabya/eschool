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
use PDF;

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

                $fullMark = $finalConfig ? $finalConfig->other_parts_total : $distributions->sum('mark');
                $passMark = $distributions->min('pass_mark');

                $ctMark = 0;
                $annualMark = 0;

                foreach ($distributions as $dist) {
                    $mark = StudentMark::where([
                        'student_id' => $student->id,
                        'exam_id' => $this->exam_id,
                        'school_class_id' => $this->school_class_id,
                        'class_section_id' => $this->class_section_id,
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

                if ($total < $passMark) {
                    $failFlag = true;
                }

                // Calculate highest mark for this subject across all students
                $studentsTotalMarks = [];

                foreach ($studentIds as $studId) {
                    $studCT = 0;
                    $studAnnual = 0;

                    foreach ($distributions as $dist) {
                        $studMark = StudentMark::where([
                            'student_id' => $studId,
                            'exam_id' => $this->exam_id,
                            'school_class_id' => $this->school_class_id,
                            'class_section_id' => $this->class_section_id,
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

                $highestGradeName = $highestGradeData->grade_name ?? $this->getLetterGrade($highestGradeData->grade_point ?? 0);

                $subjects[] = [
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
                    'highest_grade' => $highestGradeName,
                ];

                $totalMarksObtained += $total;
                $totalGradePoints += $gradePoint;
                $subjectCount++;
            }

            $averageGPA = $subjectCount > 0 ? round($totalGradePoints / $subjectCount, 2) : 0;

            $summary = [
                'total' => round($totalMarksObtained, 2),
                'grade' => $failFlag ? 'F' : $this->getLetterGrade($averageGPA),
                'gpa' => $averageGPA,
                'result' => $failFlag ? 'Fail' : 'Pass',
                'position' => 0,
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
