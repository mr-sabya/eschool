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

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>