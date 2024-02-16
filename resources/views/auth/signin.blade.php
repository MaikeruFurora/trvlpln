<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Travel Plan</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App Icons -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.css" rel="stylesheet" type="text/css" />

    </head>


    <body>

        <!-- Loader -->
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>
            </div>
        </div>

        <!-- Begin page -->
    

        <div class="account-pages">
            
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mx-auto">
                        <div>
                            <div >
                                <a href="index.html" class="logo logo-admin">
                                    <img src="{{ asset('assets/images/logo-bg-1.png') }}" height="65" alt="logo">
                                </a>
                            </div>
                            <h5 class="font-14 text-muted"></h5>
                            <p class="text-muted ">
                                <b>BEAT PLAN</b> - A "beat plan" might be a strategic plan outlining the schedule and locations for sales representatives or merchandisers to visit. This plan helps ensure that sales representatives cover specific territories or retail outlets efficiently and regularly.
                            </p>

                            <h5 class="font-14 text-muted mb-4">Sign in with your account :</h5>
                            <div>
                                <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Enter your personal account using your username and password.</p>
                                <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>If you continue to receive an error, please contact IT for assistance. </p>
                                <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Thank you and have a nice day.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        @if (session()->has('msg'))
                            <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> {{ session()->get('msg') }}
                            </div>
                        @endif
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="p-2">
                                    <h4 class="text-muted float-right font-18 mt-4">Sign In</h4>
                                    <div>
                                        <a href="index.html" class="logo logo-admin"><img src="{{ asset('assets/images/logo-bg-sm.png') }}" height="40" alt="logo"></a>
                                    </div>
                                </div>
        
                                <div class="p-2">
                                    <form class="form-horizontal m-t-20" action="{{ route('auth.login.post') }}" method="POST" autocomplete="off">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input class="form-control" type="text" placeholder="Email | Username" name="username" autocomplete="off" autofocus value="">
                                                @error('username')
                                                <small class="form-text text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <input class="form-control" type="password" placeholder="Password" name="password" value="">
                                                @error('password')
                                                <small class="form-text text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember_token">
                                                    <label class="custom-control-label" for="customCheck1">Remember me</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group text-center row m-t-20">
                                            <div class="col-12">
                                                <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
        
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>



        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>

    </body>
</html>