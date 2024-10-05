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
    <title>iCodeNET | {{ __("User Management") }}</title>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    @vite(['resources/sass/index-styles.scss', 'resources/sass/user-management-styles.scss', 'resources/sass/layouts/footer.scss', 'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js'  ,'resources/js/hamburger-menu.js', 'resources/js/disable-dash-btn-reqs.js', 'resources/js/reset-search.js'])
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
    @foreach ($users as $user)
        <x-delete-user-modal :user="$user"/>
    @endforeach

    @include('layouts.navbar', ['navBtns' => $navElements, 'user' => $userData])
    <x-flash-message />

    @yield('content')

    <div class="container-fluid dashboard-intro">
        <div class="row align-items-center px-3">
            <div class="col-md-6 text-center text-md-left">
                <div class="d-flex align-items-center justify-content-center desktop-mt-5">
                    <img src="{{ $userData->profile_picture ? asset('storage/' . $userData->profile_picture) : asset('images/profile-pic.png') }}" alt="Profile Picture" class="profile-pic">
                    <div>
                        <span class="dashboard-greeting">{{ __("Welcome") }}, @auth<strong>{{ auth()->user()->name }}</strong></span>@endauth
                    </div>
                </div>
                <p class="dashboard-intro-text px-3 desktop-mt-4 desktop-mb-5">{{ __("This page is for administrators to manage users for variety of purposes. From saving each person's data to be able to view if certain rules are not being followed, to punishing bad actors or individuals with malicious intent.") }}
                </p>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/user-list-page/admin-page-pic.png') }}" alt="Profile Ilustration" class="img-fluid intro-image">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h3 class="ex-heading">{{ __("User Management") }}</h3>
                <p class="subheading pb-2">{{ __("List of all registered in the system users") }}</p>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    @include('partials._search', ['action' => '/admin/user-list'], ['placeholder' => __("Search users...")])
                </div>
                <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-uppercase">{{ __("ID") }}</th>
                            <th class="text-uppercase">{{ __("Profile Picture") }}</th>
                            <th class="text-uppercase">{{ __("Name") }}</th>
                            <th class="text-uppercase">{{ __("Email") }}</th>
                            <th class="text-uppercase">{{ __("Role") }}</th>
                            <th class="text-uppercase">{{ __("Registration Method") }}</th>
                            <th class="text-uppercase">{{ __("Creation Date") }}</th>
                            <th class="text-uppercase">{{ __("Updated Date") }}</th>
                            <th class="text-uppercase">{{ __("Action") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)  
                        <tr class="ex-style-hover">
                            <th scope="row" class="align-middle">
                                <a href="/admin/profile/{{ $user->id }}" class="stretched-link">{{ $user->id }}</a>
                            </th>
                            <td class="align-middle">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/profile-pic.png') }}" alt="{{ $user->name }}" class="img-fluid rounded-circle user-profile-pic">
                            </td>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ __("$user->role") }}</td>
                            <td class="allign-middle">
                                @if ($user->google_id && $user->github_id)
                                    {{ __("Gmail & GitHub Authorization") }}
                                @elseif($user->github_id)
                                    {{ __("GitHub Authorization") }}
                                @elseif ($user->google_id)
                                    {{ __("Gmail Authorization") }}
                                @else
                                {{ __("Registration Form") }}
                                @endif

                            </td>
                            <td class="align-middle">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="align-middle">{{ $user->updated_at->format('Y-m-d') }}</td>
                            <td class="align-middle position-relative">
                                <div class="btn-group hover-overlap">
                                    <a href="/admin/profile/{{ $user->id }}/" class="btn text-info">
                                        <i class="fas fa-user fa-lg"></i>
                                    </a>
                                    <a href="/admin/profile/edit/{{ $user->id }}" class="btn text-warning">
                                        <i class="fas fa-pen manage-btn fa-lg"></i>
                                    </a>
                                    <button type="button" class="btn text-danger" data-toggle="modal" data-target="#deleteModal{{ $user->id }}">
                                        <i class="fas fa-trash-alt manage-btn text-danger fa-lg hover-effect"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center align-middle">
                                @if (request()->has('search'))
                                <div class="no-exercises-message my-2 mx-2 py-3">
                                    <p><i class="fas fa-info-circle fa-lg align-items-center"></i> {{ __("No matching users found for ") . "“" . request()->get('search') . "”"}}  
                                    <button id="resetSearchFilter" class="emptySearchBtn" data-url="{{ url()->current() }}"> {{ __("Show all users") }}</button></p>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
                @if($users->count())
                <div class="row">
                    <div class="col-md-6 d-flex align-items-center">
                        <p class="mb-0 text-muted pagination-paragraph">
                            {{ __("Showing") }} <span class="fw-semibold">{{ $users->firstItem() }}</span> {{ __("to") }} <span class="fw-semibold">{{ $users->lastItem() }}</span> {{ __("of") }} <span class="fw-semibold">{{ $users->total() }}</span> {{ __("results") }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end">
                            {{ $users->appends(request()->input())->links() }}
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
