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

        // Fetch all students in the class for highest mark calculation
        $this->students = Student::where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->when($this->student->department_id, fn ($q) => $q->where('department_id', $this->student->department_id))
            ->get();
        $studentIds = $this->students->pluck('id');

        // 2. Fetch all student marks for the entire class, this is our source of truth.
        $allStudentMarks = StudentMark::where('exam_id', $this->examId)
            ->whereIn('student_id', $studentIds)
            ->get()->groupBy(['student_id', 'subject_id']);

        // 3. Get all assigned subjects and their configurations for the class
        $allAssignedSubjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', $this->sessionId)
            ->where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->get();
        $allAssignedSubjectIds = $allAssignedSubjects->pluck('subject_id');

        $finalMarkConfigs = FinalMarkConfiguration::where('school_class_id', $this->classId)
            ->whereIn('subject_id', $allAssignedSubjectIds)
            ->when($this->student->department_id, function ($query) {
                $query->where(fn($subQuery) =>
                    $subQuery->where('department_id', $this->student->department_id)->orWhereNull('department_id')
                );
            }, fn($query) => $query->whereNull('department_id'))
            ->get()->keyBy('subject_id');
        
        $subjectMarkDistributions = SubjectMarkDistribution::with('markDistribution')
            ->where('school_class_id', $this->classId)
            ->whereIn('subject_id', $allAssignedSubjectIds)
            ->get();
        
        // This makes sure we have subject names available for the final report.
        $this->subjects = $allAssignedSubjects;

        // 4. Determine visible headers for the table
        $allowedExamDistributionIds = $this->exam->markDistributionTypes->pluck('id');
        $this->markdistributions = $subjectMarkDistributions->pluck('markDistribution')->whereIn('id', $allowedExamDistributionIds)->unique('id')->values();
        $this->hasClassTest = $this->markdistributions->contains('name', 'Class Test');
        $this->hasOtherMarks = $this->markdistributions->where('name', '!=', 'Class Test')->isNotEmpty();


        // ============================ START: REWRITTEN LOGIC ============================

        // 5. Pre-process results for all students to find the highest marks
        $resultsByStudent = collect();
        foreach ($allStudentMarks as $sId => $subjects) {
            $studentResults = collect();
            foreach ($subjects as $subjectId => $marks) {
                $config = $finalMarkConfigs->get($subjectId);
                if ($config) {
                    // Note: 'calculateSubjectResult' doesn't use the student object, so this is safe
                    $studentResults->put($subjectId, $this->calculateSubjectResult($marks, $config, $subjectMarkDistributions, $allowedExamDistributionIds));
                }
            }
            $resultsByStudent->put($sId, $studentResults);
        }

        // 6. Determine highest marks using the processed results
        $highestMarks = collect();
        foreach ($allAssignedSubjectIds as $subjectId) {
            $highestMarks[$subjectId] = $resultsByStudent->max(fn ($studentResults) => $studentResults->get($subjectId)['total_calculated_marks'] ?? 0);
        }

        // 7. Assemble the final report for the specific student being viewed
        // This is the most critical part of the fix. We start from the student's actual marks.
        $marksForThisStudent = $allStudentMarks->get($this->studentId);

        if ($marksForThisStudent) {
            // Loop through ONLY the subjects that the student has marks for.
            foreach ($marksForThisStudent as $subjectId => $studentMarksForSubject) {
                $result = $resultsByStudent->get($this->studentId)?->get($subjectId);

                // If a result was successfully calculated, process it.
                if ($result) {
                    $result['highest_mark'] = $highestMarks->get($subjectId, 0);
                    $isFourthSubject = $studentMarksForSubject->first()->is_fourth_subject ?? false;

                    if ($isFourthSubject) {
                        $this->fourthSubjectMarks = $result;
                    } else {
                        $this->marks[] = $result;
                    }
                }
            }
        }
        
        // ============================ END: REWRITTEN LOGIC ============================

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
