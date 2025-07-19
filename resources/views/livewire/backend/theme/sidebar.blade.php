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
                        ['url' => 'admin.gender.index', 'label' => 'Gender'],
                        ['url' => 'admin.blood.index', 'label' => 'Blood Group'],
                        ['url' => 'admin.religion.index', 'label' => 'Religions'],

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

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>