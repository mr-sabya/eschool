<?php

namespace App\Livewire\Backend\Student;

use App\Imports\StudentsImport;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\SchoolClass;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    public $import_file;
    public $import_class_id;
    public $import_section_id;

    public function importStudents()
    {
        $this->validate([
            'import_file' => 'required|file|mimes:xlsx,csv|max:10240',
            'import_class_id' => 'required|exists:school_classes,id',
            'import_section_id' => 'nullable|exists:class_sections,id',
        ]);

        $sessionId = AcademicSession::current()->id ?? null;

        Excel::import(new StudentsImport(
            $this->import_class_id,
            $this->import_section_id,
            $sessionId
        ), $this->import_file);

        $this->reset(['import_file']);
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Students imported successfully!']);
        $this->dispatch('student-saved');
    }

    public function render()
    {
        return view('livewire.backend.student.import', [
            'classes' => SchoolClass::all(),
            'sections' => ClassSection::all(),
        ]);
    }
}
