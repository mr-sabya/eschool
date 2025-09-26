<?php

namespace App\Livewire\Backend\Tool;

use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\Student;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class StudentIdCard extends Component
{
    // Properties for filters
    public Collection $academicSessions;
    public Collection $schoolClasses;
    public Collection $classSections;
    public Collection $departments;

    public $generationType = 'individual';
    public $searchQuery = '';

    public $selectedAcademicSessionId = null;
    public $selectedSchoolClassId = null;
    public $selectedClassSectionId = null;
    public $selectedDepartmentId = null;
    public $selectedStudentId = null;

    public $showDepartmentFilter = false;

    // Properties for results
    public Collection $searchableStudents;
    public Collection $studentsToPrint;
    public $schoolSettings;
    public $validUntil;

    public function mount()
    {
        $this->academicSessions = AcademicSession::where('is_active', 1)->orderBy('name')->get();
        $this->schoolClasses = SchoolClass::orderBy('id')->get();
        $this->schoolSettings = Setting::first();

        $this->classSections = new Collection();
        $this->departments = new Collection();
        $this->searchableStudents = new Collection();
        $this->studentsToPrint = new Collection();

        $this->validUntil = now()->year . '-12-31';
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) > 2) {
            $this->searchableStudents = Student::with('user')
                ->whereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('roll_number', 'like', '%' . $this->searchQuery . '%')
                ->limit(10)
                ->get();
        } else {
            $this->searchableStudents = new Collection();
        }
    }

    public function handleSessionChange()
    {
        $this->reset('selectedSchoolClassId', 'selectedClassSectionId', 'selectedDepartmentId');
        $this->classSections = new Collection();
        $this->departments = new Collection();
    }

    public function handleSchoolClassChange()
    {
        $this->reset('selectedClassSectionId', 'selectedDepartmentId');
        $this->classSections = new Collection();
        $this->departments = new Collection();

        if ($this->selectedSchoolClassId) {
            $this->classSections = ClassSection::where('school_class_id', $this->selectedSchoolClassId)->get();
            $className = $this->schoolClasses->find($this->selectedSchoolClassId)->name;
            if (in_array($className, ['Nine', 'Ten', 'Eleven', 'Twelve'])) {
                $this->showDepartmentFilter = true;
                $this->departments = Department::all();
            } else {
                $this->showDepartmentFilter = false;
            }
        }
    }

    public function generateCards()
    {
        $this->validate([
            'validUntil' => 'required|date_format:Y-m-d',
        ]);

        $query = Student::query()->with(['user', 'schoolClass', 'classSection', 'guardian']);

        if ($this->generationType === 'individual') {
            $this->validate(['selectedStudentId' => 'required|exists:students,id']);

            // ** THE FIX IS HERE: Specify the table name for the 'id' column **
            $query->where('students.id', $this->selectedStudentId);
        } else { // Bulk generation
            $this->validate([
                'selectedAcademicSessionId' => 'required',
                'selectedSchoolClassId' => 'required',
                'selectedClassSectionId' => 'required',
            ]);

            $query->where('academic_session_id', $this->selectedAcademicSessionId)
                ->where('school_class_id', $this->selectedSchoolClassId)
                ->where('class_section_id', $this->selectedClassSectionId);

            if ($this->showDepartmentFilter && $this->selectedDepartmentId) {
                $query->where('department_id', $this->selectedDepartmentId);
            }
        }

        $this->studentsToPrint = $query->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('students.*') // Also good practice to select from a specific table
            ->get();
    }

    public function render()
    {
        return view('livewire.backend.tool.student-id-card');
    }
}
