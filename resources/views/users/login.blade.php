@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/users/register', 'label' => __('Register')],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iCodeNET | {{ __("Sign In") }}</title>
    @vite(['resources/sass/login-register-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/js/disable-dash-btn-reqs.js', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js'  ,'resources/js/hamburger-menu.js', 'resources/js/password-toggle.js', 'resources/sass/tooltips-styles.scss'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
</head>
<body class="register-section">
    @include('layouts.navbar', ['navBtns' => $navElements])
    
    @yield('content')
    <x-flash-message />
    <div class="container-fluid register-container d-flex justify-content-center align-items-center">
        <div class="card register-card p-5 shadow-lg">
            <div class="text-center">
                <img src="{{ asset('images/web-logo.png') }}" alt="Logo" class="register-logo mb-3">
                <h1 class="register-title">iCodeNET</h1>
            </div>
            <form method="POST" action="/users/authenticate" id="loginForm">
                @csrf
                <div class="form-group mb-4">
                    <label for="name" class="form-label">{{ __("Username") }}</label>
                    <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="{{ __("Enter Your Username...") }}">
                    <p class="helper-text">{{ __("Enter Your Username") }}</p>
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="form-label">{{ __("Password") }}</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="{{ __("Enter Your Password...") }}">
                        <span id="passwordVisibility" class="togglePassword"> 
                            <i class="fas fa-eye fa-lg toggleIcon" id="toggleIcon"></i>    
                        </span>
                    </div>
                    <p class="helper-text">{{ __("Enter Your Password") }}</p>
                    
                    @error('credentials')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" id="loginBtn" class="btn btn-lg register-btn" data-loading-text="{{ __("Logging In...") }}">{{ __("Sign In") }}</button>
                </div>
            </form>
            <div class="mt-3 d-flex justify-content-between">
                <a href="/forgot-password" class="register-login-link"><i class="fa fa-arrow-left"></i> {{ __("Forgot my Password") }} </a>
                <a href="/users/register" class="register-login-link">{{ __("Create an account") }} <i class="fa fa-arrow-right"></i></a>
            </div>
            <div class="auth-container mt-4">
                <p class="auth-text">{{ __("You can also sign up with") }}</p>
                <ul class="auth-wrapper">
                    <li class="auth-icon gmail">
                        <a href="{{ url('auth/google') }}" class="auth-link">
                            <span class="auth-tooltip">{{ __("Gmail") }}</span>
                            <span><i class="fab fa-google"></i></span>
                        </a>
                    </li>
                    <li class="auth-icon github">
                        <a href="{{ url('auth/github') }}" class="auth-link">
                            <span class="auth-tooltip">{{ __("GitHub") }}</span>
                            <span><i class="fab fa-github"></i></span>
                        </a>
                    </li>
                </ul>
            </div>
            
        </div>
    </div>
</body>
</html>
