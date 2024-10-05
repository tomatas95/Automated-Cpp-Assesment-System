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
    <title>iCodeNET | {{ __($exercise->translated_title) }} {{ __("Submittions") }}</title>
    @vite(['resources/sass/profile-styles.scss',  'resources/sass/manage-submissions-view.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js'  ,'resources/js/hamburger-menu.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
</head>
<body class="create-bg">
    @include('layouts.navbar', ['navBtns' => $navElements])
    
    @yield('content')
    <x-flash-message />
    <div class="container my-5 content-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="submission-heading">{{ __("Submittions") }}</h2>
            <i class="fas fa-user fa-2x submission-user-icon"></i>
        </div>
        <h1 class="exercise-heading">{{ $exercise->title }}</h1>
        <div class="col-12">
            <div class="d-flex justify-content-center">
                @include('partials._search', ['action' => $searchURL], ['placeholder' => __('Search submittions...')])
            </div>
            <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th class="text-uppercase">{{ __("Name") }}</th>
                    <th class="text-uppercase">{{ __("Automatization Check") }}</th>
                    <th class="text-uppercase">{{ __("Submittion Date") }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $submission)  
                <tr style="transform: rotate(0);">
                    <th scope="row" class="align-middle"><a href="/submissions/{{ $submission->exercise->id }}/view/{{ $submission->id }}" class="stretched-link">{{ $submission->user->name }}</a></th>
                    <td class="align-middle">{{ $submission->auto_check_correct_cases }} {{ __("Correct") }}</td>
                    <td class="align-middle">{{ $submission->updated_at }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">
                        <div class="no-exercises-message my-2 mx-2 py-3">
                            <p><i class="fas fa-info-circle fa-lg align-items-center"></i>{{ __("There has been no submittions for this exercise yet.") }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
        @if($submissions->count())
        <div class="row mt-4">
            <div class="col-md-6 d-flex align-items-center">
                <p class="mb-0 text-muted">
                    {{ __("Showing") }} <span class="fw-semibold">{{ $submissions->firstItem() }}</span> {{ __("to") }} <span class="fw-semibold">{{ $submissions->lastItem() }}</span> {{ __("of") }} <span class="fw-semibold">{{ $submissions->total() }}</span> {{ __("results") }}
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    {{ $submissions->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
@yield('content')

@include('layouts.footer')
</div>
</body>
</html>
