@extends('layout.app-bdo')
@section('css')
<link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-xl-2 col-lg-3 col-md-4">
        <div class="col-12">
            <x-activity-form :lists="$lists"/>
        </div>
        <div class="col-12 d-none d-md-block">
            @include('calendar.parts.guide')
        </div>
    </div>
    <div class="col-xl-10 col-lg-9 col-md-8">
        <div id='calendar' 
            data-id="{{ auth()->id() }}"
            data-list="{{ route("authenticate.activity.list",['user']) }}" 
            data-update="{{ route('authenticate.activity.update',['param']) }}" 
            data-info="{{ route('authenticate.activity.info',['param']) }}" 
            class="mt-4"></div>
    </div>
</div>
@include('calendar.view-activity',['list'=>$lists,'sttus'=>$sttus])
@endsection

@section('js')
<script src="{{ asset('assets/js/booking.js') }}?v={{ time() }}"></script>
{{-- <script src="{{ asset('assets/js/activity.v2.js') }}"></script> --}}
<script src="{{ asset('assets/js/bdo.js') }}?v={{ time() }}"></script>
@endsection