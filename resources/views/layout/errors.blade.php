<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Error @yield('code')</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        html, body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-md-8">
                <h1 class="display-1">@yield('code')</h1>
                <h2 class="mb-4">@yield('message')</h2>
                <a href="{{ url('/') }}" class="btn btn-primary">Go Back</a>
            </div>
        </div>
    </div>
</body>
</html>
