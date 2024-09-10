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
        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" />
        <link href="{{ asset('plugins/timepicker/jquery.timepicker.css') }}" rel="stylesheet" />
        <link href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/alertify/css/alertify.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
        <!-- Google Fonts: Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

        @yield('css')
        <style>
            html, body{
                font-family: 'poppins', sans-serif;
                background: white
            }
            /* Fullscreen modal for mobile view */
            @media (max-width: 767.98px) { /* Adjust the max-width if needed */
              .modal-dialog {
                max-width: 100%;
                margin: 0;
                height: 100%;
                width: 100%;
              }

              .modal-content {
                height: 100%;
                border-radius: 0;
              }

              .modal-body {
                overflow-y: auto; /* Add scrolling if content is too long */
              }
            }
            /* Ensure the parent element has position relative */
            .position-relative {
                position: relative;
            }

            #suggestions_list {
                position: absolute;
                z-index: 1000;
                background-color: white;
                border: 1px solid #ccc;
                width: 100%; /* Match the width of the input */
                max-height: 200px; /* Limit the height of the list */
                overflow-y: auto; /* Scroll if the list is too long */
                list-style: none; /* Remove bullets */
                padding: 0;
                margin: 0;
                display: none; /* Hidden by default */
            }

            #suggestions_list li {
                font-size: 12px;
                padding: 5px;
                border-bottom: 1px solid #ccc;
                cursor: pointer;
                width: 100%; /* Ensure each list item has the same width */
            }

            #suggestions_list li:hover {
                background-color: #f1f1f1;
            }

            .modal-open {
                overflow: hidden;
            }

            .fc-event .fc-title {
                white-space: normal; /* Allow text wrapping */
                word-wrap: break-word; /* Ensure long words are wrapped */
                font-size: 11px; /* Adjust font size if necessary */
                padding: 1px; /* Adjust padding for readability */
            }
            .fc-time {
                display: inline-block;
            }
            .fc-event {
                min-height: 20px; /* Ensure events have enough space */
                padding: 1px; /* Adjust padding if text is being cut off */
            }
            .fc-row {
                margin-bottom: 6px; /* Reduce this value or remove it entirely */
            }
            .fc-event {
                padding: 6px; /* Reduce this value or remove it entirely */
            }
        </style>
    </head>
   
    <body>

       <!-- Loader -->
       @include('layout.loader')

       <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand bold " href="#">Beat Plan</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Report
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#" data-url="{{ route('authenticate.activity.weekly.beatplan.printout') }}">BeatPlan Weekly</a>
                    <a class="dropdown-item" href="#" data-url="{{ route('authenticate.activity.weekly.printout') }}">Weekly Report</a>
                    <a class="dropdown-item" href="#" data-url="{{ route('authenticate.activity.daily.printout') }}">Today ({{ date('l') }})</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Other Report</a>
                </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Guide</a>
            </li>
            <li class="nav-item">
              <a  class="nav-link text-danger" style="cursor: pointer"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>
              <form id="logout-form" action="{{ route('authenticate.signout') }}" method="POST" class="d-none">@csrf</form>
            </li>
          </ul>
          <span class="navbar-text">
            {{ strtoupper(auth()->user()->name) }}
          </span>
        </div>
      </nav>
        <!-- header-bg -->
        {{-- <div class="wrapper-bdo"> --}}
            <div class="mx-3">
                <div class="mt-2">
                    @yield('content')
                </div>
            </div> 
            <!-- end container-fluid -->
        {{-- </div> --}}

        <!-- end wrapper -->

        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/tooltip.min.js') }}"></script>
        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <!-- Jquery-Ui -->
        <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script src='{{ asset('plugins/fullcalendar/js/fullcalendar.min.js') }}'></script>
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
