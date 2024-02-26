<div class="row justify-content-between mt-2">
    <div class="col-lg-3 col-md-6 col-sm-12">
        <button type="button" name="delete" data-delete="{{ route('authenticate.activity.destroy',['param']) }}" class="btn btn-sm btn-danger btn-block m-1" style="font-size: 11px">
            <i class="fas fa-calendar-times"></i> Delete
        </button>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="btn-group btn-block m-1 " role="group" aria-label="Basic example">
            <button type="button" class="btn btn-sm m-0 btn-secondary mr-1" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm m-0 btn-success mr-1" name="save">Save</button>
        </div>
    </div>
</div>