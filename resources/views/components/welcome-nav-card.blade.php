<nav class="navbar navbar-expand-lg welcome-nav sticky-top">
    <div class="d-flex align-items-center">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="iCodeNET Logo" class="navbar-logo">
        </a>
        <a class="navbar-brand navbar-title" href="{{ url('/') }}">iCodeNET</a>
    </div>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link nav-btn" href="{{ url('/') }}">{{ __('Home') }} </a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-btn allign-middle" href="#about">{{ __('About') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-btn" href="#contact">{{ __('Contact') }}</a>
            </li>
            @auth
            <li class="nav-item">
                <a class="nav-link nav-btn" href="{{ url('/index') }}">{{ __('Dashboard') }}</a>
            </li>
            @else
            <li class="nav-item">
                <a class="nav-link nav-btn" href="{{ url('/users/login') }}">{{ __('Login') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-btn" href="{{ url('/users/register') }}">{{ __('Register') }}</a>
            </li>
            @endauth
        </ul>
    </div>
    <div class="dropdown ml-auto">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            @php
                $locale = session('lang', 'en');
                $flag = $locale === 'lt' ? 'LT.png' : 'GB.png';
            @endphp
            <img src="{{ asset('flags/' . $flag) }}" alt="Language" class="flag-icon"> {{ strtoupper($locale) }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
            <li>
                <a class="dropdown-item" href="{{ route('setLanguage', 'en') }}">
                    <img src="{{ asset('flags/GB.png') }}" alt="Great Britain Flag" class="flag-icon"> EN
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('setLanguage', 'lt') }}">
                    <img src="{{ asset('flags/LT.png') }}" alt="Lithuanian Flag" class="flag-icon"> LT
                </a>
            </li>
        </ul>
    </div>
    <div class="dropdown ml-2">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('My Account') }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
            <li><a class="dropdown-item" href="/profile">{{ __("Profile") }}</a></li>
            <li><a class="dropdown-item" href="/logout">{{ __("Logout") }}</a></li>
        </ul>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>