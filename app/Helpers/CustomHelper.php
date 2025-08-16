<?php

use Illuminate\Support\Facades\DB;

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
