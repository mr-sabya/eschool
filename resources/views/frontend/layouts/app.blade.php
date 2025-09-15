<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://themesbazar.com/">
    <title>Home | Khalishpur Collegiate Girls' School</title>


    <link rel="icon" href="wp-content/themes/educationaltheme/images/favicon.png" type="image/x-icon" />
    <link rel="shortcut icon" href="wp-content/themes/educationaltheme/images/favicon.png" type="image/x-icon" />


    <link rel='stylesheet' id='bootstrap-css' href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' id='font-awesome-css' href="{{ asset('assets/frontend/fontawesome/css/all.min.css') }}" type='text/css'
        media='all' />

    <link rel="stylesheet" href="{{ asset('assets/frontend/fonts/remixicon.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/vendor/slick/slick.css') }}" />

    <link rel='stylesheet' id='responsive-css' href="{{ asset('assets/frontend/css/responsive.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' id='menu-css' href="{{ asset('assets/frontend/css/menu.css') }}" type='text/css' media='all' />

    <link rel='stylesheet' id='style-css' href="{{ asset('assets/frontend/css/style.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' id='style-css' href="{{ asset('assets/frontend/css/main.css') }}" type='text/css' media='all' />
    <link rel='stylesheet' id='style-css' href="{{ asset('assets/frontend/css/update.css') }}" type='text/css' media='all' />

    @livewireStyles

</head>

<body>

    <!-- header start -->
    <!-- top header start -->
    <livewire:frontend.theme.header />
    <!-- top header end -->

    <!-- menu -->
    <livewire:frontend.theme.menu />
    <!-- header end -->

    @yield('content')

    <!-- footer -->
    <livewire:frontend.theme.footer />
    <!-- footer -->




    <button id="scrollUpBtn" title="Go to top"><i class="fa fa-arrow-circle-up"
            aria-hidden="true"></i>
    </button>

    <script data-navigate-once type='text/javascript' src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script data-navigate-once type='text/javascript' src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
    <script data-navigate-once type="text/javascript" src="{{ asset('assets/frontend/vendor/slick/slick.min.js') }}"></script>

    <script type='text/javascript' src="{{ asset('assets/frontend/js/custom.js') }}"></script>

    @livewireScripts

</body>


</html>