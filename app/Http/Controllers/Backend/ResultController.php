<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\ClassPositionHelper;
use App\Helpers\ResultHelper;
use App\Http\Controllers\Controller;
use App\Models\ClassSubjectAssign;
use App\Models\Exam;
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
            ])->setPaper('A4', 'landscape');
        } else {

            // Generate PDF
            $pdf = Pdf::loadView('backend.result.pdf', [
                'student' => $student,
                'exam' => $exam,
                'students' => $students,
                'subjects' => $subjects,
                'markdistributions' => $markdistributions,
            ])->setPaper('A4', 'landscape');
        }

        // Return as download
        $fileName = $student->user->name . '-result.pdf';
        return $pdf->download($fileName);
    }


    // show result position
    public function showResultPosition()
    {
        $students = Student::with(['schoolClass', 'classSection'])
            ->where('school_class_id', 7)
            ->where('class_section_id', 7)
            ->where('department_id', 1)
            ->get();

        $examId = 1; // Example exam ID, replace with actual logic to get the exam ID

        $resuls = ClassPositionHelper::getClassResults($students, $examId);

        return $resuls;
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
