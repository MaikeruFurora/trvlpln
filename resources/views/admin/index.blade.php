@extends('layout.app')
@section('content')
<div class="card">
    <div class="card-body">
            <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-12">
                @foreach ($users as $key => $item)    
                <div id="accordion">
                    <div class="card m-0 border">
                        <a data-toggle="collapse" data-parent="#accordion"
                            href="#collapseOne{{ $key }}"
                            aria-controls="collapseOne{{ $key }}" class="text-dark">
                        <div class="card-header p-2" id="headingOne{{ $key }}">
                            <h5 class="mb-0 mt-0 font-14"> <i class="fas fa-warehouse mr-2"></i> {{ $key }}</h5>
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
                class="col-xl-10 col-lg-10 col-md-9 col-sm-12">
            </div>
        </div>
    </div>
</div>
    {{-- <div class="col-xl-1 col-lg-1 col-md-1 col-sm-12">
        <button name="report" class="btn btn-secondary btn-block mb-3 btn-sm">REPORT</button>
        <p><strong>Guide</strong></p>
        <ul class="list-group">
            @foreach ($lists as $item)
                <i class="list-group-item text-center p-1 pt-3" style="font-size: 11px"> <i class="{{ $item->icon }}" style="font-size: 15px"></i> <br>{{ $item->name }}</i>
            @endforeach
        </ul>
    </div> --}}
@include('calendar.readonly')
@include('calendar.modal-daterange-admin',['wrhs'=>$wrhs])
@endsection
@section('js')
<script src="{{ asset('assets/js/admin.v2.js') }}"></script>
@endsection
