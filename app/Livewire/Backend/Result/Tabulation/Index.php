<?php

namespace App\Livewire\Backend\Result\Tabulation;

use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use App\Helpers\ClassPositionHelper;
use App\Helpers\SchoolHighestMarkHelper;
use App\Livewire\Backend\Result\HighSchool;
use App\Models\ClassSubjectAssign;
use App\Models\SubjectMarkDistribution;

class Index extends Component
{
    public $academicSessions;
    public $classes;
    public $sections = [];
    public $exams = [];
    public $studentMarks = [];
    public $studentResults = [];

    public $selectedSession = null;
    public $selectedClass = null;
    public $selectedSection = null;
    public $selectedExam = null;
    public $selectedDepartment = null;
    public $tabulationData = [];

    public $students;
    public $subjects;
    public $exam;

    public function mount()
    {
        $this->academicSessions = AcademicSession::orderBy('start_date', 'desc')->get();
        $this->classes = SchoolClass::orderBy('id')->get();
        $this->students = collect();
        $this->subjects = collect();
    }

    public function loadExamsForSession()
    {
        $this->selectedExam = null;
        $this->students = collect();
        $this->subjects = collect();

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
            $this->subjects = collect();
        } else {
            $this->sections = [];
            $this->selectedSection = null;
            $this->students = collect();
            $this->subjects = collect();
        }
        $this->selectedDepartment = null;
    }

    public function generateTabulation()
    {
        // Get students
        $students = Student::with('user')
            ->where('school_class_id', $this->selectedClass)
            ->where('class_section_id', $this->selectedSection)
            ->get();

        // Get subjects (ClassSubjectAssign)
        $subjects = ClassSubjectAssign::with('subject')
            ->where('school_class_id', $this->selectedClass)
            ->where('class_section_id', $this->selectedSection)
            ->get();

        // Get mark distributions with related student marks
        $markDistributions = SubjectMarkDistribution::with(['markDistribution'])
            ->where('school_class_id', $this->selectedClass)
            ->where('class_section_id', $this->selectedSection)
            ->get();

        // Initialize tabulation data
        $this->tabulationData = [];

        $exam = Exam::findOrFail($this->selectedExam);

        // Loop through students and subjects
        foreach ($students as $student) {
            foreach ($subjects as $subjectAssign) {
                $marks = HighSchool::getSubjectResultsStatic(
                    $student,
                    $exam,            // Pass the Exam model
                    $subjectAssign,   // Pass the ClassSubjectAssign
                    $students,        // All students in this class/section
                    $markDistributions // All mark distributions for this class/section
                );

                $this->tabulationData[$student->id][$subjectAssign->subject_id] = $marks;
            }
        }
    }



    public function downloadTabulationPdf()
    {
        $this->generateTabulation(); // ensure data is loaded

        $pdf = Pdf::loadView('backend.result.tabulation-pdf', [
            'students'   => $this->students,
            'subjects'   => $this->subjects,
            'exam'       => $this->exam,
            'classId'    => $this->selectedClass,
            'sectionId'  => $this->selectedSection,
        ])->setPaper('a4', 'landscape');

        $fileName = "tabulation-sheet-{$this->exam->examCategory->name}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function render()
    {
        return view('livewire.backend.result.tabulation.index', [
            'departments' => Department::orderBy('id')->get(),
        ]);
    }
}
