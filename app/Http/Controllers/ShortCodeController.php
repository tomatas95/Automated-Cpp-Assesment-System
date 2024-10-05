<?php

namespace App\Http\Controllers;

use App\Models\ShortCode;
use Illuminate\Http\Request;

class ShortCodeController extends Controller
{
    public function index() {
        $shortCodes = ShortCode::all();

        return response()->json([
            'shortCodes' => $shortCodes,
        ]);
    }
}
