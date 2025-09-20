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
        // Use the currently selected filters from the component's public properties
        $pdfComponent = app(Show::class);
        $pdfComponent->mount(
            $studentId,
            $this->selectedExam,
            $this->selectedClass,
            $this->selectedSection,
            $this->selectedSession
        );
        $pdfComponent->loadReport();

        // Ensure student data was loaded before proceeding
        if (!$pdfComponent->student) {
            // Optionally, flash a message to the user
            session()->flash('error', 'Could not generate PDF. Student data not found.');
            return;
        }

        $pdf = Pdf::loadView('backend.result.pdf', [
            'student' => $pdfComponent->student,
            'exam' => $pdfComponent->exam,
            'subjects' => $pdfComponent->subjects,
            'marks' => $pdfComponent->marks,
            'fourthSubjectMarks' => $pdfComponent->fourthSubjectMarks,
            'markdistributions' => $pdfComponent->markdistributions,
            'students' => $pdfComponent->students,
        ])->setPaper('a4', 'landscape');

        $fileName = $pdfComponent->student->user['name'] . '-result.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
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



    // vvv ADD THIS ENTIRE NEW FUNCTION vvv
    public function downloadTabulationSheetPdf()
    {
        if ($this->students->isEmpty()) {
            session()->flash('error', 'No students found to generate a tabulation sheet.');
            return;
        }

        

        // Fetch the subjects list first, as we need to count them.
        $subjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', $this->selectedSession)
            ->where('school_class_id', $this->selectedClass)
            ->where('class_section_id', $this->selectedSection)
            ->when($this->selectedDepartment, fn($q) => $q->where('department_id', $this->selectedDepartment))
            ->get();

        $markDistributions = MarkDistribution::whereHas('subjectMarkDistributions', fn($q) => $q->where('school_class_id', $this->selectedClass))
            ->orderBy('id')->get();

        // Call the helper to get the results
        $results = ClassPositionHelper::getTabulationSheetResults($this->students, $this->selectedExam, $markDistributions);

        // --- DYNAMIC PAPER SIZE LOGIC ---
        $subjectsCount = $subjects->count();
        $paperSize = 'a4'; // Default size

        // Define your thresholds here. These numbers are just examples.
        if ($subjectsCount > 7 && $subjectsCount <= 10) {
            $paperSize = 'legal'; // Use Legal for a medium number of subjects
        } elseif ($subjectsCount > 10) {
            $paperSize = 'a3'; // Use A3 for a large number of subjects
        }
        // --- END OF LOGIC ---

        // Fetch all the other necessary data for the header
        $resultsCollection = collect($results);
        $exam = Exam::find($this->selectedExam);
        $class = SchoolClass::find($this->selectedClass);
        // ... (rest of your data fetching remains the same)
        $section = ClassSection::find($this->selectedSection);
        $session = AcademicSession::find($this->selectedSession);
        $department = $this->selectedDepartment ? Department::find($this->selectedDepartment) : null;
        $totalStudents = $this->students->count();
        $totalPass = $resultsCollection->where('is_fail', false)->count();
        $passPercentage = $totalStudents > 0 ? number_format(($totalPass / $totalStudents) * 100, 2) : 0;
        $gradeCounts = $resultsCollection->pluck('final_grade')->countBy();
        $highestMark = $resultsCollection->max('total_marks');

        

        $pdf = Pdf::loadView('backend.result.tabulation-sheet-pdf', [
            'results' => $results,
            'subjects' => $subjects, // Pass the subjects you already fetched
            'markDistributions' => $markDistributions,
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
        ])->setPaper($paperSize, 'landscape'); // <-- Use the dynamic $paperSize variable

        // ... (rest of the function for filename and download remains the same) ...
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
