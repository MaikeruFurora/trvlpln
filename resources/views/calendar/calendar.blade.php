<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>BeatPlan</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="{{ csrf_token() }}" name="_token" />
        <!-- App Icons -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('plugins/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" />
        <link href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/alertify/css/alertify.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/style-custom.css') }}" rel="stylesheet" type="text/css" />
        
        <style>
            .fc-title{
                font-size: .9em;
            }
            .label-text{
                font-size: 12px
            }
            .buttons{
                background: red;
            }
            /* Default styles for FullCalendar */
            #calendar {
              margin: 0 auto;
            }
        
            /* Media query for mobile devices */
            @media (max-width: 600px) {
              #calendar {
                max-width: 100%;
                padding: 0 10px;
              }
            }
        
        
            /* Loading spinner styles */
            .loading-spinner {
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              z-index: 9999;
              background-color: rgba(255, 255, 255, 0.8);
              border-radius: 5px;
              padding: 10px;
            }

            .account-pages .main-card {
                max-height: 100vh; /* Viewport Height */
                overflow: auto; /* Enable scrolling if content is larger than the screen */
            }
        
        </style>
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

        <div class="account-pages">

            <div class="card m-2 main-card">
                <div class="card-header p-1">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ strtoupper(auth()->user()->name) }}
                            <a  class="badge text-danger badge-pill"style="cursor:pointer;font-size: 15px"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-power-off"></i></a>
                            <form id="logout-form" action="{{ route('authenticate.signout') }}" method="POST" class="d-none">@csrf</form>
                        </li>
                    </ul>
                </div>
                <div class="card-body shadow">
            
                    <div class="row">
                        <div class="col-xl-2 col-lg-3 col-md-4">
                            <h3 class="m-t-5 m-b-15 font-14 border-bottom lead">Create Activity</h3>
                            <form id="Activity" autocomplete="off" class="m-t-5 m-b-20" action="{{ route('authenticate.activity.store') }}">@csrf
                                <input type="hidden" name="id">
                                <div class="form-group mb-2">
                                    <small class="mb-0 label-text" for="">Activity</small>
                                    <select name="activity" id="" class="custom-select custom-select-sm" required>
                                        <option value=""></option>
                                        @foreach ($lists as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <small class="mb-0 label-text" for="">Date</small>
                                    <input type="text" class="form-control form-control-sm datepicker" name="date_from" required/>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" name="week" id="applydisWeek">
                                    <small class="form-check-label label-text" for="applydisWeek">Apply this week.</small>
                                </div>
                                
                                {{-- <div class="form-group mb-2">
                                    <small class="mb-0 label-text" for="">Date & time </small>
                                    <input type="text" class="form-control form-control-sm datepicker" name="date_to"/>
                                </div> --}}
                                <div class="form-group mb-2">
                                    <small class="mb-0 label-text" for="">Client</small>
                                    <textarea class="form-control form-control-sm text-uppercase" id="" rows="3" name="client" maxlength="100" required></textarea>
                                </div> 
                                <button type="submit" class="btn btn-block btn-secondary mb-3">Save</button>
                            </form>
                            @include('calendar.parts.guide')
                        </div>
            
                        <div id='calendar' 
                            data-id="{{ auth()->id() }}"
                            data-list="{{ route("authenticate.activity.list",['user']) }}" 
                            data-update="{{ route('authenticate.activity.update',['param']) }}" 
                            data-info="{{ route('authenticate.activity.info',['param']) }}" 
                            class="col-xl-10 col-lg-9 col-md-8">
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <div class="card-footer m-0 p-0">
                    <small class="ml-2 bold">version 1.2.0</small>
                </div>
            </div>
            
            @include('calendar.view-activity',['list'=>$lists,'sttus'=>$sttus])

        </div>




        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.j') }}s"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script src='{{ asset('plugins/fullcalendar/js/fullcalendar.min.js') }}'></script>
        <script src="{{ asset('plugins/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="{{ asset('plugins/alertify/js/alertify.js') }}"></script>
        <script src="{{ asset('plugins/jquery-toast/jquery.toast.js') }}"></script>
        <script src="{{ asset('plugins/moment/moment.js') }}"></script>
        <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/global.js') }}"></script>
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script src="{{ asset('assets/js/activity.js') }}"></script>

    </body>
</html>