<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') &dash; CMS</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
        type="text/css">
    <link href="/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
    <link href="/css/layout.min.css" rel="stylesheet" type="text/css">
    <link href="/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="/css/colors.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="/global_assets/js/main/jquery.min.js"></script>
    <script src="/global_assets/js/main/bootstrap.bundle.min.js"></script>
    <script src="/global_assets/js/plugins/loaders/blockui.min.js"></script>
    <script src="{{asset('global_assets/js/plugins/notifications/pnotify.min.js')}}"></script>
    <script type="text/javascript" src="/global_assets/js/plugins/notifications/sweet_alert.min.js"></script>
    <script type="text/javascript" src="/custom/form.js"></script>
    <script type="text/javascript" src="/custom/number.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme CSS --->
    @yield('styles')
    <!-- /Theme css -->

    <!-- Theme js --->
    <script src="/js/theme/app.js"></script>
    @yield('js')
    <script>
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
    <!-- /Theme JS -->
</head>

<body class="navbar-top">
    @include('layouts.partials.navbar')

    <div class="page-content">
        @include('layouts.partials.sidebar')
        <div class="content-wrapper">
            @yield('content')
            @include('layouts.partials.footer')
        </div>

    </div>

    <audio id="success-audio">
        <source src="/audio/success.ogg" type="audio/ogg">
        <source src="/audio/success.mp3" type="audio/mpeg">
    </audio>
    <audio id="error-audio">
        <source src="/audio/error.ogg" type="audio/ogg">
        <source src="/audio/error.mp3" type="audio/mpeg">
    </audio>
    <audio id="warning-audio">
        <source src="/audio/warning.ogg" type="audio/ogg">
        <source src="/audio/warning.mp3" type="audio/mpeg">
    </audio>
    <script type="text/javascript" src="/global_assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
    @yield('scripts')
    @stack('javascript')
    <script type="text/javascript" src="/custom/dira.js"></script>
</body>

</html>