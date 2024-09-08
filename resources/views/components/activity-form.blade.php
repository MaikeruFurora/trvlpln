<div>
    <h6 class="my-4">Plan Details</h5>
    <form id="Activity" autocomplete="off" class="m-t-5 m-b-20" action="{{ route('authenticate.activity.store') }}">@csrf
        <input type="hidden" name="id">
        <div class="form-group mb-3">
            <label class="label-text" for="">Activity</label>
            <select name="activity" id="" class="custom-select" required>
                <option value=""></option>
                @foreach ($lists as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="label-text" for="">Date</label>
            <input type="text" class="form-control datepicker" name="date_from" required/>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="time_from" class="label-text">Time From</label>
                    <input type="time" name="time_from" class="form-control" id="time_from"> <!--timepicker-->
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="time_to" class="label-text">Time to</label>
                    <input type="time" name="time_to" class="form-control" id="time_to"> <!--timepicker-->
                </div>
            </div>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="week" id="applydisWeek">
            <label class="form-check-label label-text" for="applydisWeek">Apply this week.</label>
        </div>
        
        {{-- <div class="mb-3">
            <label class="label-text" for="">Date & time </label>
            <input type="text" class="form-control datepicker" name="date_to"/>
        </div> --}}
        <div class="mb-3">
            <label class="label-text" for="">Client</label>
            <textarea class="form-control text-uppercase" id="" rows="3" name="client" maxlength="100" required></textarea>
        </div> 
        <button type="submit" class="btn w-100 btn-dark mb-3">Submit</button>
    </form>
</div>