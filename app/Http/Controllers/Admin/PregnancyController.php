<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PregnancyController extends Controller
{
    public function index()
    {
        $pregnancies = DB::table('pregnancy_tracking')
            ->join('users', 'pregnancy_tracking.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                'users.phone_no',
                'pregnancy_tracking.lmp_date',
                'pregnancy_tracking.edd',
                'pregnancy_tracking.updated_at'
            )
            ->orderBy('pregnancy_tracking.edd', 'asc')
            ->get()
            ->map(function ($row) {
                $edd = \Carbon\Carbon::parse($row->edd);
                $now = \Carbon\Carbon::now();
                $row->days_left   = $now->diffInDays($edd, false);
                $row->weeks_gone  = (int) \Carbon\Carbon::parse($row->lmp_date)->diffInWeeks($now);
                $row->is_past     = $edd->isPast();
                return $row;
            });

        return view('admin.pregnancy.index', compact('pregnancies'));
    }
}
