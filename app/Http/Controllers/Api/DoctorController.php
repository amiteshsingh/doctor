<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with([
            'availability',
            'educations',
            'languages.language',
            'specializations.specialization',
            'locations'
        ])
        ->where('status', 1)
        ->where('approval_status', 1);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function ($q) use ($request) {
                $q->whereHas('specialization', function ($sp) use ($request) {
                    $sp->where('name', 'like', '%' . $request->specialization . '%');
                });
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('city')) {
            $query->whereHas('locations', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->city . '%');
            });
        }

        if ($request->filled('zip_code')) {
            $query->whereHas('locations', function ($q) use ($request) {
                $q->where('zip_code', 'like', '%' . $request->zip_code . '%');
            });
        }

        $doctors = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status'  => 200,
            'data'    => $doctors->items(),
            'meta'    => [
                'current_page' => $doctors->currentPage(),
                'last_page'    => $doctors->lastPage(),
                'per_page'     => $doctors->perPage(),
                'total'        => $doctors->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $doctor = Doctor::with([
            'availability',
            'educations',
            'languages.language',
            'specializations.specialization',
            'locations'
        ])
        ->where('status', 1)
        ->where('approval_status', 1)
        ->findOrFail($id);

        return response()->json([
            'status' => 200,
            'data'   => $doctor
        ]);
    }

    public function specializations()
    {
        return response()->json([
            'status' => 200,
            'data'   => Specialization::where('status', 1)->orderBy('name')->get(['id', 'name'])
        ]);
    }
}
