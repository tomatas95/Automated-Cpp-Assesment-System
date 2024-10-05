@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '#about', 'label' => __('About Us')],
        ['url' => '#contact', 'label' => __('Contact')],
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
    <title>iCodeNET | {{ __("Path to Mastering Individualization and Programming") }}</title>
    @vite(['resources/sass/welcome-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js'])
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
</head>
<body class=" welcome-section">
    @include('layouts.navbar', ['navBtns' => $navElements])
    @yield('content')
    <x-flash-message />
    <div id="home" class="container-fluid section-intro">

        <x-welcome-header-card />
        <div class="row section-break">
            <div class="col-12 d-flex align-items-center justify-content-center flex-column text-center">
                <h2 id="about" class="section-break-heading">{{ __('Join and be a part of the new') }}</h2>
                <h1 class="section-break-subheading">{{ __('Digital Learning Ecosystem') }}</h1>
            </div>
        </div>
        <x-welcome-about-card />
            <div class="container-fluid section-intro">
                <div class="row final-section">
                    <div class="col-12 d-flex align-items-center justify-content-center flex-column text-center">
                        <h1 class="section-break-subheading">{{ __('Empower Yourself and Learn with iCodeNET!') }}</h1>
                        <p class="final-section-subheading">{{ __("Today's knowledge is tomorrow's history.") }}</p>
                        <p class="final-section-subheading">{{ __("Tomorrow's Triumph begins TODAY!") }}</p>
                        @guest
                        <a class="btn btn-lg welcome-btns mt-3" href="{{ url('/users/register') }}">{{ __('Start Now') }}</a>                            
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('content')
    
    @include('layouts.footer')
</body>
</html>
