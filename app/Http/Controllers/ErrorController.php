<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ErrorController extends Controller
{
    public function notFoundErr(){
        $locale = Session::get('lang', 'en');
        App::setLocale($locale);

        return response()->view('errors.404', [], 404);
    }
}
