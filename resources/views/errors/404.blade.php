@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
    ];

    if (Auth::guest()) {
        $navElements[] = ['url' => '/users/register', 'label' => __('Register')];
        $navElements[] = ['url' => '/users/login', 'label' => __('Login')];
    }else{
        $navElements[] = ['url' => '/index', 'label' => __('Dashboard')];
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __("404 Not Found") }}</title>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    @vite(['resources/sass/index-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/error-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js'])
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex flex-column">
    @include('layouts.navbar', ['navBtns' => $navElements])
    <div class="container flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="text-center bg-white p-5 rounded shadow w-100">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('images/error-page/404_error.png') }}" alt="404 Image" class="img-fluid">
                </div>
                <div class="col-md-6 text-md-left text-center">
                    <h1 class="display-1">404</h1>
                    <p class="lead font-weight-bold">{{ __("UH OH! You're lost.") }}</p>
                    <p class="text-muted">{{ __("The page you are looking for does not exist. You can click the button below to go back to the homepage.") }}</p>
                    <a href="{{ url('/') }}" class="btn btn-success btn-lg mt-2">
                        <i class="fas fa-home"></i> {{ __("Back to Home") }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
