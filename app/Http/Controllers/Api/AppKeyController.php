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
            'groq_api_key_report_analysis'  => env('GROQ_API_KEY_REPORT'),
            'groq_api_key_medicine_scan'  => env('GROQ_API_KEY_MEDICIN'),
            'gemini_api_key' => env('GEMINI_API_KEY'),
            'usda_api_key'  => env('USDA_API_KEY'),
        ]);
    }
}
