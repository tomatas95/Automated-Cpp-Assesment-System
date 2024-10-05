@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/users/register', 'label' => __('Register')],
        ['url' => '/users/login', 'label' => __('Login')],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iCodeNET | {{ __("Recover Password") }}</title>
    @vite(['resources/sass/login-register-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/js/disable-dash-btn-reqs.js', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
</head>
<body class="register-section">
    @include('layouts.navbar', ['navBtns' => $navElements])
    
    @yield('content')
    <div class="container-fluid register-container d-flex justify-content-center align-items-center">
        <div class="card register-card p-5 shadow-lg">
            <div class="text-center">
                <img src="{{ asset('images/web-logo.png') }}" alt="iCodeNET Logo" class="register-logo mb-3">
                <h1 class="register-title">iCodeNET</h1>
            </div>

            @php
            $message = session()->pull('email_notif');
            $messageType = session()->pull('message_type');
        @endphp
        
        @if ($message)
        <div class="alert alert-{{ $messageType == 'warning' ? 'warning' : ($messageType == 'info' ? 'info' : 'success') }} d-flex align-items-center mt-4">
            <i class="fas fa-{{ $messageType == 'warning' ? 'exclamation-circle' : ($messageType == 'info' ? 'info-circle' : 'envelope') }} fa-lg me-2"></i>
            {{ $message }}
        </div>
        @endif
        
            <form method="POST" action="/forgot-password-recovery" id="recoverPasswordForm">
                @csrf
                <div class="form-group my-4">
                    <label for="email" class="form-label">{{ __("Enter your existing Email") }}</label>
                    <input type="text" id="email" name="email" class="form-control form-control-lg" placeholder="{{ __("Enter Your Existing Email...") }}">
                    <p class="helper-text">{{ __("Enter your existing Email") }}</p>
                
                    @error('email')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg register-btn recover-pass-btn" data-loading-text="{{ __("Recovering Password...") }}">{{ __("Recover Password") }}</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
