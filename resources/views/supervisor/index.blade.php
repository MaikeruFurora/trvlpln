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
<div class="card m-2">
    <div class="card-body shadow">
       
        <div class="row mt-4">
            <div class="col-xl-3 col-lg-3 col-md-4">
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
                    </div>
                    <div class="tab-pane py-3 active" id="profile" role="tabpanel">
                       @foreach ($bdo as $item)
                            <ul class="list-group">
                                <button class="list-group-item list-group-item-action getBDO" data-id="{{ $item->id }}" id="getBDO"><i class="far fa-user-circle mr-" style="font-size:15px"></i> {{ $item->name }}</button>
                            </ul>
                       @endforeach
                    </div>
                </div>
               
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
            class="col-xl-9 col-lg-9 col-md-8"></div>
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