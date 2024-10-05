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
    <title>iCodeNET | {{ __("Manage Exercises") }}</title>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    @vite(['resources/sass/index-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/manage-exercises-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js', 'resources/js/reset-search.js'])
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navbar', ['navBtns' => $navElements])
    <x-flash-message />

    @yield('content')
    <div class="container-fluid dashboard-intro">
        <div class="row align-items-center px-3">
            <div class="col-md-6 text-center text-md-left margin-top-responsive">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/profile-pic.png') }}" alt="Profile Picture" class="profile-pic">
                    <div>
                        <span class="dashboard-greeting">{{ __("Welcome") }}, @auth<strong>{{ auth()->user()->name }}</strong></span>@endauth
                    </div>
                </div>
                <p class="dashboard-intro-text px-3 mt-4">{{ __("Here's a page to check all of your created exercises, including a way to edit them after the exercise has been successfully published! You also have full functionalities to view all the submissions from the people that have submitted a possible solution for your exercise. Analyze, decypher and improve your own knowledge!") }}
                </p>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/profile-page/edit-pic.png') }}" alt="Profile Ilustration" class="img-fluid intro-image">
            </div>
        </div>
    </div>
    <div class="col-12 text-center my-4">
        <h3 class="manage-ex">{{ __("Manage Exercises") }}</h3>
    </div>
    <div class="col-12 my-4">
        <div class="d-flex justify-content-center">
            @include('partials._search', ['action' => '/exercises/manage'], ['placeholder' => __("Search created exercises...")])
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center manage-exercises">
                <thead>
                    <tr>
                        <th class="text-uppercase">{{ __("Title") }}</th>
                        <th class="text-uppercase">{{ __("Approximate Time") }}</th>
                        <th class="text-uppercase">{{ __("Difficulty") }}</th>
                        <th class="text-uppercase">{{ __("Submittion Count") }}</th>
                        <th class="text-uppercase">{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($exercises as $exercise)  
                    <tr class="ex-style-hover">
                        <th scope="row" class="align-middle"><a class="stretched-link" href="/exercises/edit/{{ $exercise->id }}">{{ $exercise->title }}</a></th>
                        <td class="align-middle">{{ $exercise->time_number }} {{ __($exercise->time_unit) }}</td>
                        <td class="align-middle @if($exercise->difficulty === 'Easy') easy @elseif($exercise->difficulty === 'Normal') normal @elseif($exercise->difficulty === 'Hard') hard @endif">{{ __($exercise->difficulty) }}</td>
                        <td class="align-middle">{{ $exercise->submission_count }}</td>
                        <td class="align-middle position-relative">
                            <div class="btn-group hover-overlap">
                                <a href="/exercises/edit/{{ $exercise->id }}" class="btn text-warning">
                                    <i class="fas fa-pen manage-btn fa-lg"></i>
                                </a>
                                <a href="/manage/{{ $exercise->id }}/submissions/view" class="btn text-info">
                                    <i class="fas fa-address-book manage-btn fa-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            @if (request()->has('search'))
                            <div class="no-exercises-message my-2 mx-2 py-3">
                                <p><i class="fas fa-info-circle fa-lg align-items-center"></i> {{ __("No matching exercises found for ") . "“" . request()->get('search') . "”"}}  
                                    <button id="resetSearchFilter" class="emptySearchBtn" data-url="{{ url()->current() }}"> {{ __("Show all exercises") }}</button></p>
                            </div>
                            @else
                            <div class="no-exercises-message my-2 mx-2 py-3">
                                <p><i class="fas fa-info-circle fa-lg align-items-center"></i> {{ __("You don't have any exercises created, consider creating one!") }}</p>
                            </div>
                            @endif
                            </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($exercises->count())
        <div class="pagination-wrapper">
            <p class="pagination-details text-muted">
                {{ __("Showing") }} <span class="fw-semibold">{{ $exercises->firstItem() }}</span> {{ __("to") }} <span class="fw-semibold">{{ $exercises->lastItem() }}</span> {{ __("of") }} <span class="fw-semibold">{{ $exercises->total() }}</span> {{ __("results") }}
            </p>
            <div class="pagination-links">
                {{ $exercises->appends(request()->input())->links() }}
            </div>
        </div>
        @endif
    </div>
    

    @yield('content')

    @include('layouts.footer')
</body>
</html>
