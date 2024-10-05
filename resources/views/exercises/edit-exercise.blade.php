@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/index', 'label' => __('Dashboard')],
        ['url' => '/exercises/create', 'label' => __('Create Exercise')]
    ];

    $jsvalidi18 = [
    'username_validation' => __("Username must have atleast one uppercase letter and be within 3-255 characters long."),
    'email_validation' => __("The email must be a valid email address."),
    'pass_match_validation' => __("Passwords must match."),
    'password_validation' => __("Password must be within 5-255 characters long and contain at least one number."),
    'correct_validation' => __("Valid"),
    'add_new_input' => __("Enter another input...")
    
];

$popupi18 = [
        "terminal_code" => __("Your code here"),
        ];

$codeSolution = old('code_solution', $exercise->code_solution ?? '');
@endphp
<script>
    const jsvalidi18 = @json($jsvalidi18);
    const popupi18 = @json($popupi18);
    const codeSolution = {!! json_encode($codeSolution) !!};
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exercise->title }} | {{ __("Edit") }}</title>
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    @vite(['resources/sass/exercise-styles.scss', 'resources/sass/tooltips-styles.scss', 'resources/sass/layouts/navbar.scss', 'resources/js/create-ex-env.js', 'resources/js/disable-dash-btn-reqs.js', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js'  ,'resources/js/hamburger-menu.js', 'resources/js/input-field-addons.js', 'resources/js/password-toggle.js', 'resources/js/validate-exercise-creation-code.js'])
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-beautify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/theme-monokai.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/theme-github.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/mode-c_cpp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-searchbox.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="create-bg">
    @include('layouts.navbar', ['navBtns' => $navElements])
    <x-flash-message />
    <x-show-exercise-modal/>  

    @yield('content')
    <div class="container mt-5">
        <div class="bg-create-ex p-4 rounded">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="create-heading">{{ $exercise->title }}</h2>
                    <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteModal{{ $exercise->id }}">
                        <i class="fas fa-trash"></i> {{ __("DELETE") }}
                    </button>
            </div>

            <x-delete-exercise-modal :exercise="$exercise"/>

            <form action="/exercises/{{ $exercise->id }}" method="POST" id="createForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="code_solution" id="code_solution">
                <div class="form-group mt-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="title" id="exerciseTitle" placeholder="{{ __("Enter exercise title...") }}" value="{{ $exercise->title }}">
                        <span class="input-group-text"><i class="fas fa-pen fa-lg"></i></span>
                    </div>
                    @error('title')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group mt-4">
                    <textarea id="exerciseContent" class="form-control textarea-large" name="content" rows="15" placeholder="{{ __("Type exercise content here...") }}">{{ $exercise->content }}</textarea>
                </div>
                @error('content')
                <p class="text-danger small mt-1">{{ $message }}</p>
                @enderror

                <div class="allow-view-automatization mt-4">
                    <h3 class="text-muted"><i class="fas fa-lock"></i> {{ __("Access Control") }}</h3>
                    <div class="">
                        <label for="exerciseVisibility" class="form-check-label">
                            <span class="label-text text-muted">{{ __("Do you want your exercise to be public or private?") }}</span>
                            <label class="switch exSwitch">
                                <input type="checkbox" id="exerciseVisibility" name="exercise_visibility" 
                                {{ old('exercise_visibility', $exercise->exercise_password) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </label>
                    </div>
                </div>
                
                <div id="exerciseVisibilityContainer" style="display: {{ old('exercise_visibility', $exercise->exercise_password) ? 'block' : 'none' }};">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group hint-group">
                                <label for="exercise_password" class="text-muted">{{ __("Exercise Password") }}</label>
                                <div class="input-group">
                                    <input type="password" class="form-control hint-input" name="exercise_password" id="exercise_password" placeholder="{{ __("Enter exercise password...") }}" value="{{ $decryptedExPassword }}">
                                    <span id="toggleExercisePassword" class="togglePassword">
                                        <i class="fas fa-eye fa-lg toggleIcon" id="toggleExercisePasswordIcon"></i>
                                    </span>
                                </div>
                                @error('exercise_password')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="allow-view-automatization mt-4">
                    <h3 class="text-muted"><i class="fas fa-info-circle"></i> {{ __("Hint utility") }}</h3>
                    <div class="form-group">
                        <label for="includeHints" class="form-check-label">
                            <span class="label-text text-muted">{{ __("Do you want hints to be enabled?") }}</span>
                            <label class="switch hint-switch">
                                <input type="checkbox" id="includeHints" name="include_hints" 
                                {{ old('include_hints', $exercise->hint1 && $exercise->hint2 && $exercise->hint3) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </label>
                    </div>
                </div>


                <div id="hintsContainer" 
                style="display: {{ old('include_hints', $exercise->hint1 && $exercise->hint2 && $exercise->hint3) ? 'block' : 'none' }};">
                <div class="row">
                        <div class="col-md-4">
                            <div class="form-group hint-group">
                                <label for="hint1" class="text-muted">{{ __("Hint 1") }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control hint-input" name="hint1" id="hint1" placeholder="{{ __("Enter 1st hint...") }}" value="{{ $exercise->hint1 }}">
                                    <span class="input-group-text"><i class="fas fa-lightbulb input-bulb fa-lg"></i></span>
                                </div>
                                <small class="form-text text-muted">{{ __("Hint 1") }}</small>
                                @error('hint1')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group hint-group">
                                <label for="hint2" class="text-muted">{{ __("Hint 2") }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control hint-input" name="hint2" id="hint2" placeholder="{{ __("Enter 2nd hint...") }}" value="{{ $exercise->hint2 }}">
                                    <span class="input-group-text"><i class="fas fa-lightbulb input-bulb fa-lg"></i></span>
                                </div>
                                <small class="form-text text-muted">{{ __("Hint 2") }}</small>
                                @error('hint2')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group hint-group">
                                <label for="hint3" class="text-muted">{{ __("Hint 3") }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control hint-input" name="hint3" id="hint3" placeholder="{{ __("Enter 3rd hint...") }}" value="{{ $exercise->hint3 }}">
                                    <span class="input-group-text"><i class="fas fa-lightbulb input-bulb fa-lg"></i></span>
                                </div>
                                <small class="form-text text-muted">{{ __("Hint 3") }}</small>
                                @error('hint3')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>       
                    <div class="ex-tool-tip-wrapper automatic-check-tool-tip">
                        <i class="fa-solid fa-question"></i>
                        <div class="ex-tool-tip">
                            {{ __("These hints will be available for the user if they get stuck and need extra help or hints to understand the essence or a problem of the exercise.") }}
                        </div>
                    </div>
                <div class="difficulty-section">
                    <h3 class="text-muted"><i class="fas fa-puzzle-piece"></i> {{ __("What is the difficulty of your exercise?") }}</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="difficulty" id="difficultyEasy" value="easy" {{$exercise->difficulty == 'Easy' ? 'checked' : '' }}>
                        <label class="form-check-label text-success" for="difficultyEasy">{{ __("Easy") }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="difficulty" id="difficultyNormal" value="normal" {{$exercise->difficulty == 'Normal' ? 'checked' : '' }}>
                        <label class="form-check-label text-primary" for="difficultyNormal">{{ __("Normal") }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="difficulty" id="difficultyHard" value="hard" {{$exercise->difficulty == 'Hard' ? 'checked' : '' }}>
                        <label class="form-check-label text-danger" for="difficultyHard">{{ __("Hard") }}</label>
                    </div>
                    @error('difficulty')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="time-required-section mt-4">
                    <h3 class="text-muted"><i class="fas fa-clock"></i> {{ __("Approximate Time Required") }}</h3>
                    <div class="input-group">
                        <input type="number" class="form-control" name="time_number" id="timeRequired" min="0" placeholder="{{ __("Enter time...") }}" value="{{ $exercise->time_number }}">
                        <select class="form-select" name="time_unit" id="timeUnit">
                            <option value="minutes" {{ $exercise->time_unit == 'minutes' ? 'selected' : '' }}>{{ __("Minutes") }}</option>
                            <option value="hours" {{ $exercise->time_unit == 'hours' ? 'selected' : '' }}>{{ __("Hours") }}</option>
                            <option value="days" {{$exercise->time_unit == 'days' ? 'selected' : '' }}>{{ __("Days") }}</option>
                        </select>
                    </div>
                    @error('time_number')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="allow-view-automatization mt-4">
                    <h3 class="text-muted"><i class="fas fa-clipboard-check"></i> {{ __("Should automatic testing be viewable by the public upon submitting the exercise?") }}</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="allow_automatic_check_view" id="allowAutomaticCheckViewYes" value="1"{{ $exercise->allow_automatic_check_view == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-success" for="allowAutomaticCheckViewYes">{{ __("Yes") }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="allow_automatic_check_view" id="allowAutomaticCheckViewNo" value="0" {{ $exercise->allow_automatic_check_view == '0' ? 'checked' : '' }}>
                        <label class="form-check-label text-danger" for="allowAutomaticCheckViewNo">{{ __("No") }}</label>
                    </div>
                    @error('allow_automatic_check_view')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="ex-tool-tip-wrapper automatic-check-tool-tip public-tool-tip">
                    <i class="fa-solid fa-question"></i>
                    <div class="ex-tool-tip">
                        {{ __("If set to true, the automatic checking analysis will be available in public for everyone in their submitted exercises page. By setting this to false, only the exercise author will have full display of automatic check analysis for each submittion.") }}
                    </div>
                </div>                        
                <div class="allow-view-automatization">
                    <h3 class="text-muted"><i class="fas fa-clipboard-check"></i> {{ __("Should automatic testing be viewable each time program is ran?") }}</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="allow_automatic_check_run" id="allowAutomaticCheckRunYes" value="1"{{ $exercise->allow_automatic_check_run == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-success" for="allowAutomaticCheckRunYes">{{ __("Yes") }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="allow_automatic_check_run" id="allowAutomaticCheckRunNo" value="0" {{ $exercise->allow_automatic_check_run == '0' ? 'checked' : '' }}>
                        <label class="form-check-label text-danger" for="allowAutomaticCheckRunNo">{{ __("No") }}</label>
                    </div>
                    @error('allow_automatic_check_run')
                    <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="ex-tool-tip-wrapper automatic-check-tool-tip public-tool-tip">
                    <i class="fa-solid fa-question"></i>
                    <div class="ex-tool-tip">
                        {{ __("If set to true, the automatic check feature will display the results each time the program is executed. If not, the results of each execution are not displayed.") }}
                    </div>
                </div>

                <div class="difficulty-section d-inline-flex align-items-center mb-2">
                    <h3 class="text-muted mb-0"><i class="fas fa-code"></i> {{ __("Code Solution") }}</h3>
                    <div class="exclamation-icon ms-2" data-bs-toggle="modal" data-bs-target="#infoModal">
                        <i class="bi bi-info-circle-fill fs-2"></i>
                    </div>    
                </div>

                <div class="container">
                    <div id="editor" class="editor"></div>
                    <pre id="output"></pre>
                    
                    <div class="output-container" id="responsive-copyright-wrapper">
                        <div class="output-header d-flex justify-content-between align-items-center">
                            <div class="terminal-icon">
                                <i class="fas fa-terminal"></i>{{ __("Compiler Result") }}
                            </div>
                            <div class="switch-wrapper d-flex">
                                <span class="label-text text-muted switch-text">{{ __("Switch Editor Theme") }}</span>
                                <label class="switch compilator-theme">
                                    <input type="checkbox" id="toggleThemeBtn">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div id="console-output" class="output-content">
                            @if (session('compilationError'))
                            <div class="alert alert-danger">
                                <strong>{{ __("Compilation Error") }}:</strong>
                                <pre>{{ session('compilationError') }}</pre>
                            </div>
                        @endif
                            @if (session('check1Result'))
                                <div class="result-container">
                                    <p>{{ __("Test Case Input") }} 1:
                                        @if (session('check1Result')['passed'])
                                            <span class="text-success"><i class="fas fa-check-circle"></i> {{ __("Correct Answer") }}!</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times-circle"></i> {{ __("Incorrect") }}!</span>
                                        @endif
                                    </p>
                                    <p>{{ __("Result") }}: <span class="{{ session('check1Result')['passed'] ? 'text-success' : 'text-danger' }}">{{ session('check1Result')['actualOutput'] ?: 'No output received' }}</span></p>
                                    <p>{{ __("Expected Answer") }}: <span class="text-success">{{ session('check1Result')['expectedAnswer'] }}</span></p>
                                </div>
                            @endif
                    
                            @if (session('check2Result'))
                                <div class="result-container">
                                    <p>{{ __("Test Case Input") }} 2:
                                        @if (session('check2Result')['passed'])
                                            <span class="text-success"><i class="fas fa-check-circle"></i> {{ __("Correct Answer") }}!</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times-circle"></i> {{ __("Incorrect") }}!</span>
                                        @endif
                                    </p>
                                    <p>{{ __("Result") }}: <span class="{{ session('check2Result')['passed'] ? 'text-success' : 'text-danger' }}">{{ session('check2Result')['actualOutput'] ?: 'No output received' }}</span></p>
                                    <p>{{ __("Expected Answer") }}: <span class="text-success">{{ session('check2Result')['expectedAnswer'] }}</span></p>
                                </div>
                            @endif
                    
                            @if (session('check3Result'))
                                <div class="result-container">
                                    <p>{{ __("Test Case Input") }} 3:
                                        @if (session('check3Result')['passed'])
                                            <span class="text-success"><i class="fas fa-check-circle"></i> {{ __("Correct Answer") }}!</span>
                                        @else
                                            <span class="text-danger"><i class="fas fa-times-circle"></i> {{ __("Incorrect") }}!</span>
                                        @endif
                                    </p>
                                    <p>{{ __("Result") }}: <span class="{{ session('check3Result')['passed'] ? 'text-success' : 'text-danger' }}">{{ session('check3Result')['actualOutput'] ?: 'No output received' }}</span></p>
                                    <p>{{ __("Expected Answer") }}: <span class="text-success">{{ session('check3Result')['expectedAnswer'] }}</span></p>
                                </div>
                            @endif
                        </div>
                
                        <div id="input-container" class="input-container" style="display: none;">
                            <div id="input-fields"></div>
                            <button id="submitInputs" class="btn btn-primary mt-2">{{ __("Proceed") }}</button>
                        </div>
                    </div>
                </div>
                

                <div class="check-section mt-4">
                    <h3 class="text-muted"><i class="fa fa-cogs" aria-hidden="true"></i> {{ __("Automatization Checking") }}</h3>
                    <div class="ex-tool-tip-wrapper automatic-check-tool-tip public-tool-tip">
                        <i class="fa-solid fa-question"></i>
                        <div class="ex-tool-tip">
                            {{ __("Additional input feature is there for more complex exercises that cannot be checked without the use of more inputs to get to the desired result. Please note that they should be in the same order as written below as any other way, automatization check may not produce accurate results.") }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group check-group">
                                <label for="check1" class="text-muted">{{ __("#1 Check") }}</label>
                                <div class="input-group" id="check1Inputs" data-input-vals='@json(old("check1", json_decode($exercise->check1)))'>
                                    @php
                                        $check1Inputs = old('check1', json_decode($exercise->check1));
                                    @endphp
                                
                                    @foreach ($check1Inputs as $check1Input)
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" name="check1[]" placeholder="{{ __("Enter another input...") }}" value="{{ $check1Input }}">
                                            <span class="input-group-text check-ans-icon-bg"><i class="fa-solid fa-question fa-lg"></i></span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-link add-input-btn" data-target="check1Inputs">{{ __("Add Another Input") }}</button>
                                    <button type="button" class="btn btn-link text-danger add-input-btn" data-target="check1Inputs" style="display: none;">{{ __("Remove") }}</button>
                                </div>
                                <small class="form-text text-muted">{{ __("#1 Check") }}</small>
                                <p class="helper-text"></p>
                                @if ($errors->has('check1.0'))
                                <p class="text-danger small mt-1">{{ $errors->first('check1.0') }}</p>
                            @elseif ($errors->has('check1.*'))
                                <p class="text-danger small mt-1">{{ $errors->first('check1.*') }}</p>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group check-group">
                                <label for="check1Answer" class="text-muted">{{ __("#1 Check Answer") }}</label>
                                <div class="input-group ">
                                    <input type="text" class="form-control" name="check1_answer" id="check1Answer" placeholder="{{ __("Enter 1st input answer...") }}" value="{{ $exercise->check1_answer }}">
                                    <span class="input-group-text check-ans-icon-bg"><i class="fas fa-check fa-lg"></i></span>
                                </div>
                                <small class="form-text text-muted">{{ __("#1 Check Answer") }}</small>
                                @error('check1_answer')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group check-group">
                                <label for="check2" class="text-muted">{{ __("#2 Check") }}</label>
                                <div class="input-group" id="check2Inputs" data-input-vals='@json(old("check2", json_decode($exercise->check2)))'>
                                    @php
                                        $check2Inputs = old('check2', json_decode($exercise->check2));
                                    @endphp
                                
                                    @foreach ($check2Inputs as $check2Input)
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" name="check2[]" placeholder="{{ __("Enter another input...") }}" value="{{ $check2Input }}">
                                            <span class="input-group-text check-ans-icon-bg"><i class="fa-solid fa-question fa-lg"></i></span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-link add-input-btn" data-target="check2Inputs">{{ __("Add Another Input") }}</button>
                                    <button type="button" class="btn btn-link text-danger add-input-btn" data-target="check2Inputs" style="display: none;">{{ __("Remove") }}</button>
                                </div>
                                <small class="form-text text-muted">{{ __("#2 Check") }}</small>
                                <p class="helper-text"></p>
                                @if ($errors->has('check2.0'))
                                <p class="text-danger small mt-1">{{ $errors->first('check2.0') }}</p>
                            @elseif ($errors->has('check2.*'))
                                <p class="text-danger small mt-1">{{ $errors->first('check2.*') }}</p>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group check-group">
                                <label for="check2Answer" class="text-muted">{{ __("#2 Check Answer") }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="check2_answer" id="check2Answer" placeholder="{{ __("Enter 2nd input answer...") }}" value="{{ $exercise->check2_answer }}">
                                    <span class="input-group-text check-ans-icon-bg"><i class="fas fa-check fa-lg"></i></span>
                                </div>
                                <small class="form-text text-muted">{{ __("#2 Check Answer") }}</small>
                                @error('check2_answer')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group check-group">
                                <label for="check3" class="text-muted">{{ __("#3 Check") }}</label>
                                <div class="input-group" id="check3Inputs" data-input-vals='@json(old("check3", json_decode($exercise->check3)))'>
                                    @php
                                        $check3Inputs = old('check3', json_decode($exercise->check3));
                                    @endphp
                                
                                    @foreach ($check3Inputs as $check3Input)
                                        <div class="input-group mt-2">
                                            <input type="text" class="form-control" name="check3[]" placeholder="{{ __("Enter another input...") }}" value="{{ $check3Input }}">
                                            <span class="input-group-text check-ans-icon-bg"><i class="fa-solid fa-question fa-lg"></i></span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-link add-input-btn" data-target="check3Inputs">{{ __("Add Another Input") }}</button>
                                    <button type="button" class="btn btn-link text-danger add-input-btn" data-target="check3Inputs" style="display: none;">{{ __("Remove") }}</button>
                                </div>
                                <small class="form-text text-muted">{{ __("#3 Check") }}</small>
                                <p class="helper-text"></p>
                                @if ($errors->has('check3.0'))
                                <p class="text-danger small mt-1">{{ $errors->first('check3.0') }}</p>
                            @elseif ($errors->has('check3.*'))
                                <p class="text-danger small mt-1">{{ $errors->first('check3.*') }}</p>
                            @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group check-group">
                                <label for="check3Answer" class="text-muted">{{ __("#3 Check Answer") }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="check3_answer" id="check3Answer" placeholder="{{ __("Enter 3rd input answer...") }}" value="{{ $exercise->check3_answer }}">
                                    <span class="input-group-text check-ans-icon-bg"><i class="fas fa-check fa-lg"></i></span>
                                </div>
                                <small class="form-text text-muted">{{ __("#3 Check Answer") }}</small>
                                @error('check3_answer')
                                <p class="text-danger small mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-create-exercise px-5 btn-secondary btn-lg" data-loading-text={{ __("Saving...") }}>{{ __("Save") }}</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
