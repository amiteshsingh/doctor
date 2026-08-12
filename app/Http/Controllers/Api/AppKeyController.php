<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppKeyController extends Controller
{
    public function index()
    {
        return response()->json([
            'groq_api_key'  => env('GROQ_API_KEY'),
            'gemini_api_key' => env('GEMINI_API_KEY'),
            'usda_api_key'  => env('USDA_API_KEY'),
        ]);
    }
}
