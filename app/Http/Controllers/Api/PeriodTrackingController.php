<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\FirebaseNotification;

class PeriodTrackingController extends Controller
{
    public function get(Request $request)
    {
        $user   = $request->auth_user;
        $record = DB::table('period_tracking')->where('user_id', $user->id)->first();

        if (!$record) {
            return response()->json(['status' => 200, 'data' => null]);
        }

        $analysis = $this->analyze($record);

        return response()->json(['status' => 200, 'data' => array_merge((array)$record, $analysis)]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'last_period_date' => 'required|date',
            'cycle_length'     => 'required|integer|min:21|max:45',
            'period_duration'  => 'required|integer|min:2|max:10',
        ]);

        $user = $request->auth_user;

        $data = [
            'user_id'          => $user->id,
            'last_period_date' => $request->last_period_date,
            'cycle_length'     => $request->cycle_length,
            'period_duration'  => $request->period_duration,
            'updated_at'       => now(),
        ];

        $exists = DB::table('period_tracking')->where('user_id', $user->id)->exists();
        if ($exists) {
            DB::table('period_tracking')->where('user_id', $user->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('period_tracking')->insert($data);
        }

        $record   = DB::table('period_tracking')->where('user_id', $user->id)->first();
        $analysis = $this->analyze($record);

        return response()->json(['status' => 200, 'message' => 'Saved successfully.', 'data' => array_merge((array)$record, $analysis)]);
    }

    private function analyze($record): array
    {
        $lastPeriod    = Carbon::parse($record->last_period_date);
        $cycleLength   = (int)$record->cycle_length;
        $periodDur     = (int)$record->period_duration;
        $today         = Carbon::today();

        // Next period date
        $nextPeriod    = $lastPeriod->copy()->addDays($cycleLength);

        // Days until next period
        $daysUntil     = $today->diffInDays($nextPeriod, false);

        // Ovulation day (cycle_length - 14)
        $ovulationDay  = $lastPeriod->copy()->addDays($cycleLength - 14);

        // Fertile window (ovulation -5 to +1)
        $fertileStart  = $ovulationDay->copy()->subDays(5);
        $fertileEnd    = $ovulationDay->copy()->addDay();

        // Current phase
        $dayOfCycle    = $lastPeriod->diffInDays($today) % $cycleLength;
        if ($dayOfCycle < 0) $dayOfCycle = 0;

        if ($dayOfCycle < $periodDur) {
            $phase = 'period';
        } elseif ($today->between($fertileStart, $fertileEnd)) {
            $phase = 'fertile';
        } elseif ($today->isSameDay($ovulationDay)) {
            $phase = 'ovulation';
        } elseif ($dayOfCycle < $cycleLength - 14) {
            $phase = 'follicular';
        } else {
            $phase = 'luteal';
        }

        // Regularity check
        $isRegular = $cycleLength >= 21 && $cycleLength <= 35;

        return [
            'next_period_date'  => $nextPeriod->format('Y-m-d'),
            'days_until_period' => (int)$daysUntil,
            'ovulation_date'    => $ovulationDay->format('Y-m-d'),
            'fertile_start'     => $fertileStart->format('Y-m-d'),
            'fertile_end'       => $fertileEnd->format('Y-m-d'),
            'current_phase'     => $phase,
            'day_of_cycle'      => (int)$dayOfCycle + 1,
            'is_regular'        => $isRegular,
            'regularity_msg'    => $isRegular
                ? 'आपका मासिक चक्र नियमित है ✅'
                : 'आपका मासिक चक्र अनियमित है ⚠️ — डॉक्टर से सलाह लें',
        ];
    }
}
