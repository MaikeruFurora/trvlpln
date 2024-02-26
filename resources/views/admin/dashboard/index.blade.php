@extends('layout.app')
@section('content')

<div class="col-12 mt-3">
       {{--  --}}
   <div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title mb-4">Today Activity - {{ date("F d Y")}} </h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Warehouse</th>
                                <th scope="col">Success</th>
                                <th scope="col">Failed</th>
                                <th scope="col">Pending/ No Status</th>
                                <th scope="col">Total Activity Today</th>
                                <th scope="col">Progress</th>
                                <th scope="col" class="text-center">OverAll</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_merge(...array_values($data->toArray())) as $name => $value)
                            <tr @if ($value['nostatus_count']>0) style="background-color: #f8d7da" @endif>
                                <th scope="row">{{ $name }}</th>
                                <td>{{ $value['user']['wrhs'] }}</td>
                                <td>{{ $value['success_count'] }}</td>
                                <td>{{ $value['failed_count'] }}</td>
                                <td>
                                    @if ($value['nostatus_count']>0)
                                    <span class="badge badge-danger">{{ $value['nostatus_count'] }}</span>
                                    @else
                                        {{ $value['nostatus_count'] }}
                                    @endif
                                </td>
                                <td>{{ $value['activity_count'] }}</td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        @php
                                            $successPercentage = $value['activity_count'] > 0 ? ($value['success_count'] / $value['activity_count']) * 100 : 0;
                                        @endphp
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $successPercentage }}%" aria-valuenow="{{ $successPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($value['nostatus_count']>0)
                                    <i class="fas fa-times-circle text-danger" style="font-size:18px"></i>
                                    @else
                                    <i class="fas fa-check-circle text-success" style="font-size:18px"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                           
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
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

@endsection