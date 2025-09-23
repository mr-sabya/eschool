<?php

namespace App\Livewire\Backend\Result;

use App\Helpers\ClassPositionHelper; // Keep if you use the position helper in the view
use App\Models\ClassSubjectAssign;
use App\Models\Exam;
use App\Models\FinalMarkConfiguration;
use App\Models\Grade;
use App\Models\MarkDistribution;
use App\Models\Student;
use App\Models\StudentMark;
use App\Models\SubjectMarkDistribution;
use Illuminate\Support\Collection;
use Livewire\Component;

class Show extends Component
{
    public $studentId;
    public $examId;
    public $classId;
    public $sectionId;
    public $sessionId;

    /** @var Student|null */
    public $student;

    /** @var Exam|null */
    public $exam;

    /** @var Collection<int, ClassSubjectAssign>|null */
    public $subjects;

    /** @var Collection<int, Student> */
    public $students = [];

    /** @var Collection<int, MarkDistribution>|null */
    public $markdistributions;

    public $marks = [];
    public $fourthSubjectMarks;

    // Flags to control the visibility of calculated columns in the view.
    public $hasClassTest = false;
    public $hasOtherMarks = false;

    public $readyToLoad = false;

    public function mount($studentId, $examId, $classId, $sectionId, $sessionId)
    {
        $this->studentId = $studentId;
        $this->examId = $examId;
        $this->classId = $classId;
        $this->sectionId = $sectionId;
        $this->sessionId = $sessionId;
    }

    public function loadReport()
    {
        // 1. Fetch core data
        $this->student = Student::with(['user', 'schoolClass', 'classSection'])->findOrFail($this->studentId);
        $this->exam = Exam::with(['examCategory', 'academicSession', 'markDistributionTypes'])->findOrFail($this->examId);

        // Fetch all students in the class
        $this->students = Student::where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->when($this->student->department_id, function ($query) {
                $query->where('department_id', $this->student->department_id);
            })
            ->get();
        $studentIds = $this->students->pluck('id');

        // 2. Fetch all subjects and their distribution configurations
        $allAssignedSubjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', $this->sessionId)
            ->where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->where('department_id', $this->student->department_id)
            ->get();
        $allAssignedSubjectIds = $allAssignedSubjects->pluck('subject_id');

        $subjectMarkDistributions = SubjectMarkDistribution::with('markDistribution')
            ->where('school_class_id', $this->classId)
            ->whereIn('subject_id', $allAssignedSubjectIds)
            ->get();

        $configuredSubjectIds = $subjectMarkDistributions->pluck('subject_id')->unique();
        $this->subjects = $allAssignedSubjects->whereIn('subject_id', $configuredSubjectIds);

        if ($this->subjects->isEmpty()) {
            $this->readyToLoad = true;
            return;
        }

        $finalMarkConfigs = FinalMarkConfiguration::where('school_class_id', $this->classId)
            ->whereIn('subject_id', $configuredSubjectIds)
            ->get()->keyBy('subject_id');

        // 3. Determine the final, visible headers for the report table
        // A column is visible only if it's configured for a subject AND allowed by the exam.
        $allConfiguredDistributions = $subjectMarkDistributions->pluck('markDistribution')->unique('id')->values();
        $allowedExamDistributionIds = $this->exam->markDistributionTypes->pluck('id');
        $this->markdistributions = $allConfiguredDistributions->whereIn('id', $allowedExamDistributionIds);

        // Set the new flags based on the final, visible distribution headers.
        $this->hasClassTest = $this->markdistributions->contains('name', 'Class Test');
        $this->hasOtherMarks = $this->markdistributions->where('name', '!=', 'Class Test')->isNotEmpty();

        // 4. Fetch all student marks
        $allStudentMarks = StudentMark::where('exam_id', $this->examId)
            ->whereIn('student_id', $studentIds)
            ->whereIn('subject_id', $configuredSubjectIds)
            ->get()->groupBy(['student_id', 'subject_id']);

        $allowedDistributionIdsForCalculation = $this->exam->markDistributionTypes->pluck('id');

        // 5. Pre-process results for all students
        $resultsByStudent = collect();
        foreach ($this->students as $currentStudent) {
            $studentResults = collect();
            foreach ($this->subjects as $subject) {
                $studentMarksForSubject = $allStudentMarks->get($currentStudent->id)?->get($subject->subject_id) ?? collect();
                $config = $finalMarkConfigs->get($subject->subject_id);

                if ($config) {
                    $studentResults->put($subject->subject_id, $this->calculateSubjectResult($studentMarksForSubject, $config, $subjectMarkDistributions, $allowedDistributionIdsForCalculation));
                }
            }
            $resultsByStudent->put($currentStudent->id, $studentResults);
        }

        // 6. Determine highest marks for each subject
        $highestMarks = collect();
        foreach ($configuredSubjectIds as $subjectId) {
            $highestMarks[$subjectId] = $resultsByStudent->max(function ($studentResults) use ($subjectId) {
                return $studentResults->get($subjectId)['total_calculated_marks'] ?? 0;
            });
        }

        // 7. Assemble the final marks for the specific student being viewed
        $targetStudentResults = $resultsByStudent->get($this->studentId) ?? collect();

        foreach ($targetStudentResults as $subjectId => $result) {
            $result['highest_mark'] = $highestMarks->get($subjectId, 0);

            $isFourthSubject = $allStudentMarks->get($this->studentId)?->get($subjectId)?->first()?->is_fourth_subject ?? false;

            if ($isFourthSubject) {
                $this->fourthSubjectMarks = $result;
            } else {
                $this->marks[] = $result;
            }
        }

        $this->readyToLoad = true;
    }

