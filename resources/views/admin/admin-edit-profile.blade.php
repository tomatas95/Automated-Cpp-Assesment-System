@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/index', 'label' => __('Dashboard')],
        ['url' => '/exercises/create', 'label' => __('Create Exercise')]
    ];

    $dateOfBirth = explode('-', $user->date_of_birth ?? '');

    $months = [
        __('January'), __('February'), __('March'),
         __('April'), __('May'), __('June'),
          __('July'), __('August'), __('September'),
           __('October'), __('November'), __('December')      
        ];

        $jsvalidi18 = [
        'username_validation' => __("Username must have atleast one uppercase letter and be within 3-255 characters long."),
        'email_validation' => __("The email must be a valid email address."),
        'correct_validation' => __("Valid")
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
    <title>iCodeNET | {{ __("Edit Profile") }}</title>
    @vite(['resources/sass/profile-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/layouts/navbar.scss', 'resources/js/profile-edit-env.js', 'resources/js/disable-dash-btn-reqs.js', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js', 'resources/js/register-live-validation.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    <script>
        const month_lang = @json($months);

        const user_location = @json(old('location', $user->location));
        const birth_year = @json(old('year', $birth_year = $dateOfBirth[0] ?? ''));
        const birth_month = @json(old('month', $birth_month = $dateOfBirth[1] ?? ''));
        const birth_day = @json(old('day', $birth_day = $dateOfBirth[2] ?? ''));

        const country_lang = @json(__('countries'));

    </script>
</head>
<body class="create-bg">
    @include('layouts.navbar', ['navBtns' => $navElements])
    
    @yield('content')
<div class="container-fluid dashboard-intro">
    <div class="row align-items-center px-3 profile-subheading-border">
        <div class="col-md-6 text-center text-md-left">
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/profile-pic.png') }}" alt="Profile Picture" class="profile-pic">
                <div>
                    <span class="dashboard-greeting">{{ __("Welcome") }},<strong> {{ $user->name }}</strong></span>
                    <p class="dashboard-subheading edit-subheading">{{ __("Edit your Profile") }}!</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <img src="{{ asset('images/profile-page/edit-pic.png') }}" alt="Profile Ilustration" class="img-fluid intro-image">
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="bg-profile p-5 rounded">
        <h2 class="profile-heading text-uppercase mb-4">{{ __("Edit Profile") }}</h2>
        <form method="POST" action="/admin/profile/users/{{ $user->id }}" enctype="multipart/form-data" id="profileEdit">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="firstName"><i class="fas fa-user py-2"></i> * {{ __("Username") }}</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="{{ __("Enter your username...") }}" value="{{$user->name }}">
                <p class="helper-text"></p>
                @error('name')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-4">
                <label for="last_name"><i class="fas fa-user-tag py-2"></i> {{ __("Last Name") }}</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="{{ __("Enter your last name...") }}" value="{{$user->last_name }}">
                
                @error('last_name')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-4">
                <label for="email"><i class="fas fa-envelope py-2"></i> * {{ __("Email") }}</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="{{ __("Enter your email...") }}" value="{{$user->email }}">
                <p class="helper-text"></p>
                @error('email')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror
            </div>
           <div class="form-group mt-4">
                <label for="dateOfBirth"><i class="fas fa-birthday-cake py-2"></i> {{ __("Date of Birth") }}</label>
                <div class="d-flex">
                    <select class="form-control me-2" id="year" name="year">
                        <option value="">{{ __("Not specified") }}</option>
                    </select>
                    <select class="form-control me-2" id="month" name="month" style="display: none;">
                        <option value="">{{ __("Not specified") }}</option>
                    </select>
                    <select class="form-control" id="day" name="day" style="display: none;">
                        <option value="">{{ __("Not specified") }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group mt-4">
                <label for="location"><i class="fas fa-map-marker-alt py-2"></i> {{ __("Location") }}</label>
                <select class="form-control" id="location" name="location">
                    <option value="">{{ __("Not specified") }}</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label><i class="fas fa-venus-mars py-2"></i> {{ __("Gender") }}</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" {{$user->gender == 'Male' ? 'checked' : '' }}>
                        <label class="form-check-label" for="genderMale">{{ __("Male") }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" {{$user->gender == 'Female' ? 'checked' : '' }}>
                        <label class="form-check-label" for="genderFemale">{{ __("Female") }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Other" {{$user->gender == 'Other' ? 'checked' : '' }}>
                        <label class="form-check-label" for="genderOther">{{ __("Other") }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="resetGender" value="" {{ is_null($user->gender) ? 'checked' : '' }}>
                        <label class="form-check-label" for="resetGender">{{ __("Don't want to specify") }}</label>
                    </div>
                </div>
            </div>
            <div class="form-group mt-4">
                <label for="profile_picture"><i class="fas fa-image py-2"></i> {{ __("Profile Picture") }}</label>
            
                @if($user->profile_picture)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="pfp-preview">
                    </div>
                @endif
            
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                
                @error('profile_picture')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="skills"><i class="fas fa-laptop-code py-2"></i> {{ __("Skills") }}</label>
                <input type="text" class="form-control" id="skills" name="skills" placeholder="{{ __("Write some of your skills...") }}" value="{{$user->skills }}">
            
                @error('skills')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group mt-4">
                <label for="github"><i class="fab fa-github py-2"></i> {{ __("Github") }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text github-prefix">https://www.github.com/</span>
                    </div>
                    <input type="text" class="form-control" id="github" name="github" placeholder="{{ __("Your GitHub username") }}" aria-describedby="githubPrefix" value="{{$user->github ? str_replace('https://www.github.com/', '', $user->github) : '' }}">
                </div>  
                @error('github')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mt-4">
                <label for="description"><i class="fas fa-info-circle py-2"></i> {{ __("Description") }}</label>
                <textarea class="form-control" id="description" name="description" rows="5" placeholder="{{ __("Introduce yourself to the world!") }}">{{$user->description }}</textarea>
                
                @error('description')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-center mt-4">
                <button type="submit" id="submit-profile-btn" class="btn px-5 py-2 btn-save btn-lg" data-loading-text="{{ __("Saving...") }}">{{ __("Save") }}</button>
            </div>
        </form>
    </div>
</div>
@yield('content')
    
@include('layouts.footer')
</body>
</html>
