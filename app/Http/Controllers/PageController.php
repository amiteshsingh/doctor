<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Doctor;

class PageController extends Controller
{
    public function index(){


        return View::make('page.index');
    }

    public function about()
    {
        //echo "amiteshsingh";
        return view('page.about');
    }

    public function doctor(Request $request)
    {
        $doctors = Doctor::allDoctor();

        // 🔍 Search Filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $doctors = $doctors->filter(function ($doctor) use ($search) {
                return str_contains(strtolower($doctor['name']), $search) ||
                       str_contains(strtolower($doctor['specialization']), $search) ||
                       str_contains(strtolower($doctor['address']), $search);
            });
        }

        // 🎓 Min Experience Filter
        if ($request->filled('min_experience')) {
            $minExp = (int) $request->min_experience;
            $doctors = $doctors->filter(function ($doctor) use ($minExp) {
                return $doctor['experience'] >= $minExp;
            });
        }

        return view('page.doctors', ['doctors' => $doctors]);
    }

    public function hospital()
    {
        return view('page.hospitals');
    }

    public function blog()
    {
        return view('page.blog');
    }

    public function detail()
    {
        return view('page.detail');
    }
    
    public function team()
    {
        return view('page.team');
    }

    public function testimonial()
    {
        return view('page.testimonial');
    }

    public function appointment()
    {
        return view('page.appointment');
    }

    public function search()
    {
        return view('page.search');
    }

    public function contact()
    {
        return view('page.contact');
    }
}
