<?php

namespace App\Livewire\Backend\Result;

use Livewire\Component;
use App\Models\{
    Student,
    ClassSubjectAssign,
    Exam,
    MarkDistribution,
    SubjectMarkDistribution
};

class Show extends Component
{
    public $studentId;
    public $examId;
    public $student;
    public $exam;
    public $subjects;
    public $markdistributions;
    public $students;

    public function mount($studentId, $examId)
    {
        $this->studentId = $studentId;
        $this->examId = $examId;

        // Load the student with relations
        $this->student = Student::with(['schoolClass', 'classSection'])->findOrFail($this->studentId);

        // students of list class section
        $this->students = Student::where('school_class_id', $this->student->school_class_id)
            ->where('class_section_id', $this->student->class_section_id)
            ->get();

        // Load the exam
        $this->exam = Exam::findOrFail($this->examId);

        // Load assigned subjects for student's class, section and session
        $this->subjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', $this->student->academic_session_id)
            ->where('school_class_id', $this->student->school_class_id)
            ->where('class_section_id', $this->student->class_section_id)
            ->get();

        // Get subject IDs assigned
        $subjectIds = $this->subjects->pluck('subject_id')->toArray();

        // Load mark distributions
        $this->markdistributions = SubjectMarkDistribution::with('markDistribution')
            ->where('school_class_id', $this->student->school_class_id)
            ->where('class_section_id', $this->student->class_section_id)
            ->whereIn('subject_id', $subjectIds)
            ->get()
            ->unique(function ($item) {
                return $item->markDistribution->name;
            })
            ->values(); // reset keys
    }

    public function render()
    {
        return view('livewire.backend.result.show', [
            'student' => $this->student,
            'exam' => $this->exam,
            'subjects' => $this->subjects,
        ]);
    }
}
