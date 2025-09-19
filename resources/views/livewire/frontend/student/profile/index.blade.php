<div class="container my-5">

    <div class="d-flex" id="wrapper">
        <!-- Sidebar Navigation -->
        <div class="sidebar-wrapper" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4">
                <h5 class="mt-2 mb-0">Student Portal</h5>
            </div>
            <div class="list-group list-group-flush my-3">
                <a href="#" class="list-group-item list-group-item-action active"><i class="fas fa-tachometer-alt me-3"></i>Dashboard</a>
                <a href="#" class="list-group-item list-group-item-action"><i class="fas fa-poll-h me-3"></i>Result</a>
                <a href="#" class="list-group-item list-group-item-action"><i class="fas fa-user-check me-3"></i>Attendance</a>
                <a href="#" class="list-group-item list-group-item-action"><i class="fas fa-book-open me-3"></i>Library</a>
                <a href="#" class="list-group-item list-group-item-action"><i class="fas fa-money-bill-wave me-3"></i>Fees</a>
            </div>
        </div>

        <!-- Main Content -->
        <div id="page-content-wrapper">
            <!-- Top Navigation Bar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4 d-flex justify-content-between w-100">
                <div class="d-flex align-items-center">
                    <h2 class="fs-2 m-0">Dashboard</h2>
                </div>

                <div>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">Update Profile</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#passwordModal">Change Password</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="container-fluid px-4">
                <!-- CONTENT FOR EACH PAGE GOES HERE -->

                <!-- Welcome Header -->
                <div class="mb-4">
                    <h3>Welcome Back, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted">Here is your academic summary for today, Friday, 19 September 2025.</p>
                </div>


                <!-- Quick Stats Cards -->
                <div class="row g-4 mb-4">
                    <!-- Today's Classes -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon bg-primary text-white me-3">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">4</h5>
                                    <p class="card-text text-muted mb-0">Today's Classes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Upcoming Assignments -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon bg-warning text-white me-3">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">3</h5>
                                    <p class="card-text text-muted mb-0">Upcoming Assignments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Overall Attendance -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon bg-success text-white me-3">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">92%</h5>
                                    <p class="card-text text-muted mb-0">Overall Attendance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Recent Notifications -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="stats-icon bg-danger text-white me-3">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">2 New</h5>
                                    <p class="card-text text-muted mb-0">Notifications</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row my-5">
                    <h3 class="fs-4 mb-3">Recent Information</h3>
                    <!-- Placeholder for tables or other info blocks -->
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Latest Result Publication</h5>
                            </div>
                            <div class="card-body">
                                <p>The results for the Half-Yearly Examination 2025 have been published. Please visit the 'Result' section to view your detailed report card.</p>
                                <a href="#" class="btn btn-primary">View Results</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- Update Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel"><i class="fas fa-user-edit me-2"></i>Update Your Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="studentName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="studentName" value="[Student Name]">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="studentEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="studentEmail" value="[student.email@example.com]">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="studentPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="studentPhone" value="[Student Phone]">
                        </div>
                        <div class="mb-3">
                            <label for="studentAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="studentAddress" rows="3">[Student Address]</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Change Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel"><i class="fas fa-key me-2"></i>Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"><i class="fas fa-sync-alt me-2"></i>Update Password</button>
                </div>
            </div>
        </div>
    </div>

</div>