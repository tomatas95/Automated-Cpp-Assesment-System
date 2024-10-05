@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/index', 'label' => __('Dashboard')],
        ['url' => '/exercises/create', 'label' => __('Create Exercise')]
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iCodeNET | {{ __("Submitted Exercises") }}</title>
    @vite(['resources/sass/index-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/manage-exercises-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js'  ,'resources/js/hamburger-menu.js', 'resources/js/reset-search.js'])
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
</head>
<body>
    @include('layouts.navbar', ['navBtns' => $navElements])
    <x-flash-message />

    @yield('content')
    <div class="container-fluid dashboard-intro">
        <div class="row align-items-center px-3">
            <div class="col-md-6 text-center text-md-left">
                <div class="d-flex align-items-center justify-content-center margin-top-responsive">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/profile-pic.png') }}" alt="Profile Picture" class="profile-pic">
                    <div>
                        <span class="dashboard-greeting">{{ __("Welcome") }}, @auth<strong>{{ auth()->user()->name }}</strong></span>@endauth
                    </div>
                </div>
                <p class="dashboard-intro-text px-3 mt-4 margin-responsive">{{ __("Here's a page to show all the exercises you've submitted thus far! This page acts as a History page, to all the exercises you tried solving. Click on the specific Exercise to see a more in depth data about it!") }}
                </p>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/profile-page/edit-pic.png') }}" alt="Profile Ilustration" class="img-fluid intro-image">
            </div>
        </div>
    </div>
    <div class="col-12 text-center my-4">
        <h3 class="manage-ex">{{ __("Submitted Exercises") }}</h3>
    </div>
    <div class="col-12 my-4">
        <div class="d-flex justify-content-center">
            @include('partials._search', ['action' => '/submissions'], ['placeholder' => __("Search submissions...")])
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center manage-exercises">
                <thead>
                    <tr>
                        <th class="text-uppercase">{{ __("Title") }}</th>
                        <th class="text-uppercase">{{ __("Approximate Time") }}</th>
                        <th class="text-uppercase">{{ __("Automatization Check") }}</th>
                        <th class="text-uppercase">{{ __("Difficulty") }}</th>
                        <th class="text-uppercase">{{ __("Upload Date") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $submission)  
                    <tr class="ex-style-hover">
                        <th scope="row" class="align-middle"><a href="/submissions/{{ $submission->exercise->id }}/view/{{ $submission->id }}" class="stretched-link">{{ $submission->exercise->title }}</a></th>
                        <td class="align-middle">{{ $submission->exercise->time_number }} {{ __($submission->exercise->time_unit) }}</td>
                        <td class="align-middle">{{ __($submission->auto_check_correct_cases) }} {{ __("Correct") }}</td>
                        <td class="align-middle @if($submission->exercise->difficulty === 'Easy') easy @elseif($submission->exercise->difficulty === 'Normal') normal @elseif($submission->exercise->difficulty === 'Hard') hard @endif">{{ __($submission->exercise->difficulty) }}</td>
                        <td class="align-middle">{{ $submission->updated_at }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            @if (request()->has('search'))
                            <div class="no-exercises-message my-2 mx-2 py-3">
                                <p><i class="fas fa-info-circle fa-lg align-items-center"></i> {{ __("No matching submissions found for ") . "“" . request()->get('search') . "”"}}  
                                    <button id="resetSearchFilter" class="emptySearchBtn" data-url="{{ url()->current() }}"> {{ __("Show all submissions") }}</button></p>
                            </div>
                            @else
                            <div class="no-exercises-message my-2 mx-2 py-3">
                                <p><i class="fas fa-info-circle fa-lg align-items-center"></i>{{ __("You have not submitted an exercise yet! Go ahead and start learning!") }}</p>
                            </div>
                            @endif
                            </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($submissions->count())
        <div class="pagination-wrapper">
            <p class="pagination-details">
                {{ __("Showing") }} <span class="fw-semibold">{{ $submissions->firstItem() }}</span> {{ __("to") }} <span class="fw-semibold">{{ $submissions->lastItem() }}</span> {{ __("of") }} <span class="fw-semibold">{{ $submissions->total() }}</span> {{ __("results") }}
            </p>
            <div class="pagination-links">
                {{ $submissions->appends(request()->input())->links() }}
            </div>
        </div>
        @endif
    </div>
    

    @yield('content')

    @include('layouts.footer')
</body>
</html>
