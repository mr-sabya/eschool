<?php

namespace App\Http\Controllers\Backend;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    //
    public function index()
    {
        // add academic session id to all students
        $students = Student::get();
        foreach ($students as $student) {
            if (!$student->academic_session_id) {
                $student->academic_session_id = AcademicSession::where('is_active', true)->first()->id ?? null;
                $student->save();
            }
        }
        return view('backend.student.index');
    }

    // create student
    public function create()
    {
        return view('backend.student.create');
    }

    // edit
    public function edit($id)
    {
        $student = Student::findOrFail(intval($id));
        return view('backend.student.edit', compact('student'));
    }


    // import students
    public function import()
    {
        return view('backend.student.import');
    }

    // download sample import file
    public function download()
    {
        $filePath = storage_path('app/public/student_import_template_with_import_column.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'Template file not found.');
        }

        return Response::download($filePath, 'student_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // admit card generate
    public function admitCardGenerate()
    {
        return view('backend.student.admit-card');
    }

    // students id card
    public function stduentIdCard()
    {
        return view('backend.tool.student-id-card');
    }
}
