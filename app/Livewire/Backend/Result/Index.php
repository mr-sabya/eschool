<?php

namespace App\Livewire\Backend\Result;

use App\Livewire\Backend\Result\HighSchool;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class Index extends Component
{
    public $academicSessions;
    public $classes;
    public $sections = [];
    public $exams = [];

    public $selectedSession = null;
    public $selectedClass = null;
    public $selectedSection = null;
    public $selectedExam = null;
    public $selectedDepartment = null;

    public $students;

    public function mount()
    {
        $this->academicSessions = AcademicSession::orderBy('start_date', 'desc')->get();
        $this->classes = SchoolClass::orderBy('id')->get();
        $this->students = collect();
    }

    public function loadExamsForSession()
    {
        $this->selectedExam = null;
        $this->students = collect();

        if ($this->selectedSession) {
            $this->exams = Exam::where('academic_session_id', $this->selectedSession)
                ->orderBy('start_at')
                ->get();
        } else {
            $this->exams = [];
        }
    }

    public function loadSectionsForClass()
    {
        if ($this->selectedClass) {
            $this->sections = ClassSection::where('school_class_id', $this->selectedClass)
                ->orderBy('name')
                ->get();
            $this->selectedSection = null;
            $this->students = collect();
        } else {
            $this->sections = [];
            $this->selectedSection = null;
            $this->students = collect();
        }
        $this->selectedDepartment = null;
    }

    public function generateResults()
    {
        $this->validate([
            'selectedSession' => 'required|exists:academic_sessions,id',
            'selectedClass' => 'required|exists:school_classes,id',
            'selectedSection' => 'required|exists:class_sections,id',
            'selectedExam' => 'required|exists:exams,id',
        ]);

        $this->students = Student::with('user')
            ->where('academic_session_id', $this->selectedSession)
            ->where('school_class_id', $this->selectedClass)
            ->where('class_section_id', $this->selectedSection)
            ->where('department_id', $this->selectedDepartment)
            ->where('is_active', true)
            ->orderBy('roll_number')
            ->get();
    }


    public function downloadStudentPdf($studentId, $examId, $classId, $sectionId, $sessionId)
    {
        // Create an instance of GeneratePdf component manually
        $pdfComponent = app(HighSchool::class);
        $pdfComponent->mount($studentId, $examId, $classId, $sectionId, $sessionId);
        $pdfComponent->loadReport();

        $pdf = Pdf::loadView('backend.result.high-school-pdf', [
            'student' => $pdfComponent->student,
            'exam' => $pdfComponent->exam,
            'subjects' => $pdfComponent->subjects,
            'marks' => $pdfComponent->marks,
            'fourthSubjectMarks' => $pdfComponent->fourthSubjectMarks,
            'markdistributions' => $pdfComponent->markdistributions,
            'students' => $pdfComponent->students,
        ])->setPaper('A4', 'landscape');

        $fileName = $pdfComponent->student->user['name'] . '-result.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function render()
    {
        return view('livewire.backend.result.index', [
            'departments' => Department::orderBy('id')->get(),
        ]);
    }
}
