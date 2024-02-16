<div>
    <form id="Activity" autocomplete="off" class="m-t-5 m-b-20" action="{{ route('authenticate.activity.store') }}">@csrf
        <input type="hidden" name="id">
        <div class="form-group mb-3">
            <small class="mb-0 label-text" for="">Activity</small>
            <select name="activity" id="" class="custom-select custom-select-sm" required>
                <option value=""></option>
                @foreach ($lists as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-row mb-1">
            <div class="form-group col-lg-8 col-md-8 col-sm-12">
                <small class="mb-0 label-text" for="">Date & time from</small>
                <input type="text" class="form-control form-control-sm datepicker" name="date_from" required/>
            </div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <small class="mb-0 label-text" for="">Time to</small>
                <input type="text"palceholder="Time to" name="time_to" class="form-control form-control-sm timepicker">
            </div>
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