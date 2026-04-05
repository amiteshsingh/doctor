<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\PrescriptionInvoice;
use App\Models\InvoiceMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'data'   => Specialization::where('status', 1)->orderBy('name')->get(['id', 'name', 'icon_name', 'image'])
        ]);
    }

    public function bookedSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|integer',
            'date'      => 'required|date',
        ]);

        $invoiceMaster = InvoiceMaster::where('doctor_id', $request->doctor_id)->first();

        if (!$invoiceMaster) {
            return response()->json(['status' => 404, 'message' => 'No slot configuration found for this doctor.'], 404);
        }

        $startTime    = $invoiceMaster->start_time       ?? '10:00';
        $endTime      = $invoiceMaster->end_time_slot    ?? '18:00';
        $duration     = (int)($invoiceMaster->duration_time_slot ?? 30);

        // Parse start/end into minutes
        [$sh, $sm] = explode(':', $startTime);
        [$eh, $em] = explode(':', $endTime);
        $startMins = (int)$sh * 60 + (int)$sm;
        $endMins   = (int)$eh * 60 + (int)$em;

        // Already booked times for this date
        $bookedTimes = PrescriptionInvoice::whereHas('invoiceMaster', function ($q) use ($request) {
                $q->where('doctor_id', $request->doctor_id);
            })
            ->where('booking_date', $request->date)
            ->pluck('booking_time')
            ->toArray();

        // Generate all slots
        $now         = now();
        $isToday     = ($request->date === $now->format('Y-m-d'));
        $currentMins = $now->hour * 60 + $now->minute;

        $slots = [];
        for ($m = $startMins; $m < $endMins; $m += $duration) {
            $h     = intdiv($m, 60);
            $min   = $m % 60;
            $value = sprintf('%02d:%02d', $h, $min);
            $ampm  = $h >= 12 ? 'PM' : 'AM';
            $h12   = $h > 12 ? $h - 12 : ($h === 0 ? 12 : $h);
            $label = sprintf('%02d:%02d %s', $h12, $min, $ampm);

            $slots[] = [
                'value'     => $value,
                'label'     => $label,
                'is_booked' => in_array($value, $bookedTimes),
                'is_past'   => $isToday && $m <= $currentMins,
            ];
        }

        return response()->json([
            'status' => 200,
            'date'   => $request->date,
            'config' => [
                'start_time'    => $startTime,
                'end_time'      => $endTime,
                'duration_mins' => $duration,
            ],
            'slots'  => $slots,
        ]);
    }

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'doctor_id'        => 'required|integer',
            'patient_name'     => 'required|string|max:255',
            'age'              => 'required|integer',
            'gender'           => 'required|in:Male,Female,Other',
            'patient_phone_no' => 'required|string|max:20',
            'booking_date'     => 'required|date',
            'booking_time'     => 'required',
        ]);

        $invoiceMaster = InvoiceMaster::where('doctor_id', $request->doctor_id)->first();

        $invoice = new PrescriptionInvoice;
        $invoice->invoice_master_id = $invoiceMaster->id ?? null;
        $invoice->user_id           = $request->user_id;
        $invoice->invoice_number    = 'INV-' . now()->format('YmdHis');
        $invoice->patient_name      = $request->patient_name;
        $invoice->age               = $request->age;
        $invoice->gender            = $request->gender;
        $invoice->patient_address   = $request->patient_address;
        $invoice->patient_phone_no  = $request->patient_phone_no;
        $invoice->booking_date      = $request->booking_date;
        $invoice->booking_time      = $request->booking_time;
        $invoice->created_at        = now();
        $invoice->updated_at        = now();
        $invoice->save();

        return response()->json([
            'status' => 200,
            'msg'    => 'Appointment booked successfully!'
        ]);
    }
}
