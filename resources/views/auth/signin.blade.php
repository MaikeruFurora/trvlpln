<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Beat Plan</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App Icons -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/login.css" rel="stylesheet" type="text/css" />
</head>
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    Loading...
</div>
<body>
<div class="d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-8">
                <div class="card p-4">
                    <div class="text-center">
                        <a href="#" class="logo logo-admin">
                            <img src="{{ asset('assets/images/logo-bg-1.png') }}" height="50" alt="logo">
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="text-center">Sign In to Your Account</h5>
                        <p class="text-muted text-center mb-4">Welcome back! Please login to your account.</p>

                        @if (session()->has('msg'))
                            <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> {{ session()->get('msg') }}
                            </div>
                        @endif

                        <form action="{{ route('auth.login.post') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Email or Username" name="username" autofocus>
                                @error('username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="Password" name="password">
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-block" type="submit">Log In</button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0">Need help? <a href="#">Contact IT</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.onload = function() {
        let overlay = document.getElementById('overlay');

        // Show the overlay
        overlay.style.display = 'block';

        // Hide the overlay after 2 seconds
        setTimeout(function() {
            overlay.style.display = 'none';
        }, 2000);
    };
</script>
</body>
</html>
