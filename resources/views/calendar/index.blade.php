@extends('layout.app')

@section('content')
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

</style>
<div class="card m-2">
    <div class="card-header p-1">
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ strtoupper(auth()->user()->name) }}
                <a  class="badge text-danger badge-pill"style="cursor:pointer;font-size: 15px"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-power-off"></i></a>
                <form id="logout-form" action="{{ route('authenticate.signout') }}" method="POST" class="d-none">@csrf</form>
              </li>
        {{-- <div class="row list-group-item d-flex justify-content-between align-items-center">
            <div class="col-lg-4 col-md-4 col-sm-10"> --}}
               
               {{-- <small class="pl-2" style="font-size: 15px">BDO: <b>{{ strtoupper(auth()->user()->name) }}</b></small> --}}
            {{-- </div>
            <div class="col-lg-4 col-md-4 col-sm-2">
               <div class="float-right">
                
            </div>
        </div> --}}
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
                <div class="card m-b-30 border shadow-sm">
                    <div class="card-body">
                        {{-- <h4 class="card-title font-16 mt-0">Color Legend</h4> --}}
                        <p class="card-text"><i class="fas fa-palette"></i> Beat plan color guide</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item py-2 text-white" style="background: #74daae;font-weight:bold;font-size:12px"><em>Success</em></li>
                        <li class="list-group-item py-2 text-white" style="background: #e56874;font-weight:bold;font-size:12px"><em>Failed</em></li>
                        <li class="list-group-item py-2 text-white" style="background: #fecb74;font-weight:bold;font-size:12px"><em>Re-schedule</em></li>
                    </ul>
                </div>
            </div>

            <div id='calendar' 
            data-id="{{ auth()->id() }}"
            data-list="{{ route("authenticate.activity.list",['user']) }}" 
            data-update="{{ route('authenticate.activity.update',['param']) }}" 
            data-info="{{ route('authenticate.activity.info',['param']) }}" 
            class="col-xl-10 col-lg-9 col-md-8"></div>
        </div>
        <!-- end row -->
    </div>
    <div class="card-footer m-0 p-0">
        <small class="ml-2 bold">version 1.2.0</small>
    </div>
</div>

@include('calendar.view-activity',['list'=>$lists,'sttus'=>$sttus])
@endsection

@section('js')
<script src="{{ asset('assets/js/activity.js') }}"></script>
@endsection