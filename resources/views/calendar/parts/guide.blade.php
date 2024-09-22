<div class="card m-b-30">
    <div class="card-body p-0">
        <h6 class="card-text mb-0">Beat Plan Color Guide</p>
    </div>
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


<div class="mb-5">
    {{-- <p class="mb-1 font-weight-bold">Guide</p>
    @foreach ($list as $item)
        <button class="btn btn-sm my-1 px-1 border" style="font-size: 11px;">
            <i class="{{ $item->icon }}"></i> {{ $item->name }}
    </button>
    @endforeach --}}
</div>