@php
    $navElements = [
        ['url' => '/', 'label' => __('News')],
        ['url' => '/index', 'label' => __('Dashboard')],
        ['url' => '/exercises/create', 'label' => __('Create Exercise')]
    ];

    $easyIcon = asset('images/difficulty-colors/easy-diff-icon.png');
    $normalIcon = asset('images/difficulty-colors/normal-diff-icon.png');
    $hardIcon = asset('images/difficulty-colors/hard-diff-icon.png');

    $popupi18 = [
    'themepopup' => __("Theme changed successfully"),
    'runpopup' => __("Program ran successfully"),
    'runFirstpopup' => __("Please run the program first"),
    'submitFirstpopup' => __("Please run the program at least once"),
    'secondspopup' => __("Seconds to compile"),
    'memoryUsedpopup' => __("KB Memory Used"),
    'errorpopup' => __("Error occurred"),
    "variable_placeholder" => __("Enter variable's value here...")
];

function normalizeOutput($output) {
    return preg_replace('/\s+/', ' ', trim($output));
}
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>iCodeNET | {{ $exercise->title }}</title>
    @vite(['resources/sass/show-exercise.scss', 'resources/js/view-coding-env.js', 'resources/js/show-icon-color.js' ,'resources/sass/layouts/navbar.scss', 'resources/sass/hamburger-styles.scss', 'resources/js/navbar-dropdown-toggle.js' ,'resources/js/hamburger-menu.js'])
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/web-logo.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-language_tools.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-beautify.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/mode-c_cpp.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ext-searchbox.js"></script>
    <script>
        const EASY_ICON = '{{ $easyIcon }}';
        const NORMAL_ICON = '{{ $normalIcon }}';
        const HARD_ICON = '{{ $hardIcon }}';

        const popupi18 = @json($popupi18);
    </script>
</head>
<body>
    @include('layouts.navbar', ['navBtns' => $navElements])

    @yield('content')

    <div class="button-wrapper py-4 full-width-bg">
        <div class="button-popup-container">
            <button class="btn btn-light mx-2" id="runBtn">
                <i class="fas fa-play text-success"></i> {{ __("Run") }}
            </button>
            <div class="popup" id="runPopup" data-popup-type="run">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentRun"></div>
            </div>
        </div>
    
        <div class="button-popup-container">
            <button class="btn btn-light mx-2" id="toggleThemeBtn">
                <i class="fa-solid fa-terminal text-warning"></i> {{ __("Theme") }}
            </button>
            <div class="popup" id="themePopup" data-popup-type="theme">
                <div class="popup-arrow"></div>
                <div class="popup-content" id="popupContentTheme"></div>
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
                    <div class="exercise-title px-3 my-2">
                        <i class="fas fa-cogs folder-icon"></i>
                        <span><strong>{{ __("Automatization Checking") }}</strong></span>
                    </div>
                    <div class="result-item">
                        <div class="accordion" id="resultsAccordion">
                            @if($exercise->allow_automatic_check_view || $exercise->user_id === auth()->id())
                                @foreach ([1, 2, 3] as $index)
                                    @php
                                        $inputs = json_decode($exercise['check' . $index]);
                                        $output = $submission['test_result_' . $index];
                                        $expectedOutput = $exercise['check' . $index . '_answer'];
                                        $isCorrect = normalizeOutput($output) === normalizeOutput($expectedOutput);
                                        @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $index }}">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="true" aria-controls="collapse{{ $index }}">
                                                {!! $isCorrect ? '<i class="fas fa-check-circle text-success me-2"></i>' : '<i class="fas fa-times-circle text-danger me-2"></i>' !!} {{ __("Test Case") }} {{ $index }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $index }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $index }}" data-bs-parent="#resultsAccordion">
                                            <div class="accordion-body">
                                                <p><strong class="output-result">{{ __("Test Case Input") }}:</strong>
                                                    @if (count($inputs) > 1)
                                                    <ul>
                                                        @foreach ($inputs as $input)
                                                            <li>{{ $input }}</li>
                                                        @endforeach
                                                    </ul>
                                                    @else
                                                    {{ $inputs[0] }}
                                                    @endif
                                                </p>
                                                <p><strong class="output-result">{{ __("Result") }}:</strong> {{ $output }}</p>
                                                <p><strong class="output-result">{{ __("Expected Answer") }}:</strong> {{ $expectedOutput }}</p>
                                                <p><strong class="output-result">{{ __("Correct Answer") }}:</strong> {!! $isCorrect ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="total-result">
                                    <p class="text-info">{{ __("Automatic result analysis is disabled for this exercise by the author.") }}</p>
                                </div>
                            @endif
                        </div>
                        @php
                        $correctCount = $submission->auto_check_correct_cases[0];
                        $totalCases = $submission->auto_check_correct_cases[2];

                        if ($correctCount === $totalCases) {
                            $colorClass = 'text-success';
                        } elseif ($correctCount === $totalCases - 1) {
                            $colorClass = 'text-warning';
                        } else {
                            $colorClass = 'text-danger';
                        }
                        @endphp
                        <div class="total-result">
                            <p class="{{ $colorClass }}">{{ __("Total") }}: {{ $submission->auto_check_correct_cases }} {{ __("Correct") }}</p>
                        </div>
                    </div>
                    <span class="text-muted copyright">{{ __("Copyright") }} &copy; {{ __("2024 iCodeNET all rights reserved") }}</span>
                </div>
            </div>
            <div class="col-md-6 d-flex flex-column">
                <div class="code-section d-flex align-items-center">
                    <img src="{{ asset('images/show-exercise-page/code-icon.png') }}" alt="Code Icon" class="code-icon">
                    <div>
                        <span class="position-relative code-pos"><strong>{{ __("Code") }}</strong></span>
                    </div>
                </div>
                <div class="programming-language px-4 my-2">
                    <img src="{{ asset('images/show-exercise-page/programming-lang-icon.png') }}" alt="Programming Language Icon" class="language-icon">
                    <span><strong>{{ __("Programming Language") }}: <span class="text-muted">C++</span></strong></span>
                </div>
                <div id="editor" class="editor">{{ $submission->code }}</div>
                <pre id="output"></pre>
                <div class="output-container">
                    <div class="output-header"><i class="fas fa-terminal"></i>{{ __("Compiler Result") }}</div>
                    <div id="console-output" class="output-content">
                        <p><strong class="output-result">{{ __("Average Compilation Time") }}:</strong> {{ $submission->compilation_time }} {{ __("seconds") }}</p>
                        <p><strong class="output-result">{{ __("Average CPU Time") }}:</strong> {{ $submission->cpu_time }} {{ __("seconds") }}</p>
                        <p><strong class="output-result">{{ __("Average Memory Usage") }}:</strong> {{ $submission->memory_time }} KB</p>
                    </div>
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
