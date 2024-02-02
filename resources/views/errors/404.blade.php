
@extends('../layout/app')
@section('content')
<div class="account-pages">
            <div class="container">
                <div class="row justify-content-center" style="display: flex;align-items: center;align-content: center;height:50vh">
                    <div class="col-lg-7">
                        <div class="text-center mb-5">
                            <div class="mb-5">
                                <img src="{{ asset('assets/images/landed-icon-black.png') }}" height="32" alt="logo">
                            </div>
                            <h4 class="mt-4">404 PAGE NOT FOUND</h4>
                            <p>The HTTP 404 Not Found response status code indicates that the server cannot find the requested resource. Links that lead to a 404 page are often called broken or dead links and can be subject to link rot</p>
                            <a href="{{ route('authenticate.activity') }}" class="btn btn-primary btn-sm">Back home</a>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
@endsection