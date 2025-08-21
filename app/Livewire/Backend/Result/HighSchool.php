<?php

namespace App\Livewire\Backend\Result;

use App\Helpers\SchoolHighestMarkHelper;
use App\Models\ClassSubjectAssign;
use App\Models\Exam;
use App\Models\FinalMarkConfiguration;
use App\Models\Grade;
use App\Models\MarkDistribution;
use App\Models\Student;
use App\Models\StudentMark;
use App\Models\Subject;
use App\Models\SubjectMarkDistribution;
use Livewire\Component;

class HighSchool extends Component
{
    public $studentId;
    public $examId;
    public $classId;
    public $sectionId;
    public $sessionId;

    public $student;
    public $exam;
    public $subjects;
    public $markdistributions;
    public $students;

    public $marks = [];
    public $fourthSubjectMarks;

    public $readyToLoad = false;

    public function mount($studentId, $examId, $classId, $sectionId, $sessionId)
    {
        // Only store IDs here, don’t fetch heavy data yet
        $this->studentId  = $studentId;
        $this->examId     = $examId;
        $this->classId    = $classId;
        $this->sectionId  = $sectionId;
        $this->sessionId  = $sessionId;
    }

    public function loadReport()
    {
        $this->readyToLoad = true;

        // Now fetch heavy data here
        $this->student = Student::with(['schoolClass', 'classSection'])->findOrFail($this->studentId);

        $this->students = Student::where('school_class_id', $this->student->school_class_id)
            ->where('class_section_id', $this->student->class_section_id)
            ->get();

        $this->exam = Exam::findOrFail($this->examId);

        $this->subjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', $this->student->academic_session_id)
            ->where('school_class_id', $this->student->school_class_id)
            ->where('class_section_id', $this->student->class_section_id)
            ->where('department_id', $this->student->department_id)
            ->get();

        $subjectIds = $this->subjects->pluck('subject_id')->toArray();

        $this->markdistributions = SubjectMarkDistribution::with('markDistribution')
            ->where('school_class_id', $this->student->school_class_id)
            ->where('class_section_id', $this->student->class_section_id)
            ->whereIn('subject_id', $subjectIds)
            ->get()
            ->unique(fn($item) => $item->markDistribution->name)
            ->values();

        // **Populate marks for all subjects**
        foreach ($this->subjects as $subject) {
            $subjectMarks = $this->getSubjectResults($subject->subject_id);

            // Check if this subject is the fourth subject
            $studentFourthSubject = StudentMark::where('student_id', $this->student->id)
                ->where('exam_id', $this->exam->id)
                ->where('subject_id', $subject->subject_id)
                ->where('is_fourth_subject', 1)
                ->first();

            if ($studentFourthSubject) {
                $this->fourthSubjectMarks = $subjectMarks; // store the fourth subject marks
                continue; // optionally skip adding it to main marks array if you want it separate
            }

            $this->marks[] = $subjectMarks;
        }

        // dd($this->marks);


    }

    public function getSubjectResults($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);

        $finalMarkConfiguration = FinalMarkConfiguration::where('school_class_id', $this->student->school_class_id)
            ->where('subject_id', $subject->id)
            ->first();


        $annualFullMark = $finalMarkConfiguration ? $finalMarkConfiguration->other_parts_total : 0;

        // Check if any mark distribution for this subject excludes it from GPA
        $excludeFromGPA = $finalMarkConfiguration ? $finalMarkConfiguration->exclude_from_gpa : false;

        $totalCalculatedMark = 0;
        $failedAnyDistribution = false;

        // Local marks array
        $marks = [
            'subject_id' => $subject->id,
            'subject_name' => $subject->name,
            'full_mark' => $annualFullMark,
            'obtained_marks' => [],
            'class_test_result' => null,
            'other_parts_total' => 0,
            'total_calculated_marks' => 0,
            'highest_mark' => 0,
            'grade_name' => '',
            'grade_point' => 0,
            'final_result' => '',
            'exclude_from_gpa' => $excludeFromGPA,
            'fail_any_distribution' => $failedAnyDistribution,
        ];


        foreach ($this->markdistributions as $distribution) {

            $markDistribution = MarkDistribution::where('name', $distribution->markDistribution['name'])->first();

            $subjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subject->id)
                ->where('school_class_id', $this->student->school_class_id)
                ->where('class_section_id', $this->student->class_section_id)
                ->where('mark_distribution_id', $markDistribution ? $markDistribution->id : null)
                ->first();

            $studentSubjectMark = null;
            if ($subjectMarkDistribution) {
                $studentSubjectMark = StudentMark::where('student_id', $this->student->id)
                    ->where('subject_id', $subject->id)
                    ->where('school_class_id', $this->student->school_class_id)
                    ->where('exam_id', $this->exam->id)
                    ->where('mark_distribution_id', $markDistribution->id)
                    ->first();
            }

