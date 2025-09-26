<?php

namespace App\Livewire\Backend\Home;

use App\Models\Student;
use App\Models\User;
use App\Models\FeeCollection;
use App\Models\Income;
use App\Models\Expense;
use App\Models\DailyAttendance;
use Livewire\Component;
use Carbon\Carbon;

class Index extends Component
{
    // For the top summary cards
    public $totalStudents;
    public $totalTeachers;
    public $totalGuardians;
    public $todaysCollection;

    // For the charts
    public $incomeExpenseData = [];
    public $monthlyAdmissionsData = [];

    // For the table
    public $recentlyAdmittedStudents;

    // For the attendance summary
    public $attendanceSummary = [];

    public function mount()
    {
        $this->loadSummaryCardData();
        $this->loadChartData();
        $this->loadRecentStudents();
        $this->loadAttendanceSummary();
    }

    public function loadSummaryCardData()
    {
        // Card 1: Total Active Students
        $this->totalStudents = Student::where('is_active', 1)->count();

        // Card 2: Total Active Teachers
        $this->totalTeachers = User::where('role', 'teacher')->where('status', 1)->count();

        // Card 3: Total Active Guardians
        $this->totalGuardians = User::where('is_parent', 1)->where('status', 1)->count();

        // Card 4: Today's Total Collection
        $feeCollections = FeeCollection::whereDate('payment_date', today())->sum('amount_paid');
        $otherIncomes = Income::whereDate('date', today())->sum('amount');
        $this->todaysCollection = $feeCollections + $otherIncomes;
    }

    public function loadChartData()
    {
        // Data for Income vs Expense Bar Chart (Last 6 Months)
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');

            $income = FeeCollection::whereYear('payment_date', $month->year)->whereMonth('payment_date', $month->month)->sum('amount_paid')
                + Income::whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('amount');

            $expense = Expense::whereYear('date', $month->year)->whereMonth('date', $month->month)->sum('amount');

            $this->incomeExpenseData[] = [
                'month' => $monthName,
                'income' => round($income, 2),
                'expense' => round($expense, 2),
            ];
        }

        // Data for New Admissions Area Chart (This Year)
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('F');
            $admissions = Student::whereYear('admission_date', now()->year)->whereMonth('admission_date', $i)->count();

            $this->monthlyAdmissionsData[] = [
                'month' => $monthName,
                'admissions' => $admissions,
            ];
        }
    }

    public function loadRecentStudents()
    {
        // Table of recently admitted students
        $this->recentlyAdmittedStudents = Student::with(['user', 'schoolClass', 'classSection'])
            ->latest('admission_date')
            ->take(7) // Get the latest 7 students
            ->get();
    }

    public function loadAttendanceSummary()
    {
        // Attendance stats for today
        $totalPresent = DailyAttendance::whereDate('attendance_date', today())->where('status', 'present')->count();
        $totalAbsent = DailyAttendance::whereDate('attendance_date', today())->where('status', 'absent')->count();
        $totalLate = DailyAttendance::whereDate('attendance_date', today())->where('status', 'late')->count();
        $totalStudentsToday = $totalPresent + $totalAbsent + $totalLate;

        $this->attendanceSummary = [
            'present' => $totalPresent,
            'absent' => $totalAbsent,
            'late' => $totalLate,
            'percentage' => $totalStudentsToday > 0 ? round(($totalPresent / $totalStudentsToday) * 100) : 0,
        ];
    }

    public function render()
    {
        return view('livewire.backend.home.index');
    }
}
