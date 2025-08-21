<?php

namespace App\Livewire\Backend\Result;

use App\Models\ClassSubjectAssign;
use App\Models\Exam;
use App\Models\Student;
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
            ->unique(fn ($item) => $item->markDistribution->name)
            ->values();
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
