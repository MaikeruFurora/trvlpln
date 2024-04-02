@extends('layout.app-bdo')
@section('css')
<link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="card m-1 mb-0 shadow-lg border">
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
    <div class="card-body shadow ">

        <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-4">
               <div class="text-center"><img src="{{ asset('assets/images/logo-bg-1.png') }}" height="60" alt="logo"></div>
                {{-- <h3 class="m-t-5 m-b-15 font-14 border-bottom lead">Create Activity</h3> --}}
               <x-activity-form :lists="$lists"/>
                @include('calendar.parts.guide',['list'=>$lists])
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