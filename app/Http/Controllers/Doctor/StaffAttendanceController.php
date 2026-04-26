<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffAttendanceController extends Controller
{
    // Show attendance marking page
    public function index(Request $request)
    {
        $date  = $request->get('date', today()->toDateString());
        $staff = DB::table('doctor_staff')
            ->where('added_by', Auth::id())
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        // Get existing attendance for this date
        $existing = DB::table('doctor_staff_attendance')
            ->where('added_by', Auth::id())
            ->where('attendance_date', $date)
            ->get()
            ->keyBy('staff_id');

        return view('doctor.staff.attendance', compact('staff', 'date', 'existing'));
    }

    // Save attendance (AJAX)
    public function save(Request $request)
    {
        if (!$request->isMethod('post')) {
            return response()->json(['status' => 405, 'msg' => 'Invalid request.']);
        }

        try {
            $date      = $request->date;
            $attendances = $request->attendance ?? [];

            foreach ($attendances as $staffId => $row) {
                DB::table('doctor_staff_attendance')->updateOrInsert(
                    ['staff_id' => $staffId, 'attendance_date' => $date],
                    [
                        'added_by'   => Auth::id(),
                        'status'     => $row['status'] ?? 'absent',
                        'check_in'   => !empty($row['check_in'])  ? $row['check_in']  : null,
                        'check_out'  => !empty($row['check_out']) ? $row['check_out'] : null,
                        'note'       => $row['note'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            return response()->json(['status' => 200, 'msg' => 'Attendance saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }
    }

    // Monthly report
    public function report(Request $request)
    {
        $month = $request->get('month', today()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $staff = DB::table('doctor_staff')
            ->where('added_by', Auth::id())
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $daysInMonth = Carbon::create($year, $mon)->daysInMonth;

        // Get all attendance for this month
        $records = DB::table('doctor_staff_attendance')
            ->where('added_by', Auth::id())
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $mon)
            ->get()
            ->groupBy('staff_id');

        return view('doctor.staff.attendance_report', compact('staff', 'month', 'year', 'mon', 'daysInMonth', 'records'));
    }
}
