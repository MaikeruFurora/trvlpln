<div>
    <h5 class="my-3">Plan Details</h5>
    <form id="Activity" autocomplete="off" class="m-t-5 m-b-20" action="{{ route('authenticate.activity.store') }}">@csrf
        <input type="hidden" name="id">
        <div class="form-group mb-3">
            <small class="mb-0 label-text" for="">Activity</small>
            <select name="activity" id="" class="select2 form-control" required>
                <option value=""></option>
                @foreach ($lists as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <small class="mb-0 label-text" for="">Date</small>
            <input type="text" class="form-control datepicker" name="date_from" required/>
        </div>
        <div class="row mb-3">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="time_from" class="label-text">Time From</label>
                <input type="text" name="time_from" class="form-control timepicker" id="time_from">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="time_to" class="label-text">Time to</label>
                <input type="text" name="time_to" class="form-control timepicker" id="time_to">
            </div>
        </div>
        <div class="form-group form-check mb-3">
            <input type="checkbox" class="form-check-input" name="week" id="applydisWeek">
            <small class="form-check-label label-text" for="applydisWeek">Apply this week.</small>
        </div>
        
        {{-- <div class="form-group mb-3">
            <small class="mb-0 label-text" for="">Date & time </small>
            <input type="text" class="form-control datepicker" name="date_to"/>
        </div> --}}
        <div class="form-group mb-3">
            <small class="mb-0 label-text" for="">Client</small>
            <textarea class="form-control text-uppercase" id="" rows="3" name="client" maxlength="100" required></textarea>
        </div> 
        <button type="submit" class="btn w-100 btn-primary mb-3">Submit</button>
    </form>
</div>