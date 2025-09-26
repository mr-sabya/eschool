<!doctype html>
<html lang="en">

<head>

    <!-- developed by : Sabya Roy -->
    <!-- contact developer : https://www.facebook.com/sabya.info/ -->

    <meta charset="utf-8" />
    <title>@yield('title') | {{ $settings->school_name ?? 'Your School Name'}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Team Track" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/backend/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/backend/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/backend/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/backend/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    @livewireStyles
</head>

<body class="auth-body-bg auth">
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mt-0 mb-3">
                    <livewire:backend.theme.logo />
                </h3>
                <h5 class="text-center mt-0 text-color"><b>Sign In</b></h5>
                @yield('content')
            </div>
        </div>
    </div>



    <!-- JAVASCRIPT -->
    <script data-navigate-once src="{{ asset('assets/backend/libs/jquery/jquery.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/metismenu/metisMenu.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/simplebar/simplebar.min.js') }}"></script>
    <script data-navigate-once src="{{ asset('assets/backend/libs/node-waves/waves.min.js') }}"></script>

    <script data-navigate-once src="{{ asset('assets/backend/js/app.js') }}"></script>
    @livewireScripts


    <!-- developed by : Sabya Roy -->
    <!-- contact developer : https://www.facebook.com/sabya.info/ -->

</body>

</html>