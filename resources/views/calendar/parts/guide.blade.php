<ul class="nav nav-pills my-2" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active rounded-pill border-0 shadow-sm mx-1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
            Color
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link rounded-pill border-1 shadow-sm mx-1" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
            Icon 
        </a>
    </li>
  </ul>

<!-- Tab panes -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane p-3 fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <ul class="list-group list-group-flush no-border-list">
            <li class="list-group-item py-2 px-0 d-flex align-items-center">
                <span class="circle" style="background: #306fb0;"></span>
                <span class="ml-2" style="font-weight: bold; font-size: 14px;">Pending / No status</span>
            </li>
            <li class="list-group-item py-2 px-0 d-flex align-items-center">
                <span class="circle" style="background: #3ab380;"></span>
                <span class="ml-2" style="font-weight: bold; font-size: 14px;">Success</span>
            </li>
            <li class="list-group-item py-2 px-0 d-flex align-items-center">
                <span class="circle" style="background: #e56874;"></span>
                <span class="ml-2" style="font-weight: bold; font-size: 14px;">Failed</span>
            </li>
            {{-- <li class="list-group-item py-2 px-0 d-flex align-items-center">
                <span class="circle" style="background: #f4a429;"></span>
                <span class="ml-2" style="font-weight: bold; font-size: 14px;">Re-schedule</span>
            </li> --}}
        </ul>
    </div>
    <div class="tab-pane p-3 fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        @foreach ($list as $item)
            <button class="btn btn-sm my-1 px-1 border" style="font-size: 11px;">
                <i class="{{ $item->icon }}"></i> {{ $item->name }}
            </button>
        @endforeach
    </div> 
</div>
