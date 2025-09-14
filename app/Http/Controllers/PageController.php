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

        $doctors = Doctor::with([
            'availability',
            'educations',
            'languages',
            'specializations.specialization',
            'locations'
        ])
        ->where('is_professional', 1)
        ->where('status', 1)
        ->where('approval_status', 1)
        ->limit(20)
        ->get();
    
        return View::make('page.index', compact('doctors'));
    }

    public function about()
    {
        return view('page.about');
    }

    // public function doctor123(Request $request)
    // {
    //     $query = Doctor::with(['availability','educations','languages','specializations.specialization',
    //         'locations'
    //     ]);

    //     // Filter: Name
    //     if ($request->filled('name')) {
    //         $query->where('name', 'like', '%' . $request->name . '%');
    //     }

    //     // Filter: Specialization
    //     if ($request->filled('specialization')) {
    //         $query->whereHas('specializations', function($q) use ($request) {
    //             // agar specialization name se search karna hai
    //             $q->whereHas('specialization', function($sp) use ($request) {
    //                 $sp->where('name', 'like', '%' . $request->specialization . '%');
    //             });
    //         });
    //     }

    //     // Filter: Address
    //     if ($request->filled('address')) {
    //         $query->whereHas('locations', function($q) use ($request) {
    //             $q->where('address', 'like', '%' . $request->address . '%');
    //         });
    //     }

    //     // Filter: Experience
    //     if ($request->filled('min_experience')) {
    //         $query->where('experience', '>=', $request->min_experience);
    //     }

    //     $doctors = $query->where('status', 1)
    //                  ->where('approval_status', 1)
    //                  ->paginate(2);
                     
    //     if ($request->ajax()) {
    //         return view('page.ajax.doctor_list', compact('doctors'))->render(); 
    //     }                     

    //     return view('page.doctors', ['doctors' => $doctors]);
    // }


    public function doctor(Request $request)
    {
        // -------------------
        // Step 1: Get user state from IP
        // -------------------
        $ip = $request->ip();
        // if ($ip == "127.0.0.1") { 
        //     $ip = "103.44.119.10"; // localhost test ke liye ek dummy public IP
        // }

        $response = @file_get_contents("http://ip-api.com/json/{$ip}");
        $data = $response ? json_decode($response, true) : null;
        $userState = $data['regionName'] ?? null;

        // -------------------
        // Step 2: Build Query
        // -------------------
        $query = Doctor::with([
            'availability',
            'educations',
            'languages',
            'specializations.specialization',
            'locations'
        ]);

        // Filter: Name
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter: Specialization
        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function($q) use ($request) {
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

        if ($request->filled('zip_code')) {
            $query->whereHas('locations', function($q) use ($request) {
                $q->where('zip_code', 'like', '%' . $request->zip_code . '%');
            });
        }

       if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($userState) {
            $query->select('doctors.*')
                ->leftJoin('doctor_locations', 'doctors.id', '=', 'doctor_locations.doctor_id')
                ->where('status', 1)
                ->where('approval_status', 1)
                ->orderByRaw("CASE WHEN doctor_locations.state = ? THEN 0 ELSE 1 END", [$userState]);
        } else {
            $query->where('status', 1)->where('approval_status', 1);
        }


        $doctors = $query->paginate(5);


        if ($request->ajax()) {
            return view('page.ajax.doctor_list', compact('doctors'))->render(); 
        }

        return view('page.doctors', [
            'doctors' => $doctors,
            'userState' => $userState
        ]);
    }

    public function hospital(Request $request)
    {
        $query = Hospital::with(['specializations.specialization']);

        // Filter: Name + Address +` PIN Code
        if ($request->filled('name') || $request->filled('address') || $request->filled('zip_code')) {
            $query->where(function($q) use ($request) {
                if ($request->filled('name')) {
                    $q->where('name', 'like', '%' . $request->name . '%');
                }
                if ($request->filled('address')) {
                    $q->where('address', 'like', '%' . $request->address . '%');
                }

                if ($request->filled('zip_code')) {
                    $q->where('zip_code', 'like', '%' . $request->zip_code . '%');
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
                        ->paginate(10);

        if ($request->ajax()) {
            return view('page.ajax.hospital_list', compact('hospitals'))->render();
        }
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


    public function appointment()
    {
        return view('page.appointment');
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

    public function professionalDoctors()
    {
        $doctors = Doctor::with(['educations', 'specializations.specialization', 'locations'])
            ->where('is_professional', 1)
            ->where('status', 1)
            ->where('approval_status', 1)
            ->paginate(30); // 10 doctors per page

        return view('page.professional-doctors', compact('doctors'));
    }


}
