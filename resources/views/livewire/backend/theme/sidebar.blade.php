<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <livewire:backend.theme.menu-item
                    :url="'admin.dashboard'"
                    :icon="'ri-dashboard-line'"
                    :label="'Dashboard'"
                    :hasSubMenu="false" />


                <livewire:backend.theme.menu-item
                    :url="''"
                    :icon="'ri-wallet-line'"
                    :label="'Academic'"
                    :hasSubMenu="true"
                    :subMenuItems="
                    [
                        ['url' => 'admin.class.index', 'label' => 'Class'],
                        ['url' => 'admin.section.index', 'label' => 'Section'],
                        ['url' => 'admin.shift.index', 'label' => 'Shift'],
                        ['url' => 'admin.subject.index', 'label' => 'Subject'],
                        ['url' => 'admin.classroom.index', 'label' => 'Classroom'],
                        ['url' => 'admin.academic-session.index', 'label' => 'Academic Session'],

                    ]" />

                <livewire:backend.theme.menu-item
                    :url="''"
                    :icon="'ri-wallet-line'"
                    :label="'App Setting'"
                    :hasSubMenu="true"
                    :subMenuItems="
                    [
                        ['url' => 'admin.designation.index', 'label' => 'Designation'],
                        ['url' => 'admin.department.index', 'label' => 'Department'],
                        ['url' => 'admin.gender.index', 'label' => 'Gender'],
                        ['url' => 'admin.blood.index', 'label' => 'Blood Group'],
                        ['url' => 'admin.religion.index', 'label' => 'Religions'],

                    ]" />

                <livewire:backend.theme.menu-item
                    :url="''"
                    :icon="'ri-wallet-line'"
                    :label="'Students'"
                    :hasSubMenu="true"
                    :subMenuItems="
                    [
                        ['url' => 'admin.student.index', 'label' => 'Students List'],
                        ['url' => 'admin.student.create', 'label' => 'Add New Student'],

                    ]" />
                <livewire:backend.theme.menu-item
                    :url="''"
                    :icon="'ri-wallet-line'"
                    :label="'Guardians'"
                    :hasSubMenu="true"
                    :subMenuItems="
                    [
                        ['url' => 'admin.guardian.index', 'label' => 'Guardians List'],
                        ['url' => 'admin.guardian.create', 'label' => 'Add New Guardian'],

                    ]" />
                <livewire:backend.theme.menu-item
                    :url="''"
                    :icon="'ri-wallet-line'"
                    :label="'Staffs'"
                    :hasSubMenu="true"
                    :subMenuItems="
                    [
                        ['url' => 'admin.staff.index', 'label' => 'Staff List'],
                        ['url' => 'admin.staff.create', 'label' => 'Add New Staff'],

                    ]" />
                <livewire:backend.theme.menu-item
                    :url="''"
                    :icon="'ri-wallet-line'"
                    :label="'Exams'"
                    :hasSubMenu="true"
                    :subMenuItems="
                    [
                        ['url' => 'admin.exam-category.index', 'label' => 'Exam Categories'],
                        ['url' => 'admin.exam.index', 'label' => 'Exams'],
                        ['url' => 'admin.mark-distribution.index', 'label' => 'Mark Distribution'],
                        ['url' => 'admin.subject-mark-distribution.index', 'label' => 'Suject Mark Distribution'],

                    ]" />

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>