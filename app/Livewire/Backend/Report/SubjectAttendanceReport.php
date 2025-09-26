<?php

namespace App\Livewire\Backend\Report;

use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\ClassSubjectAssign;
use App\Models\Student;
use App\Models\SubjectAttendance;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Carbon\Carbon;

class SubjectAttendanceReport extends Component
{
    // Properties for filters
    public Collection $academicSessions;
    public Collection $schoolClasses;
    public Collection $classSections;
    public Collection $departments;
    public Collection $subjects;
    public Collection $students;

    public $selectedAcademicSessionId = null;
    public $selectedSchoolClassId = null;
    public $selectedClassSectionId = null;
    public $selectedDepartmentId = null;
    public $selectedSubjectId = null;
    public $selectedStudentId = null;

    public $month;
    public $showDepartmentFilter = false;

    // Properties for the report itself
    public $attendanceData = [];
    public $summary = [];
    public $selectedStudent = null;
    public $selectedSubject = null;

    public function mount()
    {
        $this->academicSessions = AcademicSession::where('is_active', 1)->orderBy('name')->get();
        $this->schoolClasses = SchoolClass::orderBy('id')->get();

        $this->classSections = new Collection();
        $this->departments = new Collection();
        $this->subjects = new Collection();
        $this->students = new Collection();

        $this->month = now()->format('Y-m');
        $this->resetReport();
    }

    public function handleAcademicSessionChange()
    {
        $this->reset('selectedSchoolClassId', 'selectedClassSectionId', 'selectedDepartmentId', 'selectedSubjectId', 'selectedStudentId');
        $this->classSections = new Collection();
        $this->departments = new Collection();
        $this->subjects = new Collection();
        $this->students = new Collection();
        $this->resetReport();
    }

    public function handleSchoolClassChange()
    {
        $this->reset('selectedClassSectionId', 'selectedDepartmentId', 'selectedSubjectId', 'selectedStudentId');
        $this->classSections = new Collection();
        $this->departments = new Collection();
        $this->subjects = new Collection();
        $this->students = new Collection();
        $this->resetReport();

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

    public function handleFilterChange()
    {
        $this->reset('selectedSubjectId', 'selectedStudentId');
        $this->subjects = new Collection();
        $this->students = new Collection();
        $this->filterSubjectsAndStudents();
        $this->resetReport();
    }

    public function handleReportGeneration()
    {
        $this->generateReport();
    }

    private function filterSubjectsAndStudents()
    {
        if (!$this->selectedAcademicSessionId || !$this->selectedSchoolClassId || !$this->selectedClassSectionId) {
            return;
        }

        $subjectQuery = ClassSubjectAssign::query()->with('subject')
            ->where('academic_session_id', $this->selectedAcademicSessionId)
            ->where('school_class_id', $this->selectedSchoolClassId)
            ->where('class_section_id', $this->selectedClassSectionId);

        if ($this->showDepartmentFilter && $this->selectedDepartmentId) {
            $subjectQuery->where('department_id', $this->selectedDepartmentId);
        }

        $assigned = $subjectQuery->get();

        // ** THE FIX IS HERE **
        // 1. Pluck returns a Support\Collection.
        // 2. We convert it back into an Eloquent\Collection to match the property type.
        $pluckedSubjects = $assigned->pluck('subject')->whereNotNull()->unique('id')->sortBy('name');
        $this->subjects = new Collection($pluckedSubjects); // Convert to Eloquent Collection

        $this->filterStudents();
    }

    private function filterStudents()
    {
        $query = Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.is_active', 1)
            ->where('students.school_class_id', $this->selectedSchoolClassId)
            ->where('students.class_section_id', $this->selectedClassSectionId);

        if ($this->showDepartmentFilter && $this->selectedDepartmentId) {
            $query->where('students.department_id', $this->selectedDepartmentId);
        }

        $this->students = $query->orderBy('users.name', 'asc')
            ->select('students.*', 'users.name as user_name')
            ->get();
    }

    public function generateReport()
    {
        if (!$this->selectedStudentId || !$this->selectedSubjectId) {
            $this->resetReport();
            return;
        }

        $this->selectedStudent = $this->students->find($this->selectedStudentId);
        $this->selectedSubject = $this->subjects->find($this->selectedSubjectId);

        $attendances = SubjectAttendance::where('student_id', $this->selectedStudentId)
            ->where('subject_id', $this->selectedSubjectId)
            ->whereYear('attendance_date', Carbon::parse($this->month)->year)
            ->whereMonth('attendance_date', Carbon::parse($this->month)->month)
            ->get()->keyBy(fn($item) => Carbon::parse($item->attendance_date)->format('j'));

        $this->prepareCalendarData($attendances);
    }

    private function prepareCalendarData($attendances)
    {
        $this->reset('attendanceData', 'summary');
        $this->summary = ['present' => 0, 'absent' => 0, 'late' => 0, 'holiday' => 0, 'half_day' => 0, 'percentage' => 0];

        $date = Carbon::parse($this->month);
        $daysInMonth = $date->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            if ($attendances->has($day)) {
                $status = $attendances[$day]->status;
                $this->attendanceData[$day] = $status;
                match (strtolower($status)) {
                    'present' => $this->summary['present']++,
                    'absent' => $this->summary['absent']++,
                    'late' => $this->summary['late']++,
                    'holiday' => $this->summary['holiday']++,
                    'half day' => $this->summary['half_day']++,
                    default => null,
                };
            } else {
                $this->attendanceData[$day] = 'N/A';
            }
        }

        $totalSchoolDays = $this->summary['present'] + $this->summary['absent'] + $this->summary['late'] + $this->summary['half_day'];
        $this->summary['percentage'] = $totalSchoolDays > 0 ? round((($this->summary['present'] + $this->summary['late']) / $totalSchoolDays) * 100, 2) : 0;
    }

    public function resetReport()
    {
        $this->reset(['selectedStudent', 'selectedSubject', 'attendanceData']);
        $this->summary = ['present' => 0, 'absent' => 0, 'late' => 0, 'holiday' => 0, 'half_day' => 0, 'percentage' => 0];
    }

    public function render()
    {
        return view('livewire.backend.report.subject-attendance-report');
    }
}
