<?php

namespace App\Livewire\Backend\Result;

use Livewire\Component;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use App\Models\{
    Student,
    Exam,
    SchoolClass,
    ClassSection,
    ClassSubjectAssign,
    SubjectMarkDistribution
};
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class GeneratePdf extends Component
{
    public $school_class_id;
    public $class_section_id;
    public $exam_id;

    public $classes = [];
    public $sections = [];

    public function mount()
    {
        $this->classes = SchoolClass::all();
        $this->sections = [];
    }

    public function loadSections($classId)
    {
        $this->sections = ClassSection::where('school_class_id', $classId)->get();
        $this->class_section_id = null; // reset section
    }

    public function downloadPdf()
    {
        ini_set('max_execution_time', 600); // 10 minutes
        set_time_limit(600);

        $this->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        $exam = Exam::findOrFail($this->exam_id);

        $students = Student::with(['user', 'schoolClass', 'classSection'])
            ->where('school_class_id', $this->school_class_id)
            ->where('class_section_id', $this->class_section_id)
            ->get();

        $className = SchoolClass::find($this->school_class_id)->name ?? '';
        $sectionName = ClassSection::find($this->class_section_id)->name ?? '';
        $examName = $exam->name ?? '';

        $zipFileName = $className . '-' . $sectionName . '-' . $examName . '-class-results.zip';
        $zip = new ZipArchive;
        $zipPath = storage_path($zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

            foreach ($students as $student) {
                $subjects = ClassSubjectAssign::with('subject')
                    ->where('academic_session_id', $student->academic_session_id)
                    ->where('school_class_id', $student->school_class_id)
                    ->where('class_section_id', $student->class_section_id)
                    ->get();

                $subjectIds = $subjects->pluck('subject_id')->toArray();

                $markdistributions = SubjectMarkDistribution::with('markDistribution')
                    ->where('school_class_id', $student->school_class_id)
                    ->where('class_section_id', $student->class_section_id)
                    ->whereIn('subject_id', $subjectIds)
                    ->get()
                    ->unique(fn($item) => $item->markDistribution->name)
                    ->values();

                $className = SchoolClass::find($this->school_class_id)->name ?? '';
                $sectionName = ClassSection::find($this->class_section_id)->name ?? '';

                $pdf = FacadePdf::loadView('backend.result.pdf', [
                    'exam' => $exam,
                    'className' => $className,
                    'sectionName' => $sectionName,
                    'student' => $student,
                    'students' => $students,
                    'subjects' => $subjects,
                    'markdistributions' => $markdistributions,
                ])->setPaper('A4', 'landscape');

                // Add PDF to ZIP
                $zip->addFromString($student->user->name . '-class-result.pdf', $pdf->output());
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.backend.result.generate-pdf', [
            'exams' => Exam::all(),
        ]);
    }
}
