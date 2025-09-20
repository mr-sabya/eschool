<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ClassPositionHelper;
use App\Helpers\ResultHelper;
use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\ClassSubjectAssign;
use App\Models\Exam;
use App\Models\MarkDistribution;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\SubjectMarkDistribution;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    //
    public function index()
    {
        return view('backend.result.index');
    }

    // show
    public function show($studentId, $examId, $classId, $sectionId, $sessionId)
    {
        return view('backend.result.show', compact('studentId', 'examId', 'classId', 'sectionId', 'sessionId'));
    }

    // high school result
    public function highSchool($studentId, $examId, $classId, $sectionId, $sessionId)
    {
        return view('backend.result.high-school', compact('studentId', 'examId', 'classId', 'sectionId', 'sessionId'));
    }

    // generate PDF
    public function generatePdf()
    {
        return view('backend.result.generate');
    }


    // dhownload PDF
    public function download($studentId, $examId)
    {
        // Load student with relations
        $student = Student::with(['schoolClass', 'classSection'])->findOrFail($studentId);

        // Load the exam
        $exam = Exam::findOrFail($examId);

        // Students of the same class & section
        $students = Student::where('school_class_id', $student->school_class_id)
            ->where('class_section_id', $student->class_section_id)
            ->get();

        // Assigned subjects for student's class, section & session
        $subjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', $student->academic_session_id)
            ->where('school_class_id', $student->school_class_id)
            ->where('class_section_id', $student->class_section_id)
            ->get();

        $subjectIds = $subjects->pluck('subject_id')->toArray();

        // Load mark distributions
        $markdistributions = SubjectMarkDistribution::with('markDistribution')
            ->where('school_class_id', $student->school_class_id)
            ->where('class_section_id', $student->class_section_id)
            ->whereIn('subject_id', $subjectIds)
            ->get()
            ->unique(fn($item) => $item->markDistribution->name)
            ->values();

        if ($student->schoolClass['numeric_name'] >= 6) {

            // return view('backend.result.high-school-pdf', [
            //     'student' => $student,
            //     'exam' => $exam,
            //     'students' => $students,
            //     'subjects' => $subjects,
            //     'markdistributions' => $markdistributions,
            // ]);
            // Generate PDF
            $pdf = Pdf::loadView('backend.result.high-school-pdf', [
                'student' => $student,
                'exam' => $exam,
                'students' => $students,
                'subjects' => $subjects,
                'markdistributions' => $markdistributions,
            ])->setPaper('A6', 'landscape');
        } else {

            // Generate PDF
            $pdf = Pdf::loadView('backend.result.pdf', [
                'student' => $student,
                'exam' => $exam,
                'students' => $students,
                'subjects' => $subjects,
                'markdistributions' => $markdistributions,
            ])->setPaper('A6', 'landscape');
        }

        // Return as download
        $fileName = $student->user->name . '-result.pdf';
        return $pdf->download($fileName);
    }


    // show result position
    public function showResultPosition()
    {


        // Step 2: Fetch the students based on the validated input
        $students = Student::where('academic_session_id', 1)
            ->where('school_class_id', 6)
            ->where('class_section_id', 6)
            ->get();

        // Step 3: Handle the case where no students are found
        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'No students found to generate a tabulation sheet.');
        }

        // Step 6: Fetch related data (subjects, mark distributions, etc.)
        $subjects = ClassSubjectAssign::with('subject')
            ->where('academic_session_id', 1)
            ->where('school_class_id', 6)
            ->where('class_section_id', 6)
            ->get();

        $markDistributions = MarkDistribution::whereHas('subjectMarkDistributions', fn($q) => $q->where('school_class_id', 6))
            ->orderBy('id')->get();

        // Step 5: Process the results using your helper
        $results = ClassPositionHelper::getTabulationSheetResults($students, 1, $markDistributions);

        // --- DYNAMIC PAPER SIZE LOGIC (This remains the same) ---
        $subjectsCount = $subjects->count();
        $paperSize = 'a6'; // Default size

        if ($subjectsCount > 7 && $subjectsCount <= 10) {
            $paperSize = 'legal';
        } elseif ($subjectsCount > 10) {
            $paperSize = 'a3';
        }
        // --- END OF LOGIC ---

        // Step 6: Gather all data needed for the PDF view
        $resultsCollection = collect($results);
        $exam = Exam::find(1);
        $class = SchoolClass::find(6);
        $section = ClassSection::find(6);
        $session = AcademicSession::find(1);

        $totalStudents = $students->count();
        $totalPass = $resultsCollection->where('is_fail', false)->count();
        $passPercentage = $totalStudents > 0 ? number_format(($totalPass / $totalStudents) * 100, 2) : 0;
        $gradeCounts = $resultsCollection->pluck('final_grade')->countBy();
        $highestMark = $resultsCollection->max('total_marks');

        // Step 7: Generate the PDF
        return view('backend.result.tabulation-sheet-pdf', [
            'results' => $results,
            'subjects' => $subjects,
            'markDistributions' => $markDistributions,
            'exam' => $exam,
            'class' => $class,
            'section' => $section,
            'session' => $session,
            'department' => 'null', // Assuming no department for now
            'gradeCounts' => $gradeCounts,
            'totalStudents' => $totalStudents,
            'totalPass' => $totalPass,
            'totalFail' => $totalStudents - $totalPass,
            'passPercentage' => $passPercentage,
            'highestMark' => $highestMark,
        ]);

        // Step 8: Prepare the filename and stream the download
        $departmentName = 'null'; // Assuming no department for now
        $fileName = "Tabulation-Sheet-{$class->name}-{$section->name}-{$departmentName}.pdf";

        return $pdf->stream($fileName);
        // An alternative for direct download: return $pdf->download($fileName);
    }

    // tabulation index
    public function tabulationIndex()
    {
        return view('backend.result.tabulation.index');
    }


    // tabulation sheet
    public function tabulationSheet()
    {
        return view('backend.result.tabulation.high-school');
    }
}
