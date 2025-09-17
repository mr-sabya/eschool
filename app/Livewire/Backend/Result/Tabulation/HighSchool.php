<?php

namespace App\Livewire\Backend\Result\Tabulation;

use App\Models\Student;
use App\Models\Exam;
use App\Models\ClassSubjectAssign;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\ClassPositionHelper;
use App\Helpers\SchoolHighestMarkHelper;

class HighSchool extends Component
{
    public $classId, $sectionId, $examId, $sessionId;

    public $students = [];
    public $subjects = [];
    public $exam;

    public function mount($classId, $sectionId, $examId, $sessionId)
    {
        $this->classId   = $classId;
        $this->sectionId = $sectionId;
        $this->examId    = $examId;
        $this->sessionId = $sessionId;
    }

    public function loadData()
    {
        $this->exam = Exam::with('academicSession', 'examCategory')->findOrFail($this->examId);

        $this->students = Student::with('user')
            ->where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->where('academic_session_id', $this->sessionId)
            ->get();

        $this->subjects = ClassSubjectAssign::with('subject')
            ->where('school_class_id', $this->classId)
            ->where('class_section_id', $this->sectionId)
            ->where('academic_session_id', $this->sessionId)
            ->get();
    }

    public function downloadPdf()
    {
        $this->loadData();

        $pdf = Pdf::loadView('backend.result.tabulation-pdf', [
            'students' => $this->students,
            'subjects' => $this->subjects,
            'exam'     => $this->exam,
            'classId'  => $this->classId,
            'sectionId' => $this->sectionId,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'tabulation-sheet.pdf');
    }

    public function render()
    {
        return view('livewire.backend.result.tabulation.high-school');
    }
}
