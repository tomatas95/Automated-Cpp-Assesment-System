@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/index', 'label' => __('Dashboard')],
        ['url' => '/exercises/create', 'label' => __('Create Exercise')]
    ];

    $location = $user->location;
    $gender = $user->gender;
    $country = $user->location;

    $jsvalidi18 = [
    'created_ex' => __("Created Exercises"),
    'submitted_ex' => __("Submitted Exercises"),
    'no_data_available' => __("Yet to create or submit any exercise! What are you waiting for? The intricasies of programming are waiting for you!")
];
@endphp
<script>
        const createdData = @json($createdExercises);
        const submittedData = @json($submittedExercises);
        const jsvalidi18 = @json($jsvalidi18);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iCodeNET | {{ __("Profile") }}</title>
    @vite(['resources/sass/profile-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/graph-styles.scss' ,'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/responsive-btn-layout-change.js', 'resources/js/navbar-dropdown-toggle.js'  ,'resources/js/hamburger-menu.js', 'resources/js/activity-graph.js'])
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
</head>
<body class="create-bg">
    @include('layouts.navbar', ['navBtns' => $navElements])
    
    @yield('content')
    <x-flash-message />

    <div class="container-fluid dashboard-intro">
        <div class="row align-items-center px-3 profile-subheading-border">
            <div class="col-md-6 text-center text-md-left">
                <div class="d-flex align-items-center justify-content-center margin-responsive">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/profile-pic.png') }}" alt="Profile Picture" class="profile-pic">
                    <div>
                        <span class="dashboard-greeting">{{ __("Welcome") }}, <strong>{{ $user->name }}</strong></span>
                        <p class="dashboard-subheading margin-x-responsive">{{ __("to your profile menu!") }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/profile-page/edit-pic.png') }}" alt="Profile Ilustration" class="img-fluid intro-image">
            </div>
        </div>
    </div>
<div class="container mt-5">
    <div class="bg-profile upper-container p-5">
        <h2 class="profile-heading text-uppercase mb-4">{{ __("Profile Information") }}</h2>
        <div class="row">
            <div class="col-md-6 mb-3 d-flex justify-content-between align-items-center">
                <p class="profile-input m-0 text-nowrap"><i class="fas fa-user"></i> <strong>{{ __("Name:") }}</strong> {{ $user->name }}</p>
            </div>
            <div class="col-md-6 mb-3 d-flex justify-content-between align-items-center">
                <p class="profile-input m-0 text-nowrap"><i class="fas fa-user-tag"></i> <strong>{{ __("Last Name:") }}</strong> {{ $user->last_name ?? __('Not specified') }}</p>
                <a href="{{ url("/users/my-profile/edit") }}" id="editBtn" class="btn btn-profile"><i class="fas fa-pen"></i> {{ __("Edit") }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 d-flex justify-content-between align-items-center email-row">
                <p class="profile-input m-0 text-nowrap email-row-responsive"><i class="fas fa-envelope"></i> <strong>{{ __("Email:") }}</strong> {{ $user->email }}</p>
            </div>
            <div class="col-md-6 d-flex justify-content-between align-items-center">
                <p class="profile-input m-0 text-nowrap"><i class="fas fa-map-marker-alt"></i> <strong>{{ __("Location") }}:</strong> 
                @if ($location)
                    {{ $countries[$country] ?? $location }}
                @else
                    {{ __("Not specified") }}
                @endif
                </p>
                <a href="{{ url("/exercises/manage") }}" id="createdExBtn" class="btn btn-profile"><i class="fas fa-tasks"></i> {{ __("Created Exercises") }}</a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6 mb-3 d-flex justify-content-between align-items-center">
                <p class="profile-input m-0 text-nowrap"><i class="fas fa-birthday-cake"></i> <strong>{{ __("Date of Birth") }}:</strong> {{ $user->date_of_birth ?? __('Not specified') }}</p>
            </div>
            <div class="col-md-6 mb-3 d-flex justify-content-between align-items-center">
                <p class="profile-input m-0 text-nowrap"><i class="fas fa-venus-mars"></i> <strong>{{ __("Gender") }}:</strong>
                    @if ($gender)
                    {{ $genders[$gender] ?? $gender }}
                @else
                    {{ __("Not specified") }}
                @endif
                </p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6 mb-3 d-flex justify-content-between align-items-center">
                <p class="profile-input m-0"><i class="fas fa-laptop-code"></i> <strong>{{ __("Skills") }}:</strong> {{ $user->skills ?? __('Not specified') }}</p>
            </div>
            <div class="col-md-6 mb-3 d-flex justify-content-between align-items-center flex-wrap">
                <p class="profile-input m-0"><i class="fab fa-github"></i> <strong>{{ __("GitHub") }}:</strong>
                @if ($user->github)
                    <a href="{{ $user->github }}" class="github-link">{{ $user->github }}</a>
                @else
                    {{ __("Not specified") }}
                @endif
                </p>
            </div>
        </div>        
        <div class="row mt-4" id="descRow">
            <div class="col-md-12 mb-3">
                <p class="profile-input m-0"><i class="fas fa-info-circle"></i> <strong>{{ __("Description") }}:</strong> {{ $user->description ?? __('Not specified') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-profile p-5 bottom-container">
        <h2 class="profile-heading text-uppercase mb-4">{{ __("Activity Overview") }}</h2>
        <div class="legend">
            <div class="pretty p-svg p-smooth created-checkbox">
                <label class="position-relative" for="createdExercises">
                    <input type="checkbox" class="legend-checkbox" id="createdExercises" checked value="{{ __("Created Exercises") }}">
                    {{ __("Created Exercises") }}
                </label>
            </div>
            <div class="pretty p-svg p-smooth submitted-checkbox">
                <label class="position-relative" for="submittedExercises">
                    <input type="checkbox" class="legend-checkbox" id="submittedExercises" checked value="{{ __("Submitted Exercises") }}">
                    {{ __("Submitted Exercises") }}
                </label>
            </div>
        </div>
    
        <div id="exerciseChart"></div>
    
        <div class="chart-type-options mt-3">
            <input type="radio" name="chartType" id="areaChart" value="area" checked>
            <input type="radio" name="chartType" id="barChart" value="bar">
            
            <label for="areaChart" class="option option-area">
                <div class="dot"></div>
                <span>{{ __("Area") }}</span>
            </label>
            
            <label for="barChart" class="option option-bar">
                <div class="dot"></div>
                <span>{{ __("Bar") }}</span>
            </label>
        </div>
        
        </div>
    </div>
    
</div>
</div>
@yield('content')
    
@include('layouts.footer')
</body>
</html>