    private function calculateSubjectResult(Collection $studentMarks, FinalMarkConfiguration $config, Collection $allSubjectDistributions, Collection $allowedDistributionIds)
    {
        // Only keep marks where the distribution type is allowed for this exam.
        $validStudentMarks = $studentMarks->whereIn('mark_distribution_id', $allowedDistributionIds);

        $failedAnyDistribution = false;
        $totalCalculatedMark = 0;
        $otherPartsTotal = 0;
        $ctMark = 0;
        $annualFullMark = $config->other_parts_total ?? 0;
        $obtainedMarksArray = [];

        $ctMarkDistributionId = MarkDistribution::where('name', 'Class Test')->first()->id ?? null;
        $marksByDistribution = $validStudentMarks->keyBy('mark_distribution_id');

        // This loop iterates over the final, doubly-filtered list of headers ($this->markdistributions)
        foreach ($this->markdistributions as $distribution) {
            $subjectDistributionConfig = $allSubjectDistributions
                ->where('subject_id', $config->subject_id)
                ->where('mark_distribution_id', $distribution->id)
                ->first();

            if (!$subjectDistributionConfig) {
                $obtainedMarksArray[] = '-';
                continue;
            }

            $passMark = $subjectDistributionConfig->pass_mark ?? 0;
            $mark = $marksByDistribution->get($distribution->id);

            if ($mark) {
                $marksObtained = $mark->marks_obtained;
                $isPass = $marksObtained >= $passMark;

                if ($mark->is_absent) {
                    if ($distribution->id != $ctMarkDistributionId) $failedAnyDistribution = true;
                    $obtainedMarksArray[] = '<span style="color:red;">Absent</span>';
                } elseif (!$isPass) {
                    $failedAnyDistribution = true;
                    $obtainedMarksArray[] = '<span style="color:red;">Fail (' . $marksObtained . ')</span>';
                } else {
                    $obtainedMarksArray[] = $marksObtained;
                }

                if ($distribution->id == $ctMarkDistributionId) {
                    $ctMark = $marksObtained;
                } else {
                    $otherPartsTotal += $marksObtained;
                }
            } else {
                $obtainedMarksArray[] = '-';
            }
        }

        $calculatedOtherParts = round(($otherPartsTotal * $config->final_result_weight_percentage) / 100);
        $totalCalculatedMark = $calculatedOtherParts + $ctMark;

        $grade = Grade::where('start_marks', '<=', $totalCalculatedMark)
            ->where('end_marks', '>=', $totalCalculatedMark)
            ->where('grading_scale', $config->grading_scale)
            ->first();

        if ($grade && $grade->grade_point == 0) {
            $failedAnyDistribution = true;
        }

        return [
            'subject_id'             => $config->subject_id,
            'subject_name'           => $this->subjects->firstWhere('subject_id', $config->subject_id)->subject->name,
            'full_mark'              => $annualFullMark,
            'obtained_marks'         => $obtainedMarksArray,
            'class_test_result'      => $ctMark,
            'other_parts_total'      => $calculatedOtherParts,
            'total_calculated_marks' => $totalCalculatedMark,
            'grade_name'             => $failedAnyDistribution ? 'F' : ($grade->grade_name ?? 'N/A'),
            'grade_point'            => $failedAnyDistribution ? number_format(0, 2) : ($grade->grade_point ?? number_format(0, 2)),
            'final_result'           => $failedAnyDistribution ? '<span style="color:red;">Fail</span>' : 'Pass',
            'exclude_from_gpa'       => $config->exclude_from_gpa ?? false,
            'fail_any_distribution'  => $failedAnyDistribution,
        ];
    }

    public function render()
    {
        return view('livewire.backend.result.show');
    }
}