            // Calculate obtained mark
            if ($studentSubjectMark) {
                $passMark = $subjectMarkDistribution->pass_mark ?? 0;
                $marksObtained = $studentSubjectMark->marks_obtained;
                $isPass = $marksObtained >= $passMark;

                if ($studentSubjectMark->is_absent) {
                    if ($markDistribution['name'] != 'Class Test') {
                        $failedAnyDistribution = true;
                    }
                    $marks['obtained_marks'][] = '<span style="color:red;">Absent</span>';
                } elseif (!$isPass) {
                    $marks['obtained_marks'][] = '<span style="color:red;">Fail (' . $marksObtained . ')</span>';
                    $failedAnyDistribution = true;
                } else {
                    $marks['obtained_marks'][] = $marksObtained;
                }
            } else {
                $marks['obtained_marks'][] = '-';
            }

            // Total calculated mark
            $calculatedMark = $studentSubjectMark && !$studentSubjectMark->is_absent
                ? round(($marksObtained * $finalMarkConfiguration->final_result_weight_percentage) / 100)
                : 0;

            $totalCalculatedMark += $calculatedMark;
        }

        // calculated marks
        // for ct
        $ctMarkDistribution = MarkDistribution::where('name', 'Class Test')->first();

        $ctSubjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subject->id)
            ->where('school_class_id', $this->student->school_class_id)
            ->where('class_section_id', $this->student->class_section_id)
            ->where('mark_distribution_id', $ctMarkDistribution->id)
            ->first();

        if ($ctSubjectMarkDistribution) {
            $classTestMark = StudentMark::where('student_id', $this->student->id)
                ->where('subject_id', $subject->id)
                ->where('school_class_id', $this->student->school_class_id)
                ->where('exam_id', $this->exam->id)
                ->where('mark_distribution_id', $ctMarkDistribution->id)
                ->first();
        } else {
            $classTestMark = null;
        }

        $finalClassTestMark = $classTestMark ? $classTestMark->marks_obtained : 0;

        if ($classTestMark) {
            if ($classTestMark->is_absent) {
                $classTestResult = 0;
            } else {
                $classTestResult = $finalClassTestMark;
            }
        } else {
            $classTestResult = '-';
        }
        $marks['class_test_result'] = $classTestResult;


        // other parts total 
        $otherMarkDistributions = MarkDistribution::where('name', '!=', 'Class Test')->get();

        $totalSubjectMark = 0;
        foreach ($otherMarkDistributions as $distribution) {
            $getMarkDistribution = MarkDistribution::where('name', $distribution->name)->first();

            $subjectMarkDistribution = SubjectMarkDistribution::where('subject_id', $subject->id)
                ->where('school_class_id', $this->student->school_class_id)
                ->where('class_section_id', $this->student->class_section_id)
                ->where('mark_distribution_id', $getMarkDistribution ? $getMarkDistribution->id : null)
                ->first();

            $studentSubjectMark = null;

            if ($subjectMarkDistribution) {
                $studentSubjectMark = StudentMark::where('student_id', $this->student->id)
                    ->where('subject_id', $subject->id)
                    ->where('school_class_id', $this->student->school_class_id)
                    ->where('exam_id', $this->exam->id)
                    ->where('mark_distribution_id', $getMarkDistribution->id)
                    ->first();
            }

            $marksObtained = $studentSubjectMark ? $studentSubjectMark->marks_obtained : 0;
            $totalSubjectMark += $marksObtained;
        }

        $calculatedMark = round(($totalSubjectMark * $finalMarkConfiguration->final_result_weight_percentage) / 100);

        $totalCalculatedMark = $calculatedMark + $finalClassTestMark;

        $marks['other_parts_total'] = $calculatedMark;
        $marks['total_calculated_marks'] = $totalCalculatedMark;


        // highest mark
        $highestMark = SchoolHighestMarkHelper::getHighestMark($this->students, $subject->id, $this->student->school_class_id, $this->student->class_section_id, $this->exam->id);

        $marks['highest_mark'] = $highestMark['highest_mark'];

        // gpa
        $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
            ->where('end_marks', '>=', $totalCalculatedMark)
            ->where('grading_scale', $finalMarkConfiguration->grading_scale)
            ->first();

        if($failedAnyDistribution) {
            $marks['grade_name'] = 'F';
            $marks['grade_point'] = number_format(0, 2);;
        } else {
            $marks['grade_name'] = $grade ? $grade->grade_name : 'N/A';
            $marks['grade_point'] = $grade ? $grade->grade_point : number_format(0, 2);;
        }

        $marks['final_result'] = $failedAnyDistribution ? '<span style="color:red;">Fail</span>' : 'Pass';
        $marks['fail_any_distribution'] = $failedAnyDistribution;

        return $marks;
    }


    public function render()
    {
        return view('livewire.backend.result.high-school', [
            'student'  => $this->student,
            'exam'     => $this->exam,
            'subjects' => $this->subjects,
        ]);
    }
}
