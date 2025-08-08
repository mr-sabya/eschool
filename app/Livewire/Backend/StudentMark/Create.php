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

    // Structure: $marks[studentId][markDistributionId] = ['marks_obtained' => X, 'is_absent' => true|false]
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

    public function onSectionChange()
    {
        $this->subjectId = null;
        $this->examId = null;

        $this->students = collect();
        $this->markDistributions = collect();
        $this->marks = [];
    }

    public function onSubjectChange()
    {
        $this->markDistributions = collect();
        $this->students = collect();
        $this->marks = [];

        $this->loadMarkDistributions();
        $this->loadStudents();
        $this->loadExistingMarks();
    }

    public function onExamChange()
    {
        $this->marks = [];
        $this->loadExistingMarks();
    }

    public function loadSections()
    {
        $this->sections = $this->schoolClassId
            ? ClassSection::where('school_class_id', $this->schoolClassId)->get()
            : collect();
    }

    public function loadMarkDistributions()
    {
        $this->markDistributions = ($this->schoolClassId && $this->subjectId)
            ? SubjectMarkDistribution::with('markDistribution')
            ->where('school_class_id', $this->schoolClassId)
            ->where('subject_id', $this->subjectId)
            ->get()
            : collect();
    }

    public function loadStudents()
    {
        $this->students = ($this->schoolClassId && $this->classSectionId)
            ? Student::where('school_class_id', $this->schoolClassId)
            ->where('class_section_id', $this->classSectionId)
            ->orderBy('roll_number')
            ->get()
            : collect();
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
            $this->marks[$mark->student_id][$mark->mark_distribution_id] = [
                'marks_obtained' => $mark->marks_obtained,
                'is_absent' => (bool) $mark->is_absent,
            ];
        }
    }

    public function save()
    {
        $this->validate();

        foreach ($this->marks as $studentId => $distributions) {
            foreach ($distributions as $markDistributionId => $data) {
                $isAbsent = isset($data['is_absent']) && $data['is_absent'];
                $marksObtained = $isAbsent ? null : ($data['marks_obtained'] ?? null);

                if ($marksObtained === null && !$isAbsent) {
                    // If no mark and not absent, delete
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
                        'is_absent' => $isAbsent,
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
                ->map(fn($exam) => [
                    'id' => $exam->id,
                    'name' => $exam->examCategory->name . ' (' . Carbon::parse($exam->start_at)->format('Y-m-d') . ' - ' . Carbon::parse($exam->end_at)->format('Y-m-d') . ')',
                ]),
        ]);
    }
}
