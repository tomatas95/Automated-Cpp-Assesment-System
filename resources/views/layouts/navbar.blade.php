<nav class="navbar navbar-expand-lg welcome-nav sticky-top">
    <div class="d-flex align-items-center">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/web-logo.png') }}" alt="iCodeNET Logo" class="navbar-logo navbar-logo-wrap">
        </a>
        <a class="navbar-brand navbar-title" href="{{ url('/') }}">iCodeNET</a>
    </div>

    <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex" id="navbarNav">
        <ul class="navbar-nav">
            @foreach ($navBtns as $navBtn)
                <li class="nav-item">
                    <a class="nav-link nav-btn" href="{{ url($navBtn['url']) }}">{{ $navBtn['label'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="dropdown ml-auto px-2">
        @auth
        <button class="btn btn-secondary dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ __("My Account") }}
        </button>
        @endauth
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
            <li>
                <a class="dropdown-item" href="/users/my-profile">
                    <i class="fas fa-user"></i> {{ __("View Profile") }}
                </a>
            </li>
            @if (Auth::check() && Auth::user()->role === 'admin')
            <li>
                <a class="dropdown-item" href="/admin/user-list">
                    <i class="fas fa-user-cog"></i>{{ __("User List") }}
                </a>
            </li>
            @endif
            <li>
                <a class="dropdown-item" href="/submissions">
                    <i class="fas fa-paper-plane"></i> {{ __("Submitted Exercises") }}
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="/exercises/manage">
                    <i class="fas fa-tasks"></i> {{ __("Created Exercises") }}
                </a>
            </li>
            <li>
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item" href="#">
                        <i class="fas fa-sign-out-alt"></i> {{ __("Sign Out") }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
    <div class="dropdown ml-2">
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
                    <img src="{{ asset('flags/GB.png') }}" alt="Great Britain Flag" class="flag-icon"> {{ __("English") }}
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('setLanguage', 'lt') }}">
                    <img src="{{ asset('flags/LT.png') }}" alt="Lithuanian Flag" class="flag-icon"> {{ __("Lithuanian") }}
                </a>
            </li>
        </ul>
    </div>
    <div class="d-lg-none ml-auto">
        <button class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>        
    </div>
    <div id="mobileMenu" class="d-lg-none mobileHamburger">
        <ul class="navbar-nav flex-column">
            @foreach ($navBtns as $navBtn)
                <li class="nav-item">
                    <a class="nav-link nav-btn" href="{{ url($navBtn['url']) }}">{{ $navBtn['label'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
