@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/index', 'label' => __('Dashboard')],
        ['url' => '#features', 'label' => __('Features')],
        ['url' => '#exerciseList', 'label' => __('Exercise List')],
        ['url' => '/exercises/create', 'label' => __('Create Exercise')],
    ];

@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>iCodeNET | {{ __("Dashboard") }}</title>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    @vite(['resources/sass/index-styles.scss', 'resources/sass/tooltips-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js', 'resources/js/disable-dash-btn-reqs.js', 'resources/js/password-toggle.js', 'resources/js/clickable-full-table-row.js', 'resources/js/reset-search.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    @foreach ($exercises as $exercise)
    <x-delete-exercise-modal :exercise="$exercise"/>
    @endforeach

    @include('layouts.navbar', ['navBtns' => $navElements, 'user' => $user])
    <x-flash-message />

    @yield('content')

    <x-index-dashboard-card :user="$user"/>
    <div id="features" class="container">
        <div class="row mt-5 gx-0">
            <x-index-create-card />
            <x-index-profile-card />
        </div>
        <div class="row mt-5 gx-0">
            <x-index-submitted-ex-card />
            <x-index-created-ex-card />
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h3 class="ex-heading">{{ __("Exercises Created by the Community") }}</h3>
                <p class="subheading pb-2">{{ __("Choose which one you want to solve!") }}</p>
            </div>
            <div class="col-12 exerciseList" id="exerciseList">
                <div class="d-flex justify-content-center">
                    @include('partials._search', ['action' => '/index'], ['placeholder' => __("Search exercises...")])
                
                    <div>
                        <label for="exerciseVisibility" class="form-check-label mt-4">
                            <span class="label-text text-muted">{{ __("Hide Private Exercises") }}</span>
                            <label class="switch">
                                <input type="checkbox" id="exerciseVisibility" name="exercise_visibility">
                                <span class="slider round"></span>
                            </label>
                        </label>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="indexTable" class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th class="text-uppercase">{{ __("Title") }}</th>
                                <th class="text-uppercase">{{ __("Approximate Time") }}</th>
                                <th class="text-uppercase">{{ __("Difficulty") }}</th>
                                <th class="text-uppercase">{{ __("Creator") }}</th>
                                @if ($user->role === 'admin')
                                    <th class="text-uppercase">{{ __("Action") }}</th>
                                @endif
                            </tr>
                        </thead>
                        @forelse ($exercises as $exercise)  
                        <tr class="ex-style-hover {{ $exercise->exercise_visibility === 'private' ? 'private-exercise' : ''}} position-relative">
                            <th scope="row" class="align-middle">
                                @if ($exercise->exercise_visibility === 'private')
                                    <div class="hover-text">
                                        <i class="fas fa-lock text-warning mr-1"></i>
                                        @if (in_array($exercise->id, $userSubmittions))
                                            <i class="fas fa-check-circle text-success fa-lg mr-1"></i>
                                            <a href="#" class="stretched-link text-success" data-target="#privateModal{{ $exercise->id }}">
                                                {{ $exercise->title }}
                                            </a>
                                            <span class="tooltip-text" id="center">{{ __("You already have submitted this exercise.") }}</span>
                                            @else
                                            <a href="#" class="stretched-link" data-target="#privateModal{{ $exercise->id }}">
                                                {{ $exercise->title }}
                                            </a>
                                            <span class="tooltip-text" id="center">{{ __("This exercise is private and requires a password.") }}</span>
                                        @endif
                                    </div>
                                @else
                                    @if (in_array($exercise->id, $userSubmittions))
                                    <div class="hover-text">
                                        <i class="fas fa-check-circle text-success fa-lg"></i>
                                        <a href="/exercises/show/{{ $exercise->id }}" class="stretched-link text-success">{{ $exercise->title }}</a>
                                        <span class="tooltip-text" id="center">{{ __("You already have submitted this exercise.") }}</span>
                                    </div>  
                                    @else
                                        <a href="/exercises/show/{{ $exercise->id }}" class="stretched-link">{{ $exercise->title }}</a>
                                    @endif
                                @endif
                            </th>
                            <td class="align-middle">{{ $exercise->time_number }} {{ __($exercise->time_unit) }}</td>
                            <td class="align-middle @if($exercise->difficulty === 'Easy') easy @elseif($exercise->difficulty === 'Normal') normal @elseif($exercise->difficulty === 'Hard') hard @endif">{{ __($exercise->difficulty) }}</td>
                            <td class="align-middle">{{ $exercise->user->name }}</td>
                            @if ($user->role === 'admin')
                            <td class="align-middle position-relative">
                                <div class="btn-group hover-overlap">
                                    <a href="/exercises/edit/{{ $exercise->id }}" class="btn text-warning">
                                        <i class="fas fa-pen manage-btn fa-lg"></i>
                                    </a>
                                    <a href="/manage/{{ $exercise->id }}/submissions/view" class="btn text-info">
                                        <i class="fas fa-address-book manage-btn fa-lg"></i>
                                    </a>
                                    <button type="button" class="btn text-danger" data-toggle="modal" data-target="#deleteModal{{ $exercise->id }}">
                                        <i class="fas fa-trash-alt manage-btn text-danger fa-lg hover-effect"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        
                        @include('components.private-exercise-access-modal', ['exercise' => $exercise])
                        @empty
                            <tr>
                                <td colspan="{{ $user->role ==='admin' ? 5 : 4 }}" class="text-center align-middle">
                                    @if (request()->has('search'))
                                    <div class="no-exercises-message my-2 mx-2 py-3">
                                        <p><i class="fas fa-info-circle fa-lg align-items-center"></i> {{ __("No matching exercises found for ") . "“" . request()->get('search') . "”"}}  
                                        <button id="resetSearchFilter" class="emptySearchBtn" data-url="{{ url()->current() }}"> {{ __("Show all exercises") }}</button></p>
                                    </div>
                                    @else
                                    <div class="no-exercises-message my-2 mx-2 py-3">
                                        <p><i class="fas fa-info-circle fa-lg align-items-center"></i> {{ __("No Exercises are created, become the 1st person to contribute to the community!") }}</p>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($exercises->count())
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <p class="mb-0 text-muted result-text-responsive pagination-paragraph">
                            {{ __("Showing") }} <span class="fw-semibold">{{ $exercises->firstItem() }}</span> {{ __("to") }} <span class="fw-semibold">{{ $exercises->lastItem() }}</span> {{ __("of") }} <span class="fw-semibold">{{ $exercises->total() }}</span> {{ __("results") }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $exercises->appends(request()->input())->links() }}
                        </div>
                        
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @yield('content')

    @include('layouts.footer')
</body>
</html>
