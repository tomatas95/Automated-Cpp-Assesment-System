@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/index', 'label' => __('Dashboard')],
        ['url' => '/exercises/create', 'label' => __('Create Exercise')]
    ];

    $easyIcon = asset('images/difficulty-colors/easy-diff-icon.png');
    $normalIcon = asset('images/difficulty-colors/normal-diff-icon.png');
    $hardIcon = asset('images/difficulty-colors/hard-diff-icon.png');

    $checkInputs = [$exercise->check1, $exercise->check2, $exercise->check3];
    $checkAnswers = [$exercise->check1_answer, $exercise->check2_answer, $exercise->check3_answer];

    $allowAutomaticCheckRun = $exercise->allow_automatic_check_run ? '1' : '0';

    $popupi18 = [
    'themepopup' => __("Theme changed successfully"),
    'runpopup' => __("Program ran successfully"),
    'runFirstpopup' => __("Please run the program first"),
    'submitFirstpopup' => __("Please run the program at least once"),
    'secondspopup' => __("seconds spent processing"),
    'memoryUsedpopup' => __("KB Memory Used"),
    'errorpopup' => __("Error occurred"),
    'error429' => __("Error: 429. You sent too many requests to the server"),
    // CORS Error, since we're using heroku
    'test_case' => __("Test Case"),
    'test_case_input' => __("Test Case Input"),
    "test_case_result" => __("Result"),
    "test_case_compilation_time" => __("Compilation Time"),
    "test_case_expected_answer" => __("Expected Answer"),
    "test_case_time_unit" => __("seconds"),
    "test_case_memory_use" => __("Memory Usage"),
    "test_case_verdict" => __("Correct Answer"),
    "test_case_cpu" => __("CPU Time"),
    "total" => __("Total"),
    "correct" => __("Correct"),
    "terminal_code" => __("Your code here"),
    "automatization_load" => __("Processing automatic checks, please wait..."),
    "variable_placeholder" => __("Enter variable's value here..."),
    "compilation_time" => __("Seconds to compile"),
    "hardcoded_method_error" => __("==== Error ===="),
    "criteria_not_followed_for_hardcoded_method" => __("It seems like you're opting for using hardcoded values for your execution. Unfortunately there is no variable that follows the criteria for an automatization check to test your code. Please refer to the blue information icon for more information how inputs should be written if you choose to opt for hardcoded value method."),
    "compilation_error" => __("Compilation Error"),
    "compilation_submit_error" => __("You cannot submit an exercise with a compilation error."),
    "cout_input" => __("Enter")
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>iCodeNET | {{ $exercise->title }}</title>
    @vite(['resources/sass/show-exercise.scss', 'resources/js/coding-env.js','resources/sass/tooltips-styles.scss',  'resources/js/jdoodle-api.js', 'resources/js/show-icon-color.js', 'resources/js/responsive-btn-layout-change.js' ,'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js'])
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-beautify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/theme-monokai.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/theme-github.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/mode-c_cpp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-searchbox.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    <script>
        const EASY_ICON = '{{ $easyIcon }}';
        const NORMAL_ICON = '{{ $normalIcon }}';
        const HARD_ICON = '{{ $hardIcon }}';
        const allowAutomaticCheckRun = {{ $allowAutomaticCheckRun ? 'true' : 'false' }};

        const checkInputs = @json($checkInputs);
        const checkAnswers = @json($checkAnswers);
        const popupi18 = @json($popupi18);
    </script>
</head>
<body>
    @include('layouts.navbar', ['navBtns' => $navElements])
    <x-flash-message />
    <x-show-exercise-modal/>  

    @yield('content')
    <div class="button-wrapper py-4 full-width-bg button-grid-container">
        <div class="button-popup-container">
            <button class="btn btn-light" id="runBtn">
                <i class="fas fa-play text-success"></i> {{ __("Run") }}
            </button>
            <div class="popup" id="runPopup" data-popup-type="run">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentRun"></div>
            </div>
        </div>
    
        <div class="button-popup-container">
            <button class="btn btn-light" id="toggleThemeBtn">
                <i class="fa-solid fa-terminal text-warning"></i> {{ __("Theme") }}
            </button>
            <div class="popup" id="themePopup" data-popup-type="theme">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentTheme"></div>
            </div>
        </div>
    
        <div class="button-popup-container">
            <button class="btn btn-light" id="execTimeBtn">
                <i class="fas fa-clock text-info"></i> {{ __("CPU Time") }}
            </button>
            <div class="popup" id="cpuTimePopup" data-popup-type="cpu-time">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentCpuTime"></div>
            </div>
        </div>
    
        <div class="button-popup-container">
            <button class="btn btn-light btn-responsive" id="compilerTimeBtn">
                <i class="fas fa-hourglass text-danger"></i> {{ __("Compilation Time") }}
            </button>
            <div class="popup" id="compilerTimePopup" data-popup-type="compiler-time">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentCompilationTime"></div>
            </div>
        </div>
    
        <div class="button-popup-container">
            <button class="btn btn-light btn-responsive" id="memoryBtn">
                <i class="fas fa-memory text-secondary"></i> {{ __("Memory") }}
            </button>
            <div class="popup" id="memoryPopup" data-popup-type="memory">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentMemory"></div>
            </div>
        </div>
    
        <div class="button-popup-container">
            <form id="submissionForm" action="/exercises/{{ $exercise->id }}/submit" method="POST">
                @csrf
                <input type="hidden" name="code" id="code">
                <input type="hidden" name="test_result_1" id="test_result_1">
                <input type="hidden" name="test_result_2" id="test_result_2">
                <input type="hidden" name="test_result_3" id="test_result_3">
                <input type="hidden" name="cpu_time" id="cpu_time">
                <input type="hidden" name="compilation_time" id="compilation_time">
                <input type="hidden" name="memory_time" id="memory_time">
                <input type="hidden" name="auto_check_correct_cases" id="auto_check_correct_cases">
                <button type="submit" class="btn btn-light btn-responsive" id="submitBtn" data-loading-text="Submitting...">
                    <i class="fas fa-upload text-primary" id="submitIcon"></i> {{ __("Submit") }}
                </button>
            </form>
            <div class="popup" id="submitPopup" data-popup-type="submit">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentSubmit"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid px-0 exercise-info-wrapper">
        <div class="row mx-0">
            <div class="col-md-6 d-flex flex-column">
                <div class="exercise-difficulty d-flex align-items-center">
                    <div>
                        <span><strong>{{ __("Exercise Difficulty") }}:</strong></span>
                        <img src="{{ asset('images/difficulty-colors/normal-diff-icon.png') }}" alt="Difficulty Icon" class="difficulty-icon px">
                        <span class="difficulty-level position-relative" data-difficulty="{{ $exercise->difficulty }}">{{ __("$exercise->difficulty") }}</span>
                    </div>
                </div>
                <div class="exercise-title px-3 my-2">
                    <img src="{{ asset('images/show-exercise-page/folder-icon.png') }}" alt="Folder Icon" class="folder-icon">
                    <span><strong>{{ $exercise->title }}</strong></span>
                </div>
                <div class="exercise-info-scrollable">
                    <div class="exercise-info-content">
                        {!! $exercise->content !!}
                    </div>
                </div>
                <div class="hints-section mt-3">
                    @if (is_null($exercise->hint1) && is_null($exercise->hint2) && is_null($exercise->hint3))
                    <div class="hints-disabled d-flex align-items-center">
                        <i class="fas fa-info-circle mr-2 text-info"></i>
                        <span class="text-info">{{ __("Hints are disabled for this exercise.") }}</span>
                    </div>
                    @else
                    <div id="hint1" class="hint-item">
                        <button class="btn btn-link hint-btn d-flex align-items-center" data-toggle="collapse" data-target="#hint1-content" aria-expanded="false" aria-controls="hint1-content">
                            <i class="fas fa-lightbulb mr-2"></i> <span>{{ __("Hint 1") }}</span>
                            <i class="fas fa-chevron-down ml-auto"></i>
                        </button>
                        <div id="hint1-content" class="collapse">
                            <p>{{ $exercise->hint1 }}</p>
                        </div>
                    </div>
                    <div id="hint2" class="hint-item">
                        <button class="btn btn-link hint-btn d-flex align-items-center" data-toggle="collapse" data-target="#hint2-content" aria-expanded="false" aria-controls="hint2-content">
                            <i class="fas fa-lightbulb mr-2"></i> <span>{{ __("Hint 2") }}</span>
                            <i class="fas fa-chevron-down ml-auto"></i>
                        </button>
                        <div id="hint2-content" class="collapse">
                            <p>{{ $exercise->hint2 }}</p>
                        </div>
                    </div>
                    <div id="hint3" class="hint-item">
                        <button class="btn btn-link hint-btn d-flex align-items-center" data-toggle="collapse" data-target="#hint3-content" aria-expanded="false" aria-controls="hint3-content">
                            <i class="fas fa-lightbulb mr-2"></i> <span>{{ __("Hint 3") }}</span>
                            <i class="fas fa-chevron-down ml-auto"></i>
                        </button>
                        <div id="hint3-content" class="collapse">
                            <p>{{ $exercise->hint3 }}</p>
                        </div>
                    </div>
                    @endif
                    @if ($exercise->allow_automatic_check_run)
                    <div class="exercise-title px-3 my-2">
                        <i class="fas fa-cogs folder-icon"></i>
                        <span><strong>{{ __("Automatization Checking") }}</strong></span>
                    </div>
                    <div class="result-item" id="automatization-load">
                        <div id="loadingAutomatization" class="loading-indicator" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> {{ __("Processing automatic checks, please wait...") }}
                        </div>
                    </div>
                    @endif
                </div>
                <span class="text-muted copyright" id="copyright-responsive">{{ __("Copyright") }} &copy; {{ __("2024 iCodeNET all rights reserved") }}</span>
            </div>
            <div class="col-md-6 d-flex flex-column">
                <div class="code-section d-flex align-items-center">
                    <img src="{{ asset('images/show-exercise-page/code-icon.png') }}" alt="Code Icon" class="code-icon">
                    <div>
                        <span class="position-relative code-pos"><strong>{{ __("Code") }}</strong></span>
                    </div>
                </div>
                <div class="programming-language d-flex align-items-center justify-content-between px-4 my-2">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/show-exercise-page/programming-lang-icon.png') }}" alt="Programming Language Icon" class="language-icon">
                        <span class="ms-2"><strong>{{ __("Programming Language") }}: <span class="text-muted">C++</span></strong></span>
                    </div>
                    <div class="exclamation-icon" data-bs-toggle="modal" data-bs-target="#infoModal">
                        <i class="bi bi-info-circle-fill fs-2"></i>
                    </div>
                </div>
                <div id="editor" class="editor">
                </div>
                <pre id="output"></pre>
                <div class="output-container" id="responsive-copyright-wrapper">
                    <div class="output-header"><i class="fas fa-terminal"></i>{{ __("Compiler Result") }}</div>
                    <div id="console-output" class="output-content"></div>
                    <div id="input-container" class="input-container" style="display: none;">
                        <div id="input-fields"></div>
                        <button id="submitInputs" class="btn btn-primary mt-2">{{ __("Proceed") }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
