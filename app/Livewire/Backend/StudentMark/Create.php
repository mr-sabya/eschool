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
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Create extends Component
{
    public $departmentId;
    public $schoolClassId;
    public $classSectionId;
    public $subjectId;
    public $examId;

    public Collection $departments;
    public Collection $sections;
    public Collection $students;
    public Collection $markDistributions;

    public Subject|null $currentSubject = null;  // to hold selected subject model

    // Structure: $marks[studentId][markDistributionId] = [
    //    'marks_obtained' => X,
    //    'is_absent' => true|false,
    // ]
    public $marks = [];

    // Track 4th subject checkbox per student
    public $fourth_subject = [];  // [studentId => true|false]

    protected $rules = [
        'departmentId' => 'nullable|exists:departments,id',
        'schoolClassId' => 'required|exists:school_classes,id',
        'classSectionId' => 'required|exists:class_sections,id',
        'subjectId' => 'required|exists:subjects,id',
        'examId' => 'required|exists:exams,id',
    ];

    public function mount()
    {
        $this->departments = Department::all();
        $this->sections = collect();
        $this->students = collect();
        $this->markDistributions = collect();
        $this->marks = [];
        $this->fourth_subject = [];
    }

    public function onDepartmentChange()
    {
        $this->subjectId = null;
        $this->examId = null;
        $this->markDistributions = collect();
        $this->students = collect();
        $this->marks = [];
        $this->fourth_subject = [];
        $this->currentSubject = null;

        $this->loadStudents();
        $this->loadMarkDistributions();
    }

    public function onClassChange()
    {
        $this->classSectionId = null;
        $this->subjectId = null;
        $this->examId = null;

        $this->students = collect();
        $this->markDistributions = collect();
        $this->marks = [];
        $this->fourth_subject = [];
        $this->currentSubject = null;

        $this->loadSections();
    }

    public function onSectionChange()
    {
        $this->subjectId = null;
        $this->examId = null;

        $this->students = collect();
        $this->markDistributions = collect();
        $this->marks = [];
        $this->fourth_subject = [];
        $this->currentSubject = null;
    }

    public function onSubjectChange()
    {
        $this->markDistributions = collect();
        $this->students = collect();
        $this->marks = [];
        $this->fourth_subject = [];

        // Load the selected subject model
        $this->currentSubject = $this->subjectId ? Subject::find($this->subjectId) : null;

        $this->loadMarkDistributions();
        $this->loadStudents();
        $this->loadExistingMarks();
    }

    public function onExamChange()
    {
        $this->marks = [];
        $this->fourth_subject = [];
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
        if (!$this->schoolClassId || !$this->subjectId) {
            $this->markDistributions = collect();
            return;
        }

        $query = SubjectMarkDistribution::with('markDistribution')
            ->where('school_class_id', $this->schoolClassId)
            ->where('subject_id', $this->subjectId);

        if ($this->departmentId) {
            $query->where('department_id', $this->departmentId);
        }

        $this->markDistributions = $query->get();
    }

    public function loadStudents()
    {
        if (!$this->schoolClassId || !$this->classSectionId) {
            $this->students = collect();
            return;
        }

        $query = Student::where('school_class_id', $this->schoolClassId)
            ->where('class_section_id', $this->classSectionId)
            ->orderBy('roll_number');

        if ($this->departmentId) {
            $query->where('department_id', $this->departmentId);
        }

        $this->students = $query->get();
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

            // If this subject is 4th subject, track is_fourth_subject per student
            if ($mark->is_fourth_subject) {
                $this->fourth_subject[$mark->student_id] = true;
            }
        }
    }

    public function save()
    {
        $this->validate();

        // dd($this->marks);

        foreach ($this->marks as $studentId => $distributions) {
            foreach ($distributions as $markDistributionId => $data) {
                $isAbsent = isset($data['is_absent']) && $data['is_absent'];
                $marksObtained = $isAbsent ? null : ($data['marks_obtained'] ?? null);

                if ($marksObtained === null && !$isAbsent) {
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
                        'is_fourth_subject' => $this->currentSubject && $this->currentSubject->is_fourth_subject
                            ? ($this->fourth_subject[$studentId] ?? false)
                            : false,
                    ]
                );
            }
        }

        session()->flash('message', 'Marks saved successfully.');
    }

    public function render()
    {
        return view('livewire.backend.student-mark.create', [
            'departments' => $this->departments,
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
