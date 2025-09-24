<?php

namespace App\Livewire\Backend\Result;

use App\Helpers\ClassPositionHelper;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use App\Jobs\GenerateResultZip; // <-- ADD THIS
use App\Models\ClassSubjectAssign;
use App\Models\MarkDistribution;
use Illuminate\Support\Facades\Auth; // <-- ADD THIS

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
    public $jobStatusMessage = '';

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


    public function downloadStudentPdf($studentId)
    {
        // Use a fresh instance of the Show component to avoid state issues
        $pdfComponent = new Show();

        // Manually mount the component with the required data
        $pdfComponent->mount(
            $studentId,
            $this->selectedExam,
            $this->selectedClass,
            $this->selectedSection,
            $this->selectedSession
        );

        // Run the main calculation logic
        $pdfComponent->loadReport();

        // Ensure student data was loaded before proceeding
        if (!$pdfComponent->student) {
            session()->flash('error', 'Could not generate PDF. Student data not found.');
            // It's better to redirect back or handle the error gracefully
            return redirect()->back();
        }

        // ============================ FIX IS HERE ============================
        // Add the missing $hasClassTest and $hasOtherMarks variables to the data array.
        $dataForPdf = [
            'student' => $pdfComponent->student,
            'exam' => $pdfComponent->exam,
            'subjects' => $pdfComponent->subjects,
            'marks' => $pdfComponent->marks,
            'fourthSubjectMarks' => $pdfComponent->fourthSubjectMarks,
            'markdistributions' => $pdfComponent->markdistributions,
            'students' => $pdfComponent->students,
            'classPosition' => $pdfComponent->classPosition ?? 0, // Pass the position safely

            // --- ADD THESE TWO LINES ---
            'hasClassTest' => $pdfComponent->hasClassTest,
            'hasOtherMarks' => $pdfComponent->hasOtherMarks,
        ];
        // ============================ END OF FIX ============================

        // Load the view with the complete data
        $pdf = Pdf::loadView('backend.result.pdf', $dataForPdf)
            ->setPaper('a4', 'landscape'); // Changed to portrait, which is more common for report cards

        // Sanitize the student name for the filename
        $safeStudentName = preg_replace('/[^A-Za-z0-9\-]/', '_', $pdfComponent->student->user['name']);
        $fileName = $safeStudentName . '-result.pdf';

        // Use streamDownload for a clean download response
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output(); // Use output() when streaming
        }, $fileName);
    }


    // vvv ADD THIS ENTIRE NEW FUNCTION vvv
    public function downloadAllPdfs()
    {
        if ($this->students->isEmpty()) {
            $this->jobStatusMessage = 'No students found to generate reports for.';
            return;
        }

        // Get the IDs of the students to process
        $studentIds = $this->students->pluck('id')->toArray();

        // Dispatch the job to the queue
        GenerateResultZip::dispatch(
            $studentIds,
            $this->selectedExam,
            $this->selectedClass,
            $this->selectedSection,
            $this->selectedSession,
            Auth::user() // Pass the currently logged-in user
        );

        // Provide immediate feedback to the user
        // Reset the status message after dispatching
        $this->jobStatusMessage = 'Your request has been received! The zip file is being generated in the background. You will be notified here when it is ready for download.';
    }


    // vvv ADD THIS ENTIRE NEW FUNCTION vvv
    public function downloadSummaryPdf()
    {
        if ($this->students->isEmpty()) {
            session()->flash('error', 'No students found to generate a summary.');
            return;
        }

        // Get results and the summary using your helper
        $results = ClassPositionHelper::getClassResults($this->students, $this->selectedExam);
        $summary = ClassPositionHelper::getResultSummary($results);

        // Get additional data for the PDF header
        $exam = Exam::find($this->selectedExam);
        $class = SchoolClass::find($this->selectedClass);
        $section = ClassSection::find($this->selectedSection);
        $session = AcademicSession::find($this->selectedSession);

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('backend.result.summary-pdf', [
            'summary' => $summary,
            'exam' => $exam,
            'class' => $class,
            'section' => $section,
            'session' => $session,
        ])->setPaper('a4', 'portrait');

        // Create a filename and stream the download
        $fileName = "Result-Summary-{$class->name}-{$section->name}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }



    // vvv ADD THIS ENTIRE NEW FUNCTION vvv
    public function downloadMeritListPdf()
    {
        if ($this->students->isEmpty()) {
            session()->flash('error', 'No students found to generate a merit list.');
            return;
        }

        // 1. Get the processed and sorted results from your helper
        $results = ClassPositionHelper::getClassResults($this->students, $this->selectedExam);

        // 2. Get additional data for the PDF header
        $exam = Exam::find($this->selectedExam);
        $class = SchoolClass::find($this->selectedClass);
        $section = ClassSection::find($this->selectedSection);
        $session = AcademicSession::find($this->selectedSession);
        $department = $this->selectedDepartment ? Department::find($this->selectedDepartment) : null;


        // 3. Load the view and generate the PDF
        $pdf = Pdf::loadView('backend.result.merit-list-pdf', [
            'results' => $results,
            'exam' => $exam,
            'class' => $class,
            'section' => $section,
            'session' => $session,
            'department' => $department,
        ])->setPaper('a4', 'portrait'); // Or 'landscape' if needed

        // 4. Create a filename and stream the download
        $fileName = "Merit-List-{$class->name}-{$section->name}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }



    public function downloadTabulationSheetPdf()
    {
        if (empty($this->students)) {
            session()->flash('error', 'No students found to generate a tabulation sheet.');
            return;
        }

        // Call the helper to get the calculated results.
        $results = ClassPositionHelper::getTabulationSheetResults($this->students, $this->selectedExam);

        // Fetch the exam and its allowed distributions. This is passed to the PDF view to build the headers.
        $exam = Exam::with('markDistributionTypes')->find($this->selectedExam);
        $markDistributions = $exam ? $exam->markDistributionTypes : collect();

        // Fetch the subjects list for the headers.
        $subjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', $this->selectedSession)
            ->where('school_class_id', $this->selectedClass)
            ->where('class_section_id', $this->selectedSection)
            ->when($this->selectedDepartment, fn($q) => $q->where('department_id', $this->selectedDepartment))
            ->get();

        // --- DYNAMIC PAPER SIZE LOGIC ---
        $subjectsCount = $subjects->count();
        $paperSize = 'a4'; // Default size
        if ($subjectsCount > 7 && $subjectsCount <= 10) {
            $paperSize = 'legal';
        } elseif ($subjectsCount > 10) {
            $paperSize = 'a3';
        }

        // Fetch all other necessary data for the header
        $resultsCollection = collect($results);
        $class = SchoolClass::find($this->selectedClass);
        $section = ClassSection::find($this->selectedSection);
        $session = AcademicSession::find($this->selectedSession);
        $department = $this->selectedDepartment ? Department::find($this->selectedDepartment) : null;
        $totalStudents = count($this->students);
        $totalPass = $resultsCollection->where('is_fail', false)->count();
        $passPercentage = $totalStudents > 0 ? number_format(($totalPass / $totalStudents) * 100, 2) : 0;
        $gradeCounts = $resultsCollection->pluck('final_grade')->countBy();
        $highestMark = $resultsCollection->max('total_marks');

        $pdf = Pdf::loadView('backend.result.tabulation-sheet-pdf', [
            'results' => $results,
            'subjects' => $subjects,
            'markDistributions' => $markDistributions, // Pass the allowed distributions
            'exam' => $exam,
            'class' => $class,
            'section' => $section,
            'session' => $session,
            'department' => $department,
            'gradeCounts' => $gradeCounts,
            'totalStudents' => $totalStudents,
            'totalPass' => $totalPass,
            'totalFail' => $totalStudents - $totalPass,
            'passPercentage' => $passPercentage,
            'highestMark' => $highestMark,
            'subjectsCount' => $subjectsCount,
        ])->setPaper($paperSize, 'landscape');

        $departmentName = $department ? "-{$department->name}" : '';
        $fileName = "Tabulation-Sheet-{$class->name}-{$section->name}{$departmentName}.pdf";

        return response()->streamDownload(fn() => print($pdf->output()), $fileName);
    }

    public function render()
    {
        return view('livewire.backend.result.index', [
            'departments' => Department::orderBy('id')->get(),
        ]);
    }
}
