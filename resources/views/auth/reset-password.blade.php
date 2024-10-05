@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/users/register', 'label' => __('Register')],
        ['url' => '/users/login', 'label' => __('Login')],
    ];

    $jsvalidi18 = [
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
    <title>iCodeNET | {{ __("Recover Password") }}</title>
    @vite(['resources/sass/login-register-styles.scss', 'resources/sass/tooltips-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/js/disable-dash-btn-reqs.js', 'resources/sass/hamburger-styles.scss' ,'resources/js/hamburger-menu.js', 'resources/js/navbar-dropdown-toggle.js' , 'resources/js/password-toggle.js', 'resources/js/register-live-validation.js'])
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

            <form method="POST" action="/reset-password" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group my-4 position-relative">
                    <label for="password" class="form-label">{{ __("Enter new Password") }}</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="{{ __("Enter Your New Password...") }}">
                        <div class="reg-tool-tip-wrapper">
                            <i class="fa-solid fa-question"></i>
                            <div class="reg-tool-tip">{{ __("Password must be within 3-255 character limit and contain atleast one number.") }}</div>
                        </div>
                        <span id="passwordVisibility" class="togglePassword"> 
                            <i class="fas fa-eye fa-lg toggleIcon" id="toggleIcon"></i>    
                        </span>
                    </div>
                    <p class="helper-text">{{ __("Enter your new Password") }}</p>
                    @error('password')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group my-4 position-relative">
                    <label for="password_confirmation" class="form-label">{{ __("Confirm your new Password") }}</label>
                    <div class="input-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" placeholder="{{ __("Confirm Your New Password...") }}">
                    <span id="passwordConfirmationVisibility" class="position-absolute togglePassword"> 
                        <i class="fas fa-eye fa-lg toggleIcon" id="toggleIconConfirmation"></i>    
                    </span>
                    </div>
                    <p class="helper-text">{{ __("Confirm your new Password") }}</p>
                    @error('password_confirmation')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-lg register-btn" data-loading-text="{{ __("Saving Password...") }}">{{ __("Change Password") }}</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
