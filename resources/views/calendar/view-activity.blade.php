<div>
  <form action="{{ route('authenticate.activity.update.info',['param']) }}" id="ActivityForm" autocomplete="off">@csrf
  <div class="modal fade" id="viewActivity" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1" aria-labelledby="viewActivityLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header p-3 border-0">
          <p class="modal-title ml-2" id="viewActivityLabel">title</p>
          <button type="button" class="btn btn-sm btn-danger m-1" name="delete" data-delete="{{ route('authenticate.activity.destroy',['param']) }}" style="font-size: 11px">
            <i class="fas fa-calendar-times"></i> Delete
          </button>
        </div>
        <div class="modal-body p-2">
              {{--  --}}
              <input type="hidden" class="getInput" name="id">
              <!-- Nav tabs -->
              <ul class="nav nav-pills my-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active rounded-pill border-0 shadow-sm mx-1" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">
                        Details
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link rounded-pill border-1 shadow-sm mx-1" id="booking-tab" data-toggle="tab" href="#booking" role="tab" aria-controls="booking" aria-selected="false">
                        Booking
                    </a>
                </li>
              </ul>
            
              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="details" role="tabpanel" aria-labelledby="details-tab">
                  <div class="card-body p-2 mb-0">
                    <div class="row d-none"> 
                      <div class="col-lg-4 col-md-4 col-sm-6">
                          <div class="mb-3">
                            <label class="mb-2 label-text" for="">Time From</label>
                            <input type="time" class="form-control getInput " name="time_from"> <!--timepicker-->
                          </div>
                      </div>
                      <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="mb-3">
                          <label class="mb-2 label-text" for="">Time to</label>
                          <input type="time" class="form-control getInput " name="time_to"> <!--timepicker-->
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="mb-3">
                              <label class="mb-2 label-text" for="">Date</label>
                              <input type="text" class="form-control getInput datepicker" name="date_from">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                          <div class="mb-3">
                            <label class="mb-2 label-text" for="">Activity</label>
                            <select name="activity" class="getInput form-control" id="">
                              <option value=""></option>
                              @foreach ($lists as $item)
                                  <option value="{{ $item->id }}">{{ $item->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="mb-2 label-text" for="">Client or Title</label>
                        <input type="text" name="client" class="getInput form-control" id="">
                    </div>
                    <div class="col-12 mb-2 d-flex justify-content-end">
                      @foreach ($sttus as $key => $item)
                      <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input getInput sample{{$key}}"  name="sttus[]" id="customControlValidation1{{$item}}" value="{{ $item }}">
                        <label class="form-check-label" style="font-size: 13px" for="customControlValidation1{{$item}}">{{ ucwords($item) }}</label>
                      </div>
                      @endforeach
                    </div>
                    <div class=" mb-3">
                      <label class="" for="">OS No.</label>
                        <input type="text" name="osnum" class="getInput form-control" id="">
                    </div>
                    <div class=" mb-3">
                      <label class="" for="">Notes / Remarks</label>
                      <textarea name="note" class="getInput form-control mb-0" id="" cols="10" rows="6"></textarea>
                    </div>
                    <div class="form-row mb-0">
                      <div class=" mb-3 col-lg-6 col-sm-12">
                        <input type="hidden" class="form-control" name="latitude">
                      </div>
                      <div class=" mb-3 col-lg-6 col-sm-12">
                        <input type="hidden" class="form-control" name="longitude">
                      </div>
                    </div>
                    {{--  --}}   
                    @include('calendar.parts.view-activity-button')
                    {{--  --}}
                  </div>
              </div>
              <div class="tab-pane" id="booking" role="tabpanel" aria-labelledby="booking-tab">
                  {{--  --}}
                    <!-- Product Form -->
                  <div class="row mt-4 px-2">
                      <div class="col-lg-6 col-sm-12 position-relative">
                        <div class="mb-3">
                            <label class="mb-2 label-text" for="">Product</label>
                            <input type="text" name="product" id="product" class="form-control" placeholder="Search product" style="text-transform: uppercase">
                            <ul id="suggestions_list" class="list-group"></ul>
                        </div>
                      </div>
                      <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                            <label class="mb-2 label-text" for="">Quantity</label>
                            <input type="number" id="qty" name="qty" class="form-control" placeholder="Quantity">
                        </div>
                      </div>
                      <div class="col-lg-3 col-sm-12">
                        <div class="mb-3">
                          <label class="mb-2 label-text" for="">Price</label>
                          <input type="number" id="price" name="price" class="form-control" placeholder="Price">
                        </div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-end">
                      <button id="addProduct" type="button" class="btn btn-success mr-2 my-3">Add Product</button>
                  </div>
                  <!-- Product Table -->
                  <div class="table-responsive mb-5 px-3">
                      <h6>Order</h6>
                      <table id="productTable" class="table table-striped table-sm">
                          <thead>
                              <tr>
                                  <th>Product</th>
                                  <th>Quantity</th>
                                  <th>Price</th>
                                  <th width="10%">Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              <!-- Rows will be dynamically added here -->
                          </tbody>
                      </table>
                  </div>
                  {{--  --}}
              </div>
              </div>
              {{--  --}}
              {{-- <div class="card-footer p-0">
                <small id="location" class="text-center"></small>
                <div id="map"></div>
              </div> --}}
          </div>
        </div>
      </div>
    </div>
  </form>  
</div>