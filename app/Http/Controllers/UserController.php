<?php

namespace App\Http\Controllers;

use PDO;
use App\Models\User;
use App\Models\Exercise;
use App\Models\ShortCode;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Mail\Mailer;

class UserController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function index(){
        $user = auth()->user();
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();

        $genders = Lang::get('genders');
        $countries = Lang::get('countries');

        $user->skills = $user->skills ?? __('Not specified');
        $user->description = $user->description ?? __('Not specified');

    // SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as date, COUNT(*) as count FROM submissions
    // GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d') ORDER BY date ASC;
        $createdExercises = Exercise::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date, COUNT(*) as count')
        ->where('user_id', $user->id)
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
    
    $submittedExercises = Submission::selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d") as date, COUNT(*) as count')
        ->where('user_id', $user->id)
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();
    
    // dd($submittedExercises->toArray());
            
        return view('users.view-profile',[    
            'user' => $user,
            'genders' => $genders,
            'countries' => $countries,
            'shortCodes' => $shortCodes,    
            'createdExercises' => $createdExercises,
            'submittedExercises' => $submittedExercises,
        ]);
    }

    public function create(){
        return view('users.register');
    }    

    public function store(Request $request){
        $userFields = $request->validate([
           'name' => ['required', 'min:3', 'max:255', 'regex:/[A-Z]/', Rule::unique('users', 'name')],
           'email' => ['required', 'min:5', 'email', 'max:255', Rule::unique('users', 'email')],
           'password' => ['required', 'confirmed', 'min:5', 'max:255', 'regex:/[0-9]/'],
           'password_confirmation' => ['required']
        ], [
            'name.required' => __('The name field is required.'),
            'name.min' => __('The name must be at least 3 characters.'),
            'name.max' => __('The name may not be greater than 50 characters.'),
            'name.unique' => __('Your name already exists in our system.'),
            'name.regex' => __('The name must contain at least one uppercase letter.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('The email must be a valid email address.'),
            'email.min' => __('The email must be at least 5 characters.'),
            'email.max' => __('The email may not be greater than 255 characters.'),
            'email.unique' => __('The email has already been taken.'),
            'password.required' => __('The password field is required.'),
            'password.confirmed' => __('The password confirmation does not match.'),
            'password.min' => __('The password must be at least 5 characters.'),
            'password.max' => __('The password may not be greater than 255 characters.'),
            'password.regex' => __('The password must contain at least one number.'),
            'password_confirmation.required' => __("The password confirmation field is required.")
        ]);
        
        $userFields['password'] = bcrypt($userFields['password']);

        $user = User::create($userFields);
        auth()->login($user);
        return redirect('/index')->with('message', __('User created and logged in successfully!'));
    }

    public function edit(User $user)
    {
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();

        $dateOfBirth = null;
        if($user->date_of_birth){
            $dateOfBirth = optional($user->date_of_birth)->format('Y-m-d') ?? null;
        }

        $birth_year = $dateOfBirth ? $dateOfBirth->year : null;
        $birth_month = $dateOfBirth ? $dateOfBirth->month : null;
        $birth_day = $dateOfBirth ? $dateOfBirth->day : null;

        $user = auth()->user(); 
        return view('users.edit-profile', [
            'user' => $user,
            'birth_year' => $birth_year,
            'birth_month' => $birth_month,
            'birth_day' => $birth_day,
            'shortCodes' => $shortCodes
        ]);
    }

    public function update(Request $request, User $user){
        // dd($request);

        $userFields = $request->validate([
            'name' => ['required', 'min:3', 'max:50', 'regex:/[A-Z]/', Rule::unique('users', 'name')->ignore($user->id)],
            'last_name' => ['nullable', 'max:255'],
            'email' => ['required', 'email', 'min:5', 'max:255', Rule::unique('users')->ignore($user->id)],
            'location' => ['nullable', 'max:255'],
            'gender' => ['nullable'],
            'profile_picture' => ['nullable', 'image', 'max:5120'],
            'skills' => ['nullable', 'max:255'],
            'github' => ['nullable', 'max:255'],
            'description' => ['nullable']
        ], [
            'name.required' => __('The name field is required.'),
            'name.min' => __('The name must be at least 3 characters.'),
            'name.max' => __('The name may not be greater than 50 characters.'),
            'name.unique' => __('Your name already exists in our system.'),
            'name.regex' => __('The name must contain at least one uppercase letter.'),
            'last_name.max' => __('The last name may not be greater than 255 characters.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('The email must be a valid email address.'),
            'email.min' => __('The email must be at least 5 characters.'),
            'email.max' => __('The email may not be greater than 255 characters.'),
            'location.max' => __('The location may not be greater than 255 characters.'),
            'profile_picture.image' => __('The profile picture must be an image.'),
            'profile_picture.max' => __('The profile picture may not be greater than 5120 kilobytes (5 MB).'),
            'skills.max' => __('The skills may not be greater than 255 characters.'),
            'github.max' => __('The GitHub link may not be greater than 255 characters.')
        ]);
        
        if($request->hasFile('profile_picture')){
            $userFields['profile_picture'] = $request->file('profile_picture')->store('pfps', 'public');
        }

        if (!empty($userFields['github'])) {
            $userFields['github'] = 'https://www.github.com/' . ltrim($userFields['github'], '/');
        }

        if ($request->year && $request->month && $request->day) {
            $userFields['date_of_birth'] = $request->year . '-' . $request->month . '-' . $request->day;
        } else {
            $userFields['date_of_birth'] = null;
        }

        $user->update($userFields);

        return redirect('/users/my-profile')->with('message', __('Profile updated successfully!'));

    }
}