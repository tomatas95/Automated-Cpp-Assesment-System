<?php

namespace App\Http\Controllers;

use app;
use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function googleLogin(){
        $locale = app()->getLocale();
        session()->put('locale', $locale);
        return Socialite::driver('google')->redirect();
    }

    public function googleStore(){
        try{
            $locale = app()->getLocale();
            session()->put('locale', $locale);
    
            $user = Socialite::driver('google')->user();
            $gmailUser = User::where('google_id', $user->id)->first();
    
            if ($gmailUser) {
                Auth::login($gmailUser);
                return redirect('/index')->with('message', __('Logged in with Gmail successfully!'));
            }
    
            $existingUser = User::where('email', $user->email)->first();
            if ($existingUser) {
                $existingUser->google_id = $user->id;
                $existingUser->save();
    
                Auth::login($existingUser);
                return redirect('/index')->with('message', __('Logged in with Gmail successfully!'));
            }
    
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => bcrypt(Str::random(24))
            ]);
    
            Auth::login($newUser);
            return redirect('/index')->with('message', __('User created and logged in with Gmail successfully!'));
    
        } catch (Exception $e) {
            return redirect('/users/login')->with('error', __('An error occurred during the authentication process.'));
        }
    }

    public function githubLogin(){
        $locale = app()->getLocale();
        session()->put('locale', $locale);
        return Socialite::driver('github')->redirect();
    }

    public function githubStore(){
        try{
            $locale = app()->getLocale();
            session()->put('locale', $locale);
    
            $user = Socialite::driver('github')->user();
            $githubUser = User::where('github_id', $user->id)->first();
    
            if ($githubUser) {
                Auth::login($githubUser);
                return redirect('/index')->with('message', __('Logged in with GitHub successfully!'));
            }
    
            $existingUser = User::where('email', $user->email)->first();
            if ($existingUser) {
                $existingUser->github_id = $user->id;
                $existingUser->save();
    
                Auth::login($existingUser);
                return redirect('/index')->with('message', __('Logged in with GitHub successfully!'));
            }
    
            $newUser = User::create([
                'name' => $user->name ?? $user->nickname,
                'email' => $user->email,
                'github_id' => $user->id,
                'password' => bcrypt(Str::random(24))
            ]);
    
            Auth::login($newUser);
            return redirect('/index')->with('message', __('User created and logged in with GitHub successfully!'));
    
        } catch (Exception $e) {
            return redirect('/users/login')->with('error', __('An error occurred during the authentication process.'));
        }
    }
}
