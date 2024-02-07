@extends('layout.app')
@section('css')
<link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="card m-2">
    <div class="card-header p-1">
        <ul class="list-group"  data-toggle="tooltip" data-placement="top" title="Tooltip on top">
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
                    <div class="form-group mb-3">
                        <small class="mb-0 label-text" for="">Activity</small>
                        <select name="activity" id="" class="custom-select custom-select-sm" required>
                            <option value=""></option>
                            @foreach ($lists as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row mb-1">
                        <div class="form-group col-lg-8 col-md-8 col-sm-12">
                            <small class="mb-0 label-text" for="">Date & time from</small>
                            <input type="text" class="form-control form-control-sm datepicker" name="date_from" required/>
                        </div>
                        <div class="form-group col-lg-4 col-md-4 col-sm-12">
                            <small class="mb-0 label-text" for="">Time to</small>
                            <input type="text"palceholder="Time to" name="time_to" class="form-control form-control-sm timepicker">
                        </div>
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