<?php

use Illuminate\Support\Facades\DB;

use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Support\Facades\Session;

if (!function_exists('get_location')) {
    function get_location($user_id)
    {
        $location = DB::table('doctor_locations')
            ->where('doctor_id', $user_id)
            ->select('address', 'city', 'state', 'zip_code')
            ->first();

        if ($location) {
            return $location->address . ', ' . $location->city . ', ' . $location->state . ' - ' . $location->zip_code;
        }

        return 'Location not found';
    }
}

if (!function_exists('get_specialization')) {
    function get_specialization($user_id)
    {
        $names = DB::table('doctor_specializations as ds')
            ->join('specializations as s', 'ds.specialization_id', '=', 's.id')
            ->where('ds.doctor_id', $user_id)
            ->where('s.status', 1) // only active specializations
            ->pluck('s.name') // only names, no objects
            ->toArray();

        if (!empty($names)) {
            return implode(', ', $names); // "Cardiology, Neurology"
        }

        return 'Specialization not found';
    }
}


if (! function_exists('getTotalDoctorsBySession')) {

    function getTotalDoctorsBySession()
    {
        $sessionId = Auth::id(); // ya Auth::id() if using auth()
        return Doctor::where('added_by', $sessionId)->count();
    }
}

if (! function_exists('getTotalHospitalsBySession')) {

    function getTotalHospitalsBySession()
    {
        $sessionId = Auth::id(); // ya Auth::id() if using auth()
        return Hospital::where('added_by', $sessionId)->count();
    }
}
