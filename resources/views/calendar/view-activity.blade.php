<div>
  <div class="modal fade" id="viewActivity" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="viewActivityLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header p-2">
          <p class="modal-title ml-1" id="viewActivityLabel">title</p>
          {{-- <button type="button" class="close" style="font-size: 15px"  data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> --}}
        </div>
        <div class="modal-body p-2">
              {{--  --}}
              <form action="{{ route('authenticate.activity.update.info',['param']) }}" id="ActivityForm" autocomplete="off">@csrf
                <div class="form-row p-1">
                  <div class="form-group col-lg-6 col-sm-12">
                    <small class="label-text" for="">Date & time from</small>
                    <input type="text" class="form-control form-control-sm getInput" name="date_from">
                  </div>
                  <div class="form-group col-lg-6 col-sm-12">
                    <small class="label-text" for="">Date & time to</small>
                    <input type="text" class="form-control form-control-sm getInput" name="date_to">
                  </div>
                </div>
                <input type="hidden" class="getInput" name="id">
                <div id="accordion">
                  <div class="card border mb-0">
                      <div class="card-header border p-1" id="headingOne">
                        <p class="mb-0 mt-0 ml-2"><i class="fas fa-info-circle"></i> Activity Details</p>
                      </div>  
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                          <div class="card-body p-2 mb-0">
                            <div class="row">
                              <div class="col-8">
                                <div class="form-group mb-1">
                                  <small class="mb-0 label-text" for="">Activity</small>
                                  <select name="activity" class="getInput custom-select custom-select-sm" id="">
                                    <option value=""></option>
                                    @foreach ($lists as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                  </select>
                                </div>
                                <div class="form-group">
                                <small class="mb-0 label-text" for="">Client</small>
                                  <input type="text" name="client" class="getInput form-control form-control-sm" id="">
                                </div>
                              </div>
                              <div class="col-4">
                                <br>
                                @foreach ($sttus as $key => $item)
                                <div class="custom-control mb-1">
                                  <input type="checkbox" class="custom-control-input getInput sample{{$key}}"  name="sttus[]" id="customControlValidation1{{$item}}" value="{{ $item }}">
                                  <label class="custom-control-label" for="customControlValidation1{{$item}}">{{ ucwords($item) }}</label>
                                </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                      </div>
                  </div>
                  <div class="card border mb-0">
                      <div class="card-header p-1 border" id="headingTwo">
                          <p class="mb-0 mt-0 ml-2"><i class="fas fa-comment-alt"></i> Sales Order</p>
                      </div>
                      <div>
                          <div class="card-body  p-2">
                            <div class="form-group">
                              <label class="sr-only" for="inlineFormInputGroup">OS NUM</label>
                              <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                  <div class="input-group-text">OS NUM</div>
                                </div>
                                <input type="text" name="osnum" class="getInput form-control form-control-sm" id="">
                              </div>
                            </div>
                            <div class="form-group mb-1">
                              <small class="mb-0 label-text" for="">Notes / Remarks</small>
                              <textarea name="note" class="getInput form-control mb-0" id="" cols="10" rows="6"></textarea>
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
                @include('calendar.parts.view-activity-button')
              </form>  
              {{--  --}}
            {{-- <div class="card-footer p-2"></div> --}}
          </div>
        </div>
      </div>
</div>