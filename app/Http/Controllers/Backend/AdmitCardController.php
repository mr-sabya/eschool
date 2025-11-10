<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamRoutine;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdmitCardController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $exam = Exam::with('academicSession')->findOrFail($request->exam_id);

        $students = Student::with(['user', 'schoolClass', 'classSection', 'department'])
            ->whereIn('id', $request->student_ids)
            ->get();

        // Fetch all possible routines for the given exam and the classes of the selected students.
        // This is more efficient than querying for each student individually.
        $classIds = $students->pluck('school_class_id')->unique();
        $examRoutines = ExamRoutine::with(['subject', 'timeSlot', 'classRoom'])
            ->where('exam_id', $exam->id)
            ->whereIn('school_class_id', $classIds)
            ->orderBy('exam_date')
            ->get();

        // return view('backend.student.admit-card-pdf', compact('exam', 'students', 'examRoutines'));

        $pdf = Pdf::loadView('backend.student.admit-card-pdf', compact('exam', 'students', 'examRoutines'))->setPaper('a4', 'landscape');

        return $pdf->download('admit-cards-' . $exam->name . '-' . now()->format('Y-m-d') . '.pdf');
    }
}
