@extends('layout.app-bdo')

@section('content')
<div class="row mt-4">
    <div class="col-xl-2 col-lg-2 col-md-4">
         <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-justified m-0" role="tablist">
            <li class="nav-item">
                <a class="nav-link  getVisor" data-toggle="tab" href="#home" role="tab" data-id="{{ auth()->id() }}">
                    <span class="d-none d-md-block">Create Activity</span><span class="d-block d-md-none">Create</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">
                    <span class="d-none d-md-block">BDO</span><span class="d-block d-md-none">BDO</span>
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane" id="home" role="tabpanel">
                <x-activity-form :lists="$lists"/>
            </div>
            <div class="tab-pane py-3 active" id="profile" role="tabpanel">
               @foreach ($bdo as $item)
                    <ul class="list-group">
                        <button class="list-group-item list-group-item-action getBDO" data-id="{{ $item->id }}" id="getBDO"><i class="far fa-user-circle mr-" style="font-size:15px"></i> {{ $item->name }}</button>
                    </ul>
               @endforeach
            </div>
            @if (auth()->user()->wrhs=='collector')
            <button class="btn btn-sm btn-secondary btn-block my-2" name="report"><i class="fas fa-print"></i> Generate Report</button>
            @endif
        </div>
       
        {{-- @include('calendar.parts.guide',['list'=>$lists]) --}}
    </div>

    <div id='calendar' 
        data-id="{{ auth()->id() }}"
        data-list="{{ route("authenticate.activity.list",['user']) }}" 
        data-update="{{ route('authenticate.activity.update',['param']) }}" 
        data-info="{{ route('authenticate.activity.info',['param']) }}" 
        class="col-xl-10 col-lg-10 col-md-8">
    </div>
    {{-- <div class="col-xl-1 col-lg-1 col-md-8">
        
        @for ($i = 0; $i < 10; $i++)
            <button class="btn btn-sm btn-secondary btn-block p-2 m-1" style="font-size: 11px">dasdasd</button>
        @endfor
    </div> --}}
</div>
<!-- end row -->

@include('calendar.view-activity',['list'=>$lists,'sttus'=>$sttus])
@include('calendar.modal-daterange-sprvsr',['bdo'=>$bdo])
@endsection

@section('js')
<script src="{{ asset('assets/js/booking.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/bdo.js') }}?v={{ time() }}"></script>
<script src="{{ asset('assets/js/supervisor.v2.js') }}?v={{ time() }}"></script>
@endsection