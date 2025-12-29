<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Dashboard | {{ $settings->school_name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/backend/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Include snow Theme -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('assets/backend/libs/toastr/build/toastr.min.css') }}">

    <!-- seelct2 -->
    <link rel="stylesheet" href="{{ asset('assets/backend/libs/select2/css/select2.min.css') }}">

    <!-- quill text editor -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    <!-- App Css-->
    <link href="{{ asset('assets/backend/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/backend/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    @livewireStyles

</head>

<body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="light"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <livewire:backend.theme.header />

        <!-- ========== Left Sidebar Start ========== -->
        <livewire:backend.theme.sidebar />
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div wire:poll.5s>
                    @if(isset($notification->data['type']) && $notification->data['type'] === 'zip_progress')
                    <div class="alert alert-info">
                        {{ $notification->data['message'] }}
                        ({{ $notification->data['percentage'] }}%)
                    </div>
                    @endif
                </div>


                <!-- start page title -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Dashboard</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        @if(Route::is('admin.dashboard'))
                                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                        @else
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                                        @endif
                                        <li class="breadcrumb-item active">@yield('title')</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->


                @yield('content')

            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            © Appzia.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesdesign
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- JAVASCRIPT -->
    <script data-navigate-once src="{{ asset('assets/backend/libs/jquery/jquery.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/metismenu/metisMenu.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/simplebar/simplebar.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/node-waves/waves.min.js') }}"></script>

    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <!-- toastr plugin -->
    <script data-navigate-once src="{{ asset('assets/backend/libs/toastr/build/toastr.min.js') }}"></script>

    <!-- seelct2 -->
    <script data-navigate-once src="{{ asset('assets/backend/libs/select2/js/select2.min.js') }}"></script>

    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <!-- App js -->
    <script src="{{ asset('assets/backend/js/app.js') }}"></script>


    @stack('scripts')

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                // Since the event is an array, access the first item
                const {
                    type,
                    message
                } = event[0];

                // Check if 'type' and 'message' are defined
                if (type === undefined || message === undefined) {
                    console.error('Error: type or message is undefined!');
                } else {
                    // Show toastr notification based on the type
                    if (type === 'success') {
                        toastr.success(message);
                    } else if (type === 'info') {
                        toastr.info(message);
                    } else if (type === 'warning') {
                        toastr.warning(message);
                    } else if (type === 'error') {
                        toastr.error(message);
                    }
                }

            });
        });
    </script>
</body>


</html>