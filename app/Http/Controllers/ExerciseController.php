<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ShortCode;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Session;

class ExerciseController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }
    
    public function welcome() {
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();

        return view('welcome', [
            'shortCodes' => $shortCodes
        ]);
    }

    public function index(){
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();
        $user = auth()->user();

        $userSubmittions = $user->submissions->pluck('exercise_id')->toArray();

        $exercises = Exercise::latest()->filter(request(['search']))->paginate(10);

        return view('index', [
            'exercises' => $exercises,
            'user' => $user,
            'userSubmittions' => $userSubmittions,
            'shortCodes' => $shortCodes
        ]);
    }

    public function access(Request $request, Exercise $exercise)
    {
        $passwordField = 'exercise_password_' . $exercise->id;
    
        if (empty($request->input($passwordField))) {
            return redirect()->back()->withErrors([
                $passwordField => __("Exercise Password is required.")
            ])->with('error', $exercise->title . __(" exercise requires a password to enter."));
        }
        
        $passwordFields = $request->validate([
            $passwordField => 'required'
        ], [
            $passwordField . '.required' => __("Exercise Password is required.")
        ]);

        if($exercise->exercise_password != null) {
            $decryptExPassword = decrypt($exercise->exercise_password);
        }

        if ($passwordFields[$passwordField] !== $decryptExPassword) {
            return redirect()->back()->withErrors([
                $passwordField => __("Incorrect Password")
                ])->with('error', __("The password you entered for ") . $exercise->title . __(" exercise is incorrect."));
            }
    
        $request->session()->put('exercise_password_' . $exercise->id, $passwordFields[$passwordField]);
            return redirect()->route('exercises.show', ['exercise' => $exercise->id])->with('message', __("Password verification was successful!"));
    }
    

    public function show(Request $request, Exercise $exercise){
        
        if($exercise->exercise_password != null) {
            $decryptExPassword = decrypt($exercise->exercise_password);
        }
        if ($exercise->exercise_visibility === 'private') {
            $storedPassword = $request->session()->get('exercise_password_' . $exercise->id);
    
            if (!$storedPassword || $storedPassword !== $decryptExPassword) {
                return redirect('/index')->with('error', __('This exercise is private. Please enter the password to access.'));
            }
        }

        $checkExistingSubmission = Submission::where('exercise_id', $exercise->id)->where('user_id', auth()->id())->first();

        if($checkExistingSubmission){
            return redirect('/index')->with('error', __('You have already submitted a solution for this exercise.'));
        }

        return view('exercises.show-exercise', [
            'exercise' => $exercise,
        ]);
    }

    public function create()
    {
        return view('exercises.create-exercise');
    }
    
    public function store(Request $request)
    {
        // dd($request);

        $exerciseFields = $request->validate([
            'title' => ['required', 'max:80', 'min:3', Rule::unique('exercises', 'title')],
            'content' => ['required'],
            'difficulty' => ['required', 'in:easy,normal,hard'],
            'allow_automatic_check_view' => ['required', 'boolean'],
            'allow_automatic_check_run' => ['required', 'boolean'],
            'time_number' => ['required', 'integer', 'min:1', 'max:100'],
            'time_unit' => ['required', 'in:minutes,hours,days'],
            'check1.0' => ['required', 'max:255'],
            'check1.*' => ['required', 'max:255'],
            'check1_answer' => ['required', 'max:255'],
            'check2.0' => ['required', 'max:255'],
            'check2.*' => ['required', 'max:255'],
            'check2_answer' => ['required', 'max:255'],
            'check3.*' => ['required', 'max:255'],
            'check3.0' => ['required', 'max:255'],
            'check3_answer' => ['required', 'max:255'],
        ], [
            'title.required' => __('The title field is required.'),
            'title.max' => __('The title may not be greater than 80 characters.'),
            'title.min' => __('The title may not be lower than 3 characters.'),
            'title.unique' => __('The title field must be unique.'),
            'content.required' => __('The content field is required.'),
            'difficulty.required' => __('The difficulty field is required.'),
            'difficulty.in' => __('The selected difficulty is invalid. It must be one of the following: Easy, Normal, Hard.'),
            'allow_automatic_check_view.required' => __('You must specify if automatic checks should be viewable by the public.'), 
            'allow_automatic_check_run.required' => __('You must specify if automatic result should be printed for everyone each time the program is ran.'), 
            'time_number.required' => __('The time required field is required.'),
            'time_number.integer' => __('The time required must be an integer.'),
            'time_number.min' => __('The time required must be at least 1.'),
            'time_number.max' => __('The time required may not be greater than 100.'),
            'time_unit.required' => __('The time unit field is required.'),
            'time_unit.in' => __('The selected time unit is invalid. It must be one of the following: Minutes, Hours, Days.'),
            'check1.0.required' => __('Check 1 is required.'),
            'check1.0.max' => __('Check 1 may not be greater than 255 characters.'),
            'check1.*.required' => __('#1 Check additional input cannot be empty.'),
            'check1.*.max' => __('#1 Check additional input cannot be greater than 255 characters.'),
            'check1_answer.required' => __('Check 1 answer is required.'),
            'check1_answer.max' => __('Check 1 answer may not be greater than 255 characters.'),
            'check2.0.required' => __('Check 2 is required.'),
            'check2.0.max' => __('Check 2 may not be greater than 255 characters.'),
            'check2.*.required' => __('#2 Check additional input cannot be empty.'),
            'check2.*.max' => __('#2 Check additional input cannot be greater than 255 characters.'),
            'check2_answer.required' => __('Check 2 answer is required.'),
            'check2_answer.max' => __('Check 2 answer may not be greater than 255 characters.'),
            'check3.0.required' => __('Check 1 is required.'),
            'check3.0.max' => __('Check 1 may not be greater than 255 characters.'),
            'check3.*.required' => __('#3 Check additional input cannot be empty.'),
            'check3.*.max' => __('#3 Check additional input cannot be greater than 255 characters.'),
            'check3_answer.required' => __('Check 3 answer is required.'),
            'check3_answer.max' => __('Check 3 answer may not be greater than 255 characters.'),
        ]);
    
        if ($request->has('include_hints')) {
            $hintFields = $request->validate([
                'hint1' => ['required', 'max:255'],
                'hint2' => ['required', 'max:255'],
                'hint3' => ['required', 'max:255'],
            ], [
                'hint1.required' => __('Hint 1 is required.'),
                'hint1.max' => __('Hint 1 may not be greater than 255 characters.'),
                'hint2.required' => __('Hint 2 is required.'),
                'hint2.max' => __('Hint 2 may not be greater than 255 characters.'),
                'hint3.required' => __('Hint 3 is required.'),
                'hint3.max' => __('Hint 3 may not be greater than 255 characters.'),
            ]);
            $exerciseFields = array_merge($exerciseFields, $hintFields);
        } else {
            $exerciseFields['hint1'] = null;
            $exerciseFields['hint2'] = null;
            $exerciseFields['hint3'] = null;
        }

        if($request->has('exercise_visibility')) {
            $exerciseFields['exercise_visibility'] = 'private';

            $passwordFields = $request->validate([
                'exercise_password' => ['required', 'max:255']
            ], [
                'exercise_password.required' => __("Exercise Password is required."),
                'exercise_password.max' => __("Exercise Password may not be greater than 255 characters.")
            ]);

            $passwordFields['exercise_password'] = encrypt($passwordFields['exercise_password']);
            $exerciseFields = array_merge($exerciseFields, $passwordFields);
        }
        
        $exerciseFields['code_solution'] = $request->input('code_solution');
        $exerciseFields['content'] = Purifier::clean($exerciseFields['content']);
        $exerciseFields['user_id'] = auth()->id();

        $exerciseFields['check1'] = json_encode($request->input('check1'));
        $exerciseFields['check2'] = json_encode($request->input('check2'));
        $exerciseFields['check3'] = json_encode($request->input('check3'));

        $compilationError = json_decode($request->input('compilation_error'), true);
        if($compilationError) {
            return redirect()->back()
            ->withInput()
            ->with('compilationError', $compilationError['actualOutput'])->with('error', __('Your code has compilation errors.'));
        }

        $checkResults = json_decode($request->input('check_results'), true);
        
        $check1Result = null;
        $check2Result = null;
        $check3Result = null;
        $allPassed = true;
        
        foreach ($checkResults as $checkResult) {
            if ($checkResult['checkIndex'] == 1) {
                $check1Result = $checkResult;
            }
            if ($checkResult['checkIndex'] == 2) {
                $check2Result = $checkResult;
            }
            if ($checkResult['checkIndex'] == 3) {
                $check3Result = $checkResult;
            }
            
            if (!$checkResult['passed']) {
                $allPassed = false;
            }
        }
        
        // dd($check1Result, $check2Result, $check3Result);
        
        if (!$allPassed) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'check1Result' => $check1Result,
                    'check2Result' => $check2Result,
                    'check3Result' => $check3Result
                ])
                ->with('error', __('Your code does not produce the same results as your automatization answers.'));
        }
    
        Exercise::create($exerciseFields);

        return redirect('/index')->with('message', __('Exercise created successfully!'));
    }

    public function edit(Exercise $exercise){
        $decryptExPassword = null;
        
        if($exercise->user_id != auth()->id() && auth()->user()->role != 'admin'){
            abort(403, 'Unauthorized Action');
        }
        if($exercise->exercise_password != null) {
            $decryptExPassword = decrypt($exercise->exercise_password);
        }

        return view('exercises.edit-exercise', [
            'exercise' => $exercise,
            'decryptedExPassword' => $decryptExPassword
        ]);
    }

    public function update(Request $request, Exercise $exercise)
    {
        if($exercise->user_id != auth()->id() && auth()->user()->role != 'admin'){
            abort(403, 'Unauthorized Action');
        }
    
        $exerciseFields = $request->validate([
            'title' => ['required', 'max:80', 'min:3'],
            'content' => ['required'],
            'hint1' => ['nullable', 'max:255'],
            'hint2' => ['nullable', 'max:255'],
            'hint3' => ['nullable', 'max:255'],
            'difficulty' => ['required'],
            'allow_automatic_check_view' => ['required', 'boolean'],
            'allow_automatic_check_run' => ['required', 'boolean'],
            'time_number' => ['required', 'integer', 'min:1', 'max:100'],
            'time_unit' => ['required', 'in:minutes,hours,days'],
            'check1.*' => ['required', 'max:255'],
            'check1_answer' => ['required', 'max:255'],
            'check2.*' => ['required', 'max:255'],
            'check2_answer' => ['required', 'max:255'],
            'check3.*' => ['required', 'max:255'],
            'check3_answer' => ['required', 'max:255'],
        ], [
            'title.required' => __('The title field is required.'),
            'title.max' => __('The title may not be greater than 80 characters.'),
            'title.min' => __('The title may not be lower than 3 characters.'),
            'content.required' => __('The content field is required.'),
            'difficulty.required' => __('The difficulty field is required.'),
            'difficulty.in' => __('The selected difficulty is invalid. It must be one of the following: Easy, Normal, Hard.'),
            'allow_automatic_check_view.required' => __('You must specify if automatic checks should be viewable by the public.'), 
            'allow_automatic_check_run.required' => __('You must specify if automatic result should be printed for everyone each time the program is ran.'), 
            'time_number.required' => __('The time required field is required.'),
            'time_number.integer' => __('The time required must be an integer.'),
            'time_number.min' => __('The time required must be at least 1.'),
            'time_number.max' => __('The time required may not be greater than 100.'),
            'time_unit.required' => __('The time unit field is required.'),
            'time_unit.in' => __('The selected time unit is invalid. It must be one of the following: Minutes, Hours, Days.'),
            'check1.0.required' => __('Check 1 is required.'),
            'check1.0.max' => __('Check 1 may not be greater than 255 characters.'),
            'check1.*.required' => __('#1 Check additional input cannot be empty.'),
            'check1.*.max' => __('#1 Check additional input cannot be greater than 255 characters.'),
            'check1_answer.required' => __('Check 1 answer is required.'),
            'check1_answer.max' => __('Check 1 answer may not be greater than 255 characters.'),
            'check2.0.required' => __('Check 2 is required.'),
            'check2.0.max' => __('Check 2 may not be greater than 255 characters.'),
            'check2.*.required' => __('#2 Check additional input cannot be empty.'),
            'check2.*.max' => __('#2 Check additional input cannot be greater than 255 characters.'),
            'check2_answer.required' => __('Check 2 answer is required.'),
            'check2_answer.max' => __('Check 2 answer may not be greater than 255 characters.'),
            'check3.0.required' => __('Check 1 is required.'),
            'check3.0.max' => __('Check 1 may not be greater than 255 characters.'),
            'check3.*.required' => __('#3 Check additional input cannot be empty.'),
            'check3.*.max' => __('#3 Check additional input cannot be greater than 255 characters.'),
            'check3_answer.required' => __('Check 3 answer is required.'),
            'check3_answer.max' => __('Check 3 answer may not be greater than 255 characters.'),
        ]);

        if ($request->has('include_hints')) {
            $hintFields = $request->validate([
                'hint1' => ['nullable', 'max:255'],
                'hint2' => ['nullable', 'max:255'],
                'hint3' => ['nullable', 'max:255'],
            ], [
                'hint1.max' => __('Hint 1 may not be greater than 255 characters.'),
                'hint2.max' => __('Hint 2 may not be greater than 255 characters.'),
                'hint3.max' => __('Hint 3 may not be greater than 255 characters.'),
            ]);
            $exerciseFields = array_merge($exerciseFields, $hintFields);
        } else {
            $exerciseFields['hint1'] = null;
            $exerciseFields['hint2'] = null;
            $exerciseFields['hint3'] = null;
        }

        if($request->has('exercise_visibility')) {
            $exerciseFields['exercise_visibility'] = 'private';

            $passwordFields = $request->validate([
                'exercise_password' => ['required', 'max:255']
            ], [
                'exercise_password.required' => __("Exercise Password is required."),
                'exercise_password.max' => __("Exercise Password may not be greater than 255 characters.")
            ]);

            $passwordFields['exercise_password'] = encrypt($passwordFields['exercise_password']);
            $exerciseFields = array_merge($exerciseFields, $passwordFields);
        } else {
            $exerciseFields['exercise_visibility'] = 'public';
            $exerciseFields['exercise_password'] = null;
        }
    
        $exerciseFields['code_solution'] = $request->input('code_solution');
        $exerciseFields['content'] = Purifier::clean($exerciseFields['content']);
    
        $exerciseFields['check1'] = json_encode($request->input('check1'));
        $exerciseFields['check2'] = json_encode($request->input('check2'));
        $exerciseFields['check3'] = json_encode($request->input('check3'));

        $compilationError = json_decode($request->input('compilation_error'), true);
        if($compilationError) {
            return redirect()->back()
            ->withInput()
            ->with('compilationError', $compilationError['actualOutput'])->with('error', __('Your code has compilation errors.'));
        }

        $checkResults = json_decode($request->input('check_results'), true);
        
        $check1Result = null;
        $check2Result = null;
        $check3Result = null;
        $allPassed = true;
        
        foreach ($checkResults as $checkResult) {
            if ($checkResult['checkIndex'] == 1) {
                $check1Result = $checkResult;
            }
            if ($checkResult['checkIndex'] == 2) {
                $check2Result = $checkResult;
            }
            if ($checkResult['checkIndex'] == 3) {
                $check3Result = $checkResult;
            }
            
            if (!$checkResult['passed']) {
                $allPassed = false;
            }
        }
        
        
        if (!$allPassed) {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'check1Result' => $check1Result,
                    'check2Result' => $check2Result,
                    'check3Result' => $check3Result
                ])
                ->with('error', __('Your code does not produce the same results as your automatization answers.'));
        }
        
        $exercise->update($exerciseFields);
    
        return redirect('/index')->with('message', __('Exercise updated successfully!'));
    }

    public function submit(Request $request, Exercise $exercise){

        // dd($request);

        $submissionFields = $request->validate([
            'code' => ['required'],
            'test_result_1' => ['nullable'],
            'test_result_2' => ['nullable'],
            'test_result_3' => ['nullable'],
            'cpu_time' => ['nullable'],
            'compilation_time' => ['nullable'],
            'memory_time' => ['nullable'],
            'auto_check_correct_cases' => ['nullable'],
        ]);
    
        $submissionFields['test_result_1'] = $submissionFields['test_result_1'] ?? '0';
        $submissionFields['test_result_2'] = $submissionFields['test_result_2'] ?? '0';
        $submissionFields['test_result_3'] = $submissionFields['test_result_3'] ?? '0';

        $submissionFields['user_id'] = auth()->id();
        $submissionFields['exercise_id'] = $exercise->id;

        $exercise->increment('submission_count', 1);
    
        Submission::create($submissionFields);
        return redirect('/index')->with('message', __('Solution has been submitted successfully!'));
    }    

    public function destroy(Exercise $exercise){
        if($exercise->user_id != auth()->id() && auth()->user()->role != 'admin'){
            abort(403, 'Unauthorized Action');
        }
        
        $exercise->delete();
        return redirect('/index')->with('message', __('Exercise deleted successfully!'));
    }    

    public function manage()
    {
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();

        $exercises = auth()->user()->exercises()->latest()->filter(request(['search']))->paginate(10);
        $user = auth()->user();

        return view('exercises.manage-exercises', ['exercises' => $exercises, 'user' => $user, 'shortCodes' => $shortCodes]);
    }
}
