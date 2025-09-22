<?php

namespace App\Livewire\Backend\Student;

use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AdmitCardGenerator extends Component
{
    // Filters
    public $selectedSessionId;
    public $selectedExamId;
    public $selectedClassId;
    public $selectedSectionId;
    public $selectedDepartmentId;

    // Data Collections
    public Collection $academicSessions;
    public Collection $exams;
    public Collection $schoolClasses;
    public Collection $classSections;
    public Collection $departments;

    // Student Data
    public Collection $students;
    public $selectedStudents = [];
    public $selectAll = false;

    public function mount()
    {
        $this->academicSessions = AcademicSession::orderBy('id', 'desc')->get();
        $this->schoolClasses = SchoolClass::orderBy('id')->get();
        $this->departments = Department::orderBy('id')->get();

        $this->exams = new Collection();
        $this->classSections = new Collection();
        $this->students = new Collection();

        $this->selectedSessionId = AcademicSession::where('is_active', true)->value('id');
        if ($this->selectedSessionId) {
            $this->updatedSelectedSessionId($this->selectedSessionId);
        }
    }

    public function updatedSelectedSessionId($sessionId)
    {
        $this->exams = $sessionId ? Exam::where('academic_session_id', $sessionId)->get() : new Collection();
        $this->reset('selectedExamId', 'selectedClassId', 'selectedSectionId', 'selectedDepartmentId');
        $this->students = new Collection();
    }

    public function updatedSelectedClassId($classId)
    {
        $this->classSections = $classId ? ClassSection::where('school_class_id', $classId)->get() : new Collection();
        $this->reset('selectedSectionId', 'selectedDepartmentId');
        $this->filterStudents();
    }

    public function updated($name)
    {
        if (in_array($name, ['selectedExamId', 'selectedSectionId', 'selectedDepartmentId'])) {
            $this->filterStudents();
        }
    }

    public function updatedSelectAll($value)
    {
        $this->selectedStudents = $value ? $this->students->pluck('id')->map(fn($id) => (string) $id)->toArray() : [];
    }

    public function filterStudents()
    {
        if ($this->selectedExamId && $this->selectedClassId) {
            $this->students = Student::with('user')
                ->where('school_class_id', $this->selectedClassId)
                ->when($this->selectedSectionId, fn($q) => $q->where('class_section_id', $this->selectedSectionId))
                ->when($this->selectedDepartmentId, fn($q) => $q->where('department_id', $this->selectedDepartmentId))
                ->get();
        } else {
            $this->students = new Collection();
        }
        $this->reset('selectAll', 'selectedStudents');
    }

    public function generateAdmitCards()
    {
        // 1. Perform validation directly in the component.
        if (!$this->selectedExamId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Please select an exam.']);
            return;
        }

        if (empty($this->selectedStudents)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Please select at least one student.']);
            return;
        }

        // 2. Redirect to the download controller route, passing the data as query parameters.
        // The getGenerateUrlProperty() method you already have is perfect for this.
        return redirect()->to($this->generateUrl);
    }

    // The getGenerateUrlProperty() method is unchanged and still essential.
    public function getGenerateUrlProperty()
    {
        if (!$this->selectedExamId || empty($this->selectedStudents)) {
            return '#';
        }

        $params = [
            'exam_id' => $this->selectedExamId,
            'student_ids' => $this->selectedStudents,
        ];

        return route('admin.student.admit-card.generate') . '?' . http_build_query($params);
    }

    public function render()
    {
        return view('livewire.backend.student.admit-card-generator');
    }
}
