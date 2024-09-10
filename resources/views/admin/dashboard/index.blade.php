@extends('layout.app')
@section('content')

<div class="col-12">
       {{--  --}}
   <div class="row">
    <div class="col-xl-12">
        <x-dashboard.today-activity :dataTable="$data"/>
        <x-dashboard.week-activity  :dataWeekTable="$dataWeekTable"/>
    </div>
    </div>
   {{--  --}}
    <h6>Date: {{ date("F d Y")}} (Today)</h6>
   <div class="row">
    {{--  --}}
    <div class="col-12">
        <div class="card-columns">
            @foreach ($data as $key => $item)
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-3">{{ $key }}</h4>
                    <div class="latest-massage">
                        @foreach ($item as $name => $value)
                        <a href="#" class="latest-message-list">
                            <div class="border-bottom mt-3 position-relative">
                                <div class="float-left user mr-3">
                                    <h5 class="bg-info text-center rounded-circle text-white mt-0">{{ $name[0] }}</h5>
                                </div>
                                <div class="message-time">
                                    <p class="m-0 font-weight-bold text-dark">{{ $value['activity_count'] }} Activities</p>
                                </div>
                                <div class="massage-desc">
                                    <h5 class="font-14 mt-0 text-dark">{{ $name }}</h5>
                                    <p class="text-muted mb-0"><i class="fas fa-check-circle text-success"></i> Success: <b>{{ $value['success_count'] }}</b></p>
                                    <p class="text-muted mb-0"><i class="fas fa-times-circle text-danger"></i> Failed: <b>{{ $value['failed_count'] }}</b></p>
                                    <p class="text-muted"><i class="fas fa-minus-circle"></i> No Status: <b>{{ $value['nostatus_count'] }}</b></p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    {{--  --}}

   </div>

</div>



<div class="row">

    <div class="col-4">

    </div>
    <div class="col-8">
        
    </div>
</div>
@endsection