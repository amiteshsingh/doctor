<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Doctor;
use App\Models\Hospital;

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
        $query = Doctor::with(['availability','educations','languages','specializations.specialization',
            'locations'
        ]);

        // Filter: Name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter: Specialization
        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function($q) use ($request) {
                // agar specialization name se search karna hai
                $q->whereHas('specialization', function($sp) use ($request) {
                    $sp->where('name', 'like', '%' . $request->specialization . '%');
                });
            });
        }

        // Filter: Address
        if ($request->filled('address')) {
            $query->whereHas('locations', function($q) use ($request) {
                $q->where('address', 'like', '%' . $request->address . '%');
            });
        }

        // Filter: Experience
        if ($request->filled('min_experience')) {
            $query->where('experience', '>=', $request->min_experience);
        }

        $doctors = $query->where('status', 1)
                        ->where('approval_status', 1)
                        ->get();

        return view('page.doctors', ['doctors' => $doctors]);
    }


    public function hospital(Request $request)
    {
        $query = Hospital::with(['specializations.specialization']);

        // Filter: Name + Address
        if ($request->filled('name') || $request->filled('address')) {
            $query->where(function($q) use ($request) {
                if ($request->filled('name')) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                }
                if ($request->filled('address')) {
                    $q->where('address', 'like', '%' . $request->address . '%');
                }
            });
        }

        // Filter: Specialization
        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function($q) use ($request) {
                $q->whereHas('specialization', function($sp) use ($request) {
                    $sp->where('name', 'like', '%' . $request->specialization . '%');
                });
            });
        }

        $hospitals = $query->where('status', 1)
                        ->where('approval_status', 1)
                        ->get();

        return view('page.hospitals', ['hospitals' => $hospitals]);
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

     public function doctor_profile(Request $request, $id = null, $name=null)
    {
        $doctor = Doctor::with(['availability','educations','languages','specializations','locations'
                    ])->where('id', $id)->first();

        
        return view('page.doctor-profile', ['doctor' => $doctor]);
    }

    public function hospital_details(Request $request, $id = null, $name=null)
{
    $hospital = Hospital::with(['specializations.specialization'])
                ->where('id', $id)
                ->first();

    return view('page.hospital-details', ['hospital' => $hospital]);
}

}
