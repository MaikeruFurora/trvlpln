@extends('layout.app')
@section('content')
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4">
                    @foreach ($users as $key => $item)    
                    <div id="accordion">
                        <div class="card m-0 border">
                            <a data-toggle="collapse" data-parent="#accordion"
                                href="#collapseOne{{ $key }}"
                                aria-controls="collapseOne{{ $key }}" class="text-dark">
                            <div class="card-header p-2" id="headingOne{{ $key }}">
                                <h5 class="mb-0 mt-0 font-14"> <i class="fas fa-warehouse"></i> {{ $key }} </h5>
                            </div>
                            </a>

                            <div id="collapseOne{{ $key }}" class="collapse"  aria-labelledby="headingOne{{ $key }}" data-parent="#accordion">
                                <div class="card-body p-2 bg-secondary">
                                    <ul class="list-group">
                                    @foreach ($item as $val)
                                        <button class="list-group-item p-2 text-left getBDO" style="cursor: pointer" data-id="{{ $val->id }}"><i class="fas fa-user-shield mr-2"></i> {{ $val->name }}</button>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    @endforeach
                    <br>
                </div>
                <div id='calendar' 
                    data-id="{{ auth()->id() }}"
                    data-list="{{ route("authenticate.activity.list",['user']) }}" 
                    data-update="{{ route('authenticate.activity.update',['param']) }}" 
                    data-info="{{ route('authenticate.activity.info',['param']) }}" 
                    class="col-xl-10 col-lg-9 col-md-8">
                </div>
              </div>
            </div>
        </div>
    </div>
    @include('calendar.readonly')
@endsection
@section('js')
<script src="{{ asset('assets/js/activity.js') }}"></script>
<script src="{{ asset('assets/js/admin.js') }}"></script>
@endsection
