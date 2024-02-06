<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Beat Plan</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="{{ csrf_token() }}" name="_token" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App Icons -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <link rel="stylesheet" href="{{ asset('plugins/jquery-toast/jquery.toast.css') }}">
        <!--calendar css-->
        {{-- <link href="{{ asset('plugins/fullcalendar/css/fullcalendar.min.css') }}" rel="stylesheet" /> --}}
         <link href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        @if (auth()->user()->type=='bdo')
        <link href="{{ asset('assets/css/style-custom.css') }}" rel="stylesheet" type="text/css" />
        <style>
            html, body{
                background-image: url('{{ asset("assets/images/bg.jpg") }}');background-position: 'center';background-repeat: no-repeat;background-size: 'cover';
            }
        </style>
        @else
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        @endif
        <link href="{{ asset('plugins/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" />
        <link href="{{ asset('plugins/timepicker/jquery.timepicker.css') }}" rel="stylesheet" />
        {{-- datepicker --}}
        {{-- <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"> --}}
        <link href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/alertify/css/alertify.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
        @yield('css')
    </head>

   
    <body>

        <!-- Loader -->
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </div>
        </div>

        <!-- header-bg -->
        @if (auth()->user()->type!='bdo')
            @include('layout.header')
        @endif
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    @yield('content')
                </div>
            </div> <!-- end container-fluid -->
        </div>

        <!-- end wrapper -->

        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
        <!-- Jquery-Ui -->
        <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script src='{{ asset('plugins/fullcalendar/js/fullcalendar.min.js') }}'></script>
        {{-- datepicker --}}
        {{-- <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script> --}}
        
        <script src="{{ asset('plugins/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="{{ asset('plugins/timepicker/jquery.timepicker.js') }}"></script>
        <script src="{{ asset('plugins/alertify/js/alertify.js') }}"></script>
        <script src="{{ asset('plugins/jquery-toast/jquery.toast.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/global.js') }}"></script>
        @yield('js')
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

    </body>
</html>