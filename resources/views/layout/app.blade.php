<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <title>Beat Plan</title>
        <meta content="Beat Plan Arvin Internationl Marketing Inc" name="Beat Plan is calendar Schedule of Sales Officer that provides Marketing and Sales services to our clients in the Philippines" />
        <meta content="{{ csrf_token() }}" name="_token" />
        <meta content="ThemeDesign" name="author" />
        <!-- App Icons -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <link rel="stylesheet" href="{{ asset('plugins/jquery-toast/jquery.toast.css') }}">
        <!--calendar css-->
        <!-- Google Fonts: Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" />
        <link href="{{ asset('plugins/timepicker/jquery.timepicker.css') }}" rel="stylesheet" />
        <link href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/alertify/css/alertify.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
        <style>
            html, body{
                font-family: 'poppins', sans-serif;
            }
        </style>
        @yield('css')
    </head>

   
    <body>

        <!-- Loader -->
        @include('layout.loader')
        <!-- header-bg -->
        @include('layout.header')
            {{-- <div class="container-fluid"> --}}
            <div class="mx-3">
                <div class="mt-2">
                    @yield('content')
                </div>
            </div>
             <!-- end container-fluid -->
        </div>

        <!-- end wrapper -->

        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/tooltip.min.js') }}"></script> --}}
        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <!-- Jquery-Ui -->
        {{-- <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script> --}}
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script src='{{ asset('plugins/fullcalendar/js/fullcalendar.min.js') }}'></script>
        <script src="{{ asset('plugins/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="{{ asset('plugins/timepicker/jquery.timepicker.js') }}"></script>
        <script src="{{ asset('plugins/alertify/js/alertify.js') }}"></script>
        <script src="{{ asset('plugins/jquery-toast/jquery.toast.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/global.js') }}?v={{ time() }}"></script>
        @yield('js')
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

    </body>
</html>
