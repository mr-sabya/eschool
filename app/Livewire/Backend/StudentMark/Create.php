<?php

namespace App\Livewire\Backend\StudentMark;

use Livewire\Component;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Subject;
use App\Models\Student;
use App\Models\SubjectMarkDistribution;
use App\Models\StudentMark;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Create extends Component
{
    public $schoolClassId;
    public $classSectionId;
    public $subjectId;
    public $examId;

    public Collection $sections;
    public Collection $students;
    public Collection $markDistributions;

    // [studentId][markDistributionId] => marks_obtained
    public $marks = [];

    protected $rules = [
        'schoolClassId' => 'required|exists:school_classes,id',
        'classSectionId' => 'required|exists:class_sections,id',
        'subjectId' => 'required|exists:subjects,id',
        'examId' => 'required|exists:exams,id',
    ];

    public function mount()
    {
        $this->sections = collect();
        $this->students = collect();
        $this->markDistributions = collect();
        $this->marks = [];
    }

    // Custom handler for Class select change
    public function onClassChange()
    {
        $this->classSectionId = null;
        $this->subjectId = null;
        $this->examId = null;

        $this->students = collect();
        $this->markDistributions = collect();
        $this->marks = [];

        $this->loadSections();
    }

    // Custom handler for Section select change
    public function onSectionChange()
    {
        $this->subjectId = null;
        $this->examId = null;

        $this->students = collect();
        $this->markDistributions = collect();
        $this->marks = [];
    }

    // Custom handler for Subject select change
    public function onSubjectChange()
    {
        $this->markDistributions = collect();
        $this->students = collect();
        $this->marks = [];

        $this->loadMarkDistributions();
        $this->loadStudents();
        $this->loadExistingMarks();
    }

    // Custom handler for Exam select change
    public function onExamChange()
    {
        $this->marks = [];
        $this->loadExistingMarks();
    }

    public function loadSections()
    {
        if ($this->schoolClassId) {
            $this->sections = ClassSection::where('school_class_id', $this->schoolClassId)->get();
        } else {
            $this->sections = collect();
        }
    }

    public function loadMarkDistributions()
    {
        if ($this->schoolClassId && $this->subjectId) {
            $this->markDistributions = SubjectMarkDistribution::with('markDistribution')
                ->where('school_class_id', $this->schoolClassId)
                ->where('subject_id', $this->subjectId)
                ->get();
        } else {
            $this->markDistributions = collect();
        }
    }

    public function loadStudents()
    {
        if ($this->schoolClassId && $this->classSectionId) {
            $this->students = Student::where('school_class_id', $this->schoolClassId)
                ->where('class_section_id', $this->classSectionId)
                ->orderBy('first_name')
                ->get();
        } else {
            $this->students = collect();
        }
    }

    public function loadExistingMarks()
    {
        if (!($this->schoolClassId && $this->classSectionId && $this->subjectId && $this->examId)) {
            return;
        }

        $existingMarks = StudentMark::where('school_class_id', $this->schoolClassId)
            ->where('class_section_id', $this->classSectionId)
            ->where('subject_id', $this->subjectId)
            ->where('exam_id', $this->examId)
            ->get();

        foreach ($existingMarks as $mark) {
            $this->marks[$mark->student_id][$mark->mark_distribution_id] = $mark->marks_obtained;
        }
    }

    public function save()
    {
        $this->validate();

        foreach ($this->marks as $studentId => $markDistributionArray) {
            foreach ($markDistributionArray as $markDistributionId => $marksObtained) {
                if ($marksObtained === null || $marksObtained === '') {
                    // Delete existing if input cleared
                    StudentMark::where([
                        'student_id' => $studentId,
                        'school_class_id' => $this->schoolClassId,
                        'class_section_id' => $this->classSectionId,
                        'subject_id' => $this->subjectId,
                        'exam_id' => $this->examId,
                        'mark_distribution_id' => $markDistributionId,
                    ])->delete();
                    continue;
                }

                StudentMark::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'school_class_id' => $this->schoolClassId,
                        'class_section_id' => $this->classSectionId,
                        'subject_id' => $this->subjectId,
                        'exam_id' => $this->examId,
                        'mark_distribution_id' => $markDistributionId,
                    ],
                    [
                        'marks_obtained' => $marksObtained,
                    ]
                );
            }
        }

        session()->flash('message', 'Marks saved successfully.');
    }

    public function render()
    {
        return view('livewire.backend.student-mark.create', [
            'classes' => SchoolClass::where('is_active', true)->get(),
            'sections' => $this->sections ?? collect(),
            'students' => $this->students ?? collect(),
            'markDistributions' => $this->markDistributions ?? collect(),
            'subjects' => $this->schoolClassId
                ? Subject::whereHas('subjectMarkDistributions', function ($q) {
                    $q->where('school_class_id', $this->schoolClassId);
                })->get()
                : collect(),
            'exams' => Exam::all()
                ->filter(fn($exam) => $exam->academicSession->is_active)
                ->map(function ($exam) {
                    $startDate = Carbon::parse($exam->start_at);
                    $endDate = Carbon::parse($exam->end_at);
                    return [
                        'id' => $exam->id,
                        'name' => $exam->examCategory->name . ' (' . $startDate->format('Y-m-d') . ' - ' . $endDate->format('Y-m-d') . ')',
                    ];
                }),
        ]);
    }
}
