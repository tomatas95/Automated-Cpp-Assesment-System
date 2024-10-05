<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exercise;
use App\Models\ShortCode;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Lang;

class AdminController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function index(){
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();
        $users = User::latest()->filter(request(['search']))->paginate(10);

        $userData = auth()->user();
        
        if(auth()->user()->role != 'admin'){
            return redirect('/')->with('error',  __('No Permission to do this action.'));
        }

        return view('admin.admin-view-user-list', [
            'userData' => $userData,
            'users' => $users,
            'shortCodes' => $shortCodes
        ]);
    }

    public function view($id){

        $user = User::find($id);
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();
        $genders = Lang::get('genders');
        $countries = Lang::get('countries');

        if(auth()->user()->role != 'admin'){
            return redirect('/')->with('error',  __('No Permission to do this action.'));
        }
        $user->skills = $user->skills ?? __('Not specified');
        $user->description = $user->description ?? __('Not specified');

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
        // dd($createdExercises->toArray());

        return view('admin.admin-view-profile',[
            'user' => $user,
            'genders' => $genders,
            'countries' => $countries,
            'shortCodes' => $shortCodes,
            'createdExercises' => $createdExercises,
            'submittedExercises' => $submittedExercises,
        ]);

    }

    public function edit($id)
    {   
        $user = User::find($id);
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();

        if(auth()->user()->role != 'admin'){
            return redirect('/')->with('error',  __('No Permission to do this action.'));
        }

        $dateOfBirth = null;
        if($user->date_of_birth){
            $dateOfBirth = optional($user->date_of_birth)->format('Y-m-d') ?? null;
        }

        $birth_year = $dateOfBirth ? $dateOfBirth->year : null;
        $birth_month = $dateOfBirth ? $dateOfBirth->month : null;
        $birth_day = $dateOfBirth ? $dateOfBirth->day : null;

        return view('admin.admin-edit-profile', [
            'user' => $user,
            'birth_year' => $birth_year,
            'birth_month' => $birth_month,
            'birth_day' => $birth_day,
            'shortCodes' => $shortCodes
        ]);
    }

public function update(Request $request, $id)
{
    $user = User::find($id);

    if(auth()->user()->role != 'admin'){
        return redirect('/')->with('message', $user->role);
    }

    $userFields = $request->validate([
        'name' => ['required', 'min:3', 'max:50', 'regex:/[A-Z]/'],
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

    return redirect('/admin/user-list')->with('message', __('Profile updated successfully!'));
}

    public function destroy($id){
        $user = User::find($id);
        // dd($user);

        if(auth()->user()->role != 'admin'){
            return redirect('/')->with('error',  __('No Permission to do this action.'));
        }

        $submissions = Submission::where('user_id', $user->id)->get();

        foreach($submissions as $submission){
            $exercise = Exercise::find($submission->exercise_id);
            if ($exercise) {
                $exercise->decrement('submission_count');
            }
        }
        $userName = $user->name;
        $user->delete();

        return redirect('/admin/user-list')->with('message', __('User :name deleted successfully!', ['name' => $userName]));
    }
    
    public function manage($id){
        $user = User::find($id);
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();

        if(auth()->user()->role != 'admin'){
            return redirect('/')->with('error',  __('No Permission to do this action.'));
        }

        $exercises = Exercise::where('user_id', $user->id)->latest()->filter(request(['search']))->paginate(10);
        
        return view('admin.admin-manage-ex', ['exercises' => $exercises, 'user' => $user, 'shortCodes' => $shortCodes]);
    }
}
