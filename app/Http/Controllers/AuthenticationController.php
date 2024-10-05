<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
        public function index(){
            return view("/users/login");
        }

    public function store(Request $request){
        $credentials = $request->only('name', 'password');
        
        if(auth()->attempt($credentials)){
            $request->session()->regenerate();
    
            return redirect('/index')->with('message', __("User logged in successfully!"));
        }
        return back()->withErrors(['credentials' => __('Invalid Credentials')]);
    }

    public function logout(Request $request){
        $locale = session('locale', 'en');
        auth()->logout();

        $request->session()->regenerateToken();

        return $this->loggedOut($request, $locale) ?: redirect("/")->with('message', __("Logged out successfully!"));
    }

    public function loggedOut(Request $request, $locale) {
        session()->put('locale', $locale);
    }
}
