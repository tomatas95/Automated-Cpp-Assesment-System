<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ShortCode;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Services\TranslationService;

class SubmissionController extends Controller
{
    protected $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }
    
    public function index(Exercise $exercise)
    {
        $user = auth()->user();
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();

        $submissions = Submission::where('user_id', $user->id)->with('exercise')->filter(request(['search']))->latest()->paginate(10);

        return view('submissions.submitted-exercises', 
        [
        'submissions' => $submissions,
        'exercises' => $exercise,
        'user' => $user,
        'shortCodes' => $shortCodes
    ]);
    }

    public function view(Exercise $exercise, Submission $submission){
        if ($submission->user_id != auth()->id() && $exercise->user_id != auth()->id() && auth()->user()->role != 'admin') {
            abort(403, __('Unauthorized Action'));
        }
        
        return view('submissions.submitted-view', [
            'exercise' => $exercise,
            'submission' => $submission
        ]);
    }

    public function manage(Exercise $exercise){
        if($exercise->user_id != auth()->id() && auth()->user()->role != 'admin'){
            abort(403, 'Unauthorized Action');
        }
        $shortCodes = ShortCode::all()->pluck('replace', 'shortcode')->toArray();
        
        $user = auth()->user();

        $submissions = Submission::where('exercise_id', $exercise->id)->with('exercise', 'user')->filterSubmissionView(request(['search']))->latest()->paginate(10);
        
        $searchURL = '/manage/' . $exercise->id . '/submissions/view';

        return view('submissions.submitted-participants-view', 
        ['submissions' => $submissions, 
        'exercise' => $exercise,
        'searchURL' => $searchURL,
        'user' => $user,
        'shortCodes' => $shortCodes
        ]);
    }
}
