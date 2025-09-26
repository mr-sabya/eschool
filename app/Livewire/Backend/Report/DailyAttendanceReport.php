<?php

namespace App\Livewire\Backend\Report;

use App\Models\SchoolClass;
use App\Models\ClassSection;
use App\Models\Department;
use App\Models\Student;
use App\Models\DailyAttendance;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Carbon\Carbon;

class DailyAttendanceReport extends Component
{
    // Properties for filters
    public Collection $schoolClasses;
    public Collection $classSections;
    public Collection $departments;
    public Collection $students;

    public $selectedSchoolClassId = null;
    public $selectedClassSectionId = null;
    public $selectedDepartmentId = null;
    public $selectedStudentId = null;

    public $month;
    public $showDepartmentFilter = false;

    // Properties for the report itself
    public $attendanceData = [];
    public $summary = [];
    public $selectedStudent = null;

    public function mount()
    {
        $this->schoolClasses = SchoolClass::orderBy('id')->get();
        $this->classSections = new Collection();
        $this->departments = new Collection();
        $this->students = new Collection();
        $this->month = now()->format('Y-m');
        $this->resetReport();
    }

    public function handleSchoolClassChange()
    {
        $this->reset('selectedClassSectionId', 'selectedDepartmentId', 'selectedStudentId');
        $this->classSections = new Collection();
        $this->departments = new Collection();
        $this->students = new Collection();
        $this->resetReport();

        // This is the logic that decides if the Department filter should be shown
        if ($this->selectedSchoolClassId) {
            $this->classSections = ClassSection::where('school_class_id', $this->selectedSchoolClassId)->get();

            $className = $this->schoolClasses->find($this->selectedSchoolClassId)->name;
            if (in_array($className, ['Nine', 'Ten', 'Eleven', 'Twelve'])) {
                $this->showDepartmentFilter = true;
                $this->departments = Department::all(); // Populates the departments
            } else {
                $this->showDepartmentFilter = false;
            }
        }
    }

    public function handleFilterChange()
    {
        $this->reset('selectedStudentId');
        $this->students = new Collection();
        $this->filterStudents();
        $this->resetReport();
    }

    public function handleReportGeneration()
    {
        $this->generateReport();
    }

    private function filterStudents()
    {
        if (!$this->selectedSchoolClassId || !$this->selectedClassSectionId) {
            return;
        }

        $query = Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.is_active', 1)
            ->where('students.school_class_id', $this->selectedSchoolClassId)
            ->where('students.class_section_id', $this->selectedClassSectionId);

        // This is the logic that applies the department filter to the query
        if ($this->showDepartmentFilter && $this->selectedDepartmentId) {
            $query->where('students.department_id', $this->selectedDepartmentId);
        }

        $this->students = $query->orderBy('users.name', 'asc')
            ->select('students.*', 'users.name as user_name')
            ->get();
    }

    public function generateReport()
    {
        if (!$this->selectedStudentId) {
            $this->resetReport();
            return;
        }

        $this->selectedStudent = $this->students->find($this->selectedStudentId);

        $attendances = DailyAttendance::where('student_id', $this->selectedStudentId)
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
        $this->reset(['selectedStudent', 'attendanceData']);
        $this->summary = ['present' => 0, 'absent' => 0, 'late' => 0, 'holiday' => 0, 'half_day' => 0, 'percentage' => 0];
    }

    public function render()
    {
        return view('livewire.backend.report.daily-attendance-report');
    }
}
