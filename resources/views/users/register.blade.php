@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/users/login', 'label' => __('Login')],
    ];

    $jsvalidi18 = [
    'username_validation' => __("Username must have atleast one uppercase letter and be within 3-255 characters long."),
    'email_validation' => __("The email must be a valid email address."),
    'pass_match_validation' => __("Passwords must match."),
    'password_validation' => __("Password must be within 5-255 characters long and contain at least one number."),
    'correct_validation' => __("Valid"),
];
@endphp
<script>
    const jsvalidi18 = @json($jsvalidi18);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iCodeNET | {{ __("Register") }}</title>
    @vite(['resources/sass/login-register-styles.scss', 'resources/sass/tooltips-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/js/disable-dash-btn-reqs.js', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js'  ,'resources/js/hamburger-menu.js', 'resources/js/password-toggle.js', 'resources/js/register-live-validation.js'])
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
    <div class="container-fluid register-container d-flex justify-content-center align-items-center pt-5">
        <div class="card register-card p-5 shadow-lg">
            <div class="text-center">
                <img src="{{ asset('images/web-logo.png') }}" alt="Logo" class="register-logo mb-3">
                <h1 class="register-title">iCodeNET</h1>
            </div>
            <form action="/users" method="POST" id="registerForm">
                @csrf
                <div class="form-group mb-4 position-relative">
                    <label for="name" class="form-label">{{ __("Username") }}</label>
                    <div class="input-wrapper">
                        <input type="text" id="name" name="name" class="form-control form-control-lg" placeholder="{{ __("Enter Your Username...") }}" value="{{ old('name') }}">
                        <div class="reg-tool-tip-wrapper">
                            <i class="fa-solid fa-question"></i>
                            <div class="reg-tool-tip">{{ __("Username must be within 3-255 character limit and contain atleast one uppercase letter.") }}</div>
                        </div>
                    </div>
                    <p class="helper-text">{{ __("Enter Your Username") }}</p>
                    @error('name')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                

                <div class="form-group mb-4">
                    <label for="email" class="form-label">{{ __("E-mail") }}</label>
                    <input type="text" id="email" name="email" class="form-control form-control-lg" placeholder="{{ __("Enter Your E-mail...") }}" value="{{ old('email') }}">
                    <p class="helper-text">{{ __("Enter Your E-mail") }}</p>

                    @error('email')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="password" class="form-label">{{ __("Password") }}</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="{{ __("Enter Your Password...") }}">
                        <div class="reg-tool-tip-wrapper">
                            <i class="fa-solid fa-question"></i>
                            <div class="reg-tool-tip">{{ __("Password must be within 3-255 character limit and contain atleast one number.") }}</div>
                        </div>
                        <span id="passwordVisibility" class="togglePassword"> 
                            <i class="fas fa-eye fa-lg toggleIcon" id="toggleIcon"></i>    
                        </span>
                    </div>
                    <p class="helper-text">{{ __("Enter Your Password") }}</p>
                    
                    @error('password')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group mb-4 position-relative">
                    <label for="password_confirmation" class="form-label">{{ __("Confirm Password") }}</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" placeholder="{{ __("Confirm Your Password...") }}">
                        <span id="passwordConfirmationVisibility" class="position-absolute togglePassword"> 
                            <i class="fas fa-eye fa-lg toggleIcon" id="toggleIconConfirmation"></i>    
                        </span>
                    </div>
                    <p class="helper-text">{{ __("Confirm your Password") }}</p>

                    @error('password_confirmation')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" id="submitBtn" class="btn btn-lg register-btn" data-loading-text="{{ __("Signing Up...") }}">{{ __("Sign Up") }}</button>
                </div>
            </form>
            <div class="mt-4 d-flex justify-content-end">
                <a href="/users/login" class="register-login-link">{{ __("Have an account already?") }} <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</body>
</html>
