@extends('layout.app-bdo')
@section('content')
    <div class="row mt-3">
        <!-- Left Form Section -->
        <div class="col-lg-2 col-md-2 col-sm-12 mb-2">
            <div class="form-section">
                <x-activity-form :lists="$lists"/>
            </div>
        </div>
        <!-- Right FullCalendar Section -->
        <div class="col-lg-8 col-md-8 col-sm-12 mb-2">
            <div class="calendar-section">
                <div id='calendar' 
                data-id="{{ auth()->id() }}"
                data-list="{{ route("authenticate.activity.list",['user']) }}" 
                data-update="{{ route('authenticate.activity.update',['param']) }}" 
                data-info="{{ route('authenticate.activity.info',['param']) }}"></div>
            </div>
        </div>
        <!-- Additional Content Section -->
        <div class="col-lg-2 col-md-2 col-sm-12">
            @include('calendar.parts.guide',['list'=>$lists])
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Officia incidunt maxime, exercitationem labore aut blanditiis? Recusandae harum eum nihil totam ipsa doloribus, nobis cupiditate quas praesentium in laborum ut ab!</p>
            @include('calendar.view-activity',['list'=>$lists,'sttus'=>$sttus])
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/activity.js') }}"></script>
    <script src="{{ asset('assets/js/map.js') }}"></script>
@endsection