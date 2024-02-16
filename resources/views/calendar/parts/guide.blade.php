<div class="card m-b-30 border shadow-sm">
    <div class="card-body p-2">
        {{-- <h4 class="card-title font-16 mt-0">Color Legend</h4> --}}
        <p class="card-text"><i class="fas fa-paint-brush"></i> Beat plan color guide</p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item py-1 text-white" style="background: #74daae;font-weight:bold;font-size:12px"><em>Success</em></li>
        <li class="list-group-item py-1 text-white" style="background: #e56874;font-weight:bold;font-size:12px"><em>Failed</em></li>
        <li class="list-group-item py-1 text-white" style="background: #fecb74;font-weight:bold;font-size:12px"><em>Re-schedule</em></li>
    </ul>
</div>
<div class="mb-5">
    <p class="mb-1 font-weight-bold">Guide</p>
    @foreach ($list as $item)
        <button class="btn btn-sm my-1 px-1 border" style="font-size: 11px;">
            <i class="{{ $item->icon }}"></i> {{ $item->name }}
    </button>
    @endforeach
</div>