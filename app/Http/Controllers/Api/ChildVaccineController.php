<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildVaccine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChildVaccineController extends Controller
{
    // Complete 0-5 year vaccine schedule (age in days from birth)
    private function vaccineSchedule(): array {
        return [
            ['name' => 'BCG',                    'day' => 0,    'label' => 'Janm ke din (Birth)'],
            ['name' => 'OPV-0 (Birth Dose)',     'day' => 0,    'label' => 'Janm ke din (Birth)'],
            ['name' => 'Hepatitis B (Birth)',     'day' => 0,    'label' => 'Janm ke din (Birth)'],
            ['name' => 'OPV-1',                  'day' => 42,   'label' => '6 Hafte (6 Weeks)'],
            ['name' => 'Pentavalent-1',          'day' => 42,   'label' => '6 Hafte (6 Weeks)'],
            ['name' => 'Rotavirus-1',            'day' => 42,   'label' => '6 Hafte (6 Weeks)'],
            ['name' => 'PCV-1',                  'day' => 42,   'label' => '6 Hafte (6 Weeks)'],
            ['name' => 'IPV-1',                  'day' => 42,   'label' => '6 Hafte (6 Weeks)'],
            ['name' => 'OPV-2',                  'day' => 70,   'label' => '10 Hafte (10 Weeks)'],
            ['name' => 'Pentavalent-2',          'day' => 70,   'label' => '10 Hafte (10 Weeks)'],
            ['name' => 'Rotavirus-2',            'day' => 70,   'label' => '10 Hafte (10 Weeks)'],
            ['name' => 'OPV-3',                  'day' => 98,   'label' => '14 Hafte (14 Weeks)'],
            ['name' => 'Pentavalent-3',          'day' => 98,   'label' => '14 Hafte (14 Weeks)'],
            ['name' => 'Rotavirus-3',            'day' => 98,   'label' => '14 Hafte (14 Weeks)'],
            ['name' => 'PCV-2',                  'day' => 98,   'label' => '14 Hafte (14 Weeks)'],
            ['name' => 'IPV-2',                  'day' => 98,   'label' => '14 Hafte (14 Weeks)'],
            ['name' => 'Measles-Rubella (MR-1)', 'day' => 274,  'label' => '9 Mahine (9 Months)'],
            ['name' => 'PCV Booster',            'day' => 274,  'label' => '9 Mahine (9 Months)'],
            ['name' => 'JE-1',                   'day' => 274,  'label' => '9 Mahine (9 Months)'],
            ['name' => 'Vitamin A (1st dose)',   'day' => 274,  'label' => '9 Mahine (9 Months)'],
            ['name' => 'DPT Booster-1',          'day' => 548,  'label' => '18 Mahine (18 Months)'],
            ['name' => 'OPV Booster',            'day' => 548,  'label' => '18 Mahine (18 Months)'],
            ['name' => 'Measles-Rubella (MR-2)', 'day' => 548,  'label' => '18 Mahine (18 Months)'],
            ['name' => 'JE-2',                   'day' => 548,  'label' => '18 Mahine (18 Months)'],
            ['name' => 'Vitamin A (2nd dose)',   'day' => 548,  'label' => '18 Mahine (18 Months)'],
            ['name' => 'Vitamin A (3rd dose)',   'day' => 730,  'label' => '2 Saal (2 Years)'],
            ['name' => 'Vitamin A (4th dose)',   'day' => 912,  'label' => '2.5 Saal'],
            ['name' => 'Vitamin A (5th dose)',   'day' => 1095, 'label' => '3 Saal (3 Years)'],
            ['name' => 'DPT Booster-2',          'day' => 1825, 'label' => '5 Saal (5 Years)'],
            ['name' => 'OPV Booster-2',          'day' => 1825, 'label' => '5 Saal (5 Years)'],
        ];
    }

    // Get all children with their vaccine status
    public function getChildren(Request $request) {
        $user = $request->auth_user;
        $children = Child::where('user_id', $user->id)
            ->with('vaccines')
            ->get()
            ->map(function ($child) {
                $dob = Carbon::parse($child->dob);
                $ageInDays = $dob->diffInDays(Carbon::now());
                $schedule = $this->vaccineSchedule();

                $vaccines = collect($schedule)->map(function ($v) use ($child, $dob) {
                    $dueDate = $dob->copy()->addDays($v['day'])->format('Y-m-d');
                    $record = $child->vaccines->firstWhere('vaccine_name', $v['name']);
                    return [
                        'vaccine_name' => $v['name'],
                        'label'        => $v['label'],
                        'due_date'     => $dueDate,
                        'given_date'   => $record?->given_date,
                        'is_given'     => !is_null($record?->given_date),
                        'is_overdue'   => is_null($record?->given_date) && Carbon::parse($dueDate)->isPast(),
                        'is_upcoming'  => is_null($record?->given_date) && !Carbon::parse($dueDate)->isPast(),
                        'id'           => $record?->id,
                    ];
                });

                return [
                    'id'          => $child->id,
                    'name'        => $child->name,
                    'dob'         => $child->dob,
                    'gender'      => $child->gender,
                    'age_days'    => $ageInDays,
                    'age_label'   => $this->ageLabel($ageInDays),
                    'vaccines'    => $vaccines,
                    'total'       => $vaccines->count(),
                    'given'       => $vaccines->where('is_given', true)->count(),
                    'overdue'     => $vaccines->where('is_overdue', true)->count(),
                    'upcoming'    => $vaccines->where('is_upcoming', true)->count(),
                ];
            });

        return response()->json(['status' => 200, 'data' => $children]);
    }

    // Add a new child
    public function addChild(Request $request) {
        $request->validate([
            'name'   => 'required|string',
            'dob'    => 'required|date',
            'gender' => 'nullable|string',
        ]);

        $user  = $request->auth_user;
        $child = Child::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'dob'     => $request->dob,
            'gender'  => $request->gender,
        ]);

        // Auto-create vaccine records from schedule
        $dob = Carbon::parse($request->dob);
        foreach ($this->vaccineSchedule() as $v) {
            ChildVaccine::create([
                'child_id'     => $child->id,
                'vaccine_name' => $v['name'],
                'due_date'     => $dob->copy()->addDays($v['day'])->format('Y-m-d'),
                'given_date'   => null,
            ]);
        }

        return response()->json(['status' => 200, 'message' => 'Child added successfully', 'child_id' => $child->id]);
    }

    // Mark vaccine as given
    public function markVaccine(Request $request) {
        $request->validate([
            'child_id'     => 'required|integer',
            'vaccine_name' => 'required|string',
            'given_date'   => 'required|date',
        ]);

        $user  = $request->auth_user;
        $child = Child::where('id', $request->child_id)->where('user_id', $user->id)->firstOrFail();

        ChildVaccine::where('child_id', $child->id)
            ->where('vaccine_name', $request->vaccine_name)
            ->update(['given_date' => $request->given_date]);

        return response()->json(['status' => 200, 'message' => 'Vaccine marked as given']);
    }

    // Delete a child
    public function deleteChild(Request $request, $id) {
        $user = $request->auth_user;
        Child::where('id', $id)->where('user_id', $user->id)->delete();
        return response()->json(['status' => 200, 'message' => 'Child deleted']);
    }

    private function ageLabel(int $days): string {
        if ($days < 30)   return $days . ' din';
        if ($days < 365)  return floor($days / 30) . ' mahine';
        $years  = floor($days / 365);
        $months = floor(($days % 365) / 30);
        return $months > 0 ? "{$years} saal {$months} mahine" : "{$years} saal";
    }
}
