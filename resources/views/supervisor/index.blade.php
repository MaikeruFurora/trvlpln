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
</style>
<div class="card mt-3">
    <div class="card-body shadow">
       
        <div class="row mt-4">
            <div class="col-xl-2 col-lg-2 col-md-4">
                 <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link  getVisor" data-toggle="tab" href="#home" role="tab" data-id="{{ auth()->id() }}">
                            <span class="d-none d-md-block">Create Activity</span><span class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span> 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">
                            <span class="d-none d-md-block">My BDO</span><span class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane  p-1" id="home" role="tabpanel">
                        <x-activity-form :lists="$lists"/>
                    </div>
                    <div class="tab-pane py-3 active" id="profile" role="tabpanel">
                       @foreach ($bdo as $item)
                            <ul class="list-group">
                                <button class="list-group-item list-group-item-action getBDO" data-id="{{ $item->id }}" id="getBDO"><i class="far fa-user-circle mr-" style="font-size:15px"></i> {{ $item->name }}</button>
                            </ul>
                       @endforeach
                    </div>
                </div>
               
                @include('calendar.parts.guide',['list'=>$lists])
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
      
    </div>
</div>

@include('calendar.view-activity',['list'=>$lists,'sttus'=>$sttus])
@endsection

@section('js')
<script src="{{ asset('assets/js/activity.js') }}"></script>
<script src="{{ asset('assets/js/supervisor.js') }}"></script>
@endsection