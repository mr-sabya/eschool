<div class="container-fluid">
    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-title text-muted">Total Students</h4>
                    <h2 class="mt-3 mb-2"><i class="ri-team-line text-primary me-2"></i><b>{{ $totalStudents }}</b></h2>
                    <p class="text-muted mb-0 mt-3">Active students in school</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-title text-muted">Total Teachers</h4>
                    <h2 class="mt-3 mb-2"><i class="ri-briefcase-4-line text-info me-2"></i><b>{{ $totalTeachers }}</b></h2>
                    <p class="text-muted mb-0 mt-3">Active teachers on payroll</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-title text-muted">Total Guardians</h4>
                    <h2 class="mt-3 mb-2"><i class="ri-parent-line text-success me-2"></i><b>{{ $totalGuardians }}</b></h2>
                    <p class="text-muted mb-0 mt-3">Registered guardians</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-title text-muted">Today's Collection</h4>
                    <h2 class="mt-3 mb-2"><i class="ri-money-dollar-circle-line text-warning me-2"></i><b>৳{{ number_format($todaysCollection, 2) }}</b></h2>
                    <p class="text-muted mb-0 mt-3">Fees & Other Incomes</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    {{-- Charts --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 card-title">Monthly Income vs. Expense</h4>
                    {{-- wire:ignore is crucial here to prevent Livewire from interfering with the JS chart library --}}
                    <div wire:ignore>
                        <div id="morris-bar-example" style="height: 300px"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-0 card-title">New Admissions This Year</h4>
                    <div wire:ignore>
                        <div id="morris-area-example" style="height: 300px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    {{-- Recent Students and Attendance --}}
    <div class="row">
        <div class="col-lg-7">
            <div class="card card-h-100">
                <div class="card-body">
                    <h4 class="mb-4 mt-0 card-title">Recently Admitted Students</h4>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Admission Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentlyAdmittedStudents as $student)
                                <tr>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->schoolClass->name }}</td>
                                    <td>{{ $student->classSection->name }}</td>
                                    <td> {{ optional($student->admission_date)->format('d M, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No recent admissions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-h-100">
                <div class="card-body">
                    <h4 class="mb-4 mt-0 card-title">Attendance Summary (Today)</h4>
                    <p class="font-600 mb-1">Attendance Percentage <span class="text-primary float-end"><b>{{ $attendanceSummary['percentage'] }}%</b></span></p>
                    <div class="progress mb-3">
                        <div class="progress-bar progress-bar-primary" role="progressbar" style="width: {{ $attendanceSummary['percentage'] }}%;"></div>
                    </div>

                    <p class="font-600 mb-1">Present Students <span class="text-success float-end"><b>{{ $attendanceSummary['present'] }}</b></span></p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;"></div>
                    </div>

                    <p class="font-600 mb-1">Absent Students <span class="text-danger float-end"><b>{{ $attendanceSummary['absent'] }}</b></span></p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;"></div>
                    </div>

                    <p class="font-600 mb-1">Late Comers <span class="text-warning float-end"><b>{{ $attendanceSummary['late'] }}</b></span></p>
                    <div class="progress mb-0">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

</div>

{{-- Make sure you have included the Morris Charts library in your main app.blade.php layout --}}
<script>
    document.addEventListener('livewire:load', function() {
        // Bar Chart: Income vs Expense
        let incomeExpenseData = @json($incomeExpenseData);
        if ($('#morris-bar-example').length) {
            Morris.Bar({
                element: 'morris-bar-example',
                data: incomeExpenseData,
                xkey: 'month',
                ykeys: ['income', 'expense'],
                labels: ['Income', 'Expense'],
                barColors: ['#34c38f', '#f46a6a'],
                gridLineColor: '#eef0f2',
                resize: true
            });
        }

        // Area Chart: Monthly Admissions
        let monthlyAdmissionsData = @json($monthlyAdmissionsData);
        if ($('#morris-area-example').length) {
            Morris.Area({
                element: 'morris-area-example',
                data: monthlyAdmissionsData,
                xkey: 'month',
                ykeys: ['admissions'],
                labels: ['Admissions'],
                pointSize: 3,
                fillOpacity: 0.4,
                pointStrokeColors: ['#556ee6'],
                behaveLikeLine: true,
                gridLineColor: '#eef0f2',
                lineWidth: 3,
                hideHover: 'auto',
                lineColors: ['#556ee6'],
                resize: true
            });
        }
    });
</script>