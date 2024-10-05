<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RecoverPasswordController extends Controller
{
    
    public function index(){
        return view("auth.forgot-password");
    }

    public function store(Request $request){
        $userFields = $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ], [
            'email.required' => __('The email field is required.'),
            'email.email' => __('The email must be a valid email address.'),
            'email.exists' => __('This email does not exist in our system.')
        ]);

        $existingToken = DB::table('password_reset_tokens')->where('email', $userFields['email'])->first();

        if ($existingToken) {
            $tokenCreatedDate = Carbon::parse($existingToken->created_at);
            $tokenExpirationDate = $tokenCreatedDate->addMinutes(60); 
    
            if (Carbon::now()->lessThan($tokenExpirationDate)) {
                return back()->with([
                    'email_notif' => __("A recovery email was already sent to your email address, please check your inbox."),
                    'message_type' => 'warning'
                ]);
            }
    
            DB::table('password_reset_tokens')->where('email', $userFields['email'])->delete();
        }
    
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $userFields['email'],
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('emails.forget-password', ['token' => $token], function ($message) use ($userFields) {
            $message->to($userFields['email']);
            $message->subject('Reset Password');
        });

        return back()->with([
            'email_notif' => __("An email has been sent to your inbox to reset your password."),
            'message_type' => 'success'
        ]);
    }

    public function edit($token){
        $tokenData = DB::table("password_reset_tokens")->where('token', $token)->first();

        if(!$tokenData){
            return redirect('/auth/forgot-password')->with([
            'email_notif' => __("Invalid token."),
            'message_type' => 'info']);
        }

        $tokenCreatedDate = Carbon::parse($tokenData->created_at);
        $tokenExpirationDate = $tokenCreatedDate->addMinutes(60);

        if(Carbon::now()->greaterThan($tokenExpirationDate)){
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            return redirect('/auth/forgot-password')->with([
            'email_notif' => __("This password reset link has expired."),
            'message_type' => 'info']);
        }

        return view("auth.reset-password", [
           'token' => $token 
        ]);
    }

    public function update(Request $request){

        $userFields = $request->validate([
            'token' => ['required'],
            'password' => ['required', 'confirmed', 'min:5', 'max:255', 'regex:/[0-9]/'],
            'password_confirmation' => ['required']
        ],[
            'password.required' => __('The password field is required.'),
            'password.confirmed' => __('The password confirmation does not match.'),
            'password.min' => __('The password must be at least 5 characters.'),
            'password.max' => __('The password may not be greater than 255 characters.'),
            'password.regex' => __('The password must contain at least one number.'),
            'password_confirmation.required' => __("The password confirmation field is required.")
        ]);

        $tokenData = DB::table("password_reset_tokens")->where('token', $userFields['token'])->first();
        if(!$tokenData){
            return redirect('/auth/forgot-password')->with(['email_notif' => __("Invalid token.")]);
        }

        $tokenCreatedDate = Carbon::parse($tokenData->created_at);
        $tokenExpirationDate = $tokenCreatedDate->addMinutes(60);

        if(Carbon::now()->greaterThan($tokenExpirationDate)){
            DB::table("password_reset_tokens")->where('token', $userFields['token'])->delete();
            return redirect('/auth/forgot-password')->with(['email_notif' => __("This password reset link has expired."), 'message_type' => 'info']);
        }

        $user = User::where('email', $tokenData->email)->first();
        $user->password = bcrypt($userFields['password']);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $tokenData->email)->delete();
        return redirect('/users/login')->with(['message' => __("Your password has been successfully reset. Please login with your new password.")]);
    }
}
