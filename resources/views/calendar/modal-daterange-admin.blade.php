<div>
    <div class="modal fade" id="reportModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-header p-2">
            <p class="modal-title ml-1" id="reportModalLabel">Report - Filter</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-2">
            {{--  --}}
            <form action="{{ route('authenticate.admin.report') }}" id="reportDateRangeForm" autocomplete="off">@csrf
              <div class="form-group">
                <div>
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control-sm form-control" id="date-range-start" name="start" placeholder="Start Date"  required/>
                        <input type="text" class="form-control-sm form-control" id="date-range-end" name="end" placeholder="End Date"  required/>
                    </div>
                </div>
              </div>
              <div class="form-group">
                 <select class="custom-select custom-select-sm" name="wrhs" id="" required>
                     <option value=""></option>
                     @foreach ($users as $key => $item)   
                     <option value="{{ $key }}">{{ $key }}</option>
                     @endforeach
                 </select>
              </div>
            <button class="btn-sm btn btn-block btn-secondary" type="submit">Get Report</button>
            </form>  
            {{--  --}}
        </div>
        </div>
    </div>
  </div>