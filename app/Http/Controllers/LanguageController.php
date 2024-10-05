<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function setLanguage($language, Request $request){
        $request->session()->put('lang', $language);
        return redirect()->back();
    }
}
