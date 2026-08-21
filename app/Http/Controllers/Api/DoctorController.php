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

        $doctors = $query->paginate($request->get('per_page', 50000));

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
            'locations',
        ])
        ->where('status', 1)
        ->where('approval_status', 1)
        ->findOrFail($id);

        $gallery = \DB::table('doctor_images')
            ->where('doctor_id', $id)
            ->get()
            ->map(fn($img) => [
                'id'  => $img->id,
                'url' => asset('uploads/doctor_gallery/' . $img->image),
            ]);

        $invoice_master = \DB::table('invoice_master')
            ->select('id','doctor_id','hospital_clinic_name','consultation_fee', 'start_time',
             'end_time_slot', 'duration_time_slot','booking_mode','address','phone_no')
            ->where('doctor_id', $id)
            ->get();
            

        return response()->json([
            'status' => 200,
            'data'   => $doctor,
            'gallery'=> $gallery,
            'invoice_master' => $invoice_master,
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
        $doctorId        = $request->input('doctor_id');
        $invoiceMasterId = $request->input('invoice_master_id');
        $date            = $request->input('date');

        if ((!$doctorId && !$invoiceMasterId) || !$date) {
            return response()->json(['status' => 422, 'message' => 'doctor_id or invoice_master_id and date are required.', 'slots' => []]);
        }

        $invoiceMaster = $invoiceMasterId
            ? InvoiceMaster::find($invoiceMasterId)
            : InvoiceMaster::where('doctor_id', $doctorId)->first();

        if (!$invoiceMaster || !$invoiceMaster->start_time || !$invoiceMaster->end_time_slot || !$invoiceMaster->duration_time_slot) {
            return response()->json(['status' => 404, 'message' => 'No slot configuration found for this doctor.', 'slots' => []]);
        }

        $doctorId = $invoiceMaster->doctor_id;

        $startTime    = $invoiceMaster->start_time       ?? '10:00';
        $endTime      = $invoiceMaster->end_time_slot    ?? '18:00';
        $duration     = (int)($invoiceMaster->duration_time_slot ?? 30);

        // Parse start/end into minutes
        [$sh, $sm] = explode(':', $startTime);
        [$eh, $em] = explode(':', $endTime);
        $startMins = (int)$sh * 60 + (int)$sm;
        $endMins   = (int)$eh * 60 + (int)$em;

        // Already booked times for this date — normalize to h:i A (exclude cancelled)
        $bookedTimes = PrescriptionInvoice::whereHas('invoiceMaster', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->where('booking_date', $date)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'cancelled'); })
            ->pluck('booking_time')
            ->map(function ($time) {
                try {
                    return \Carbon\Carbon::createFromFormat('h:i A', trim($time))->format('H:i');
                } catch (\Exception $e) {
                    return $time;
                }
            })
            ->toArray();

        // Generate all slots
        $now         = now('Asia/Kolkata');
        $isToday     = ($date === $now->format('Y-m-d'));
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
            'date'   => $date,
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
        ]);

        $invoiceMasterId = $request->input('invoice_master_id');
        $invoiceMaster = $invoiceMasterId
            ? InvoiceMaster::find($invoiceMasterId)
            : InvoiceMaster::where('doctor_id', $request->doctor_id)->first();

        // Queue number: us date ke liye is doctor ke active bookings count + 1
        $queueNumber = PrescriptionInvoice::where('invoice_master_id', $invoiceMaster->id ?? null)
            ->where('booking_date', $request->booking_date)
            ->where(function($q) { $q->whereNull('status')->orWhere('status', '!=', 'cancelled'); })
            ->count() + 1;

        $invoice = new PrescriptionInvoice;
        $invoice->invoice_master_id = $invoiceMaster->id ?? null;
        $invoice->user_id           = $request->auth_user->id ?? null;
        $invoice->invoice_number    = 'INV-' . now()->format('YmdHis');
        $invoice->patient_name      = $request->patient_name;
        $invoice->age               = $request->age;
        $invoice->gender            = $request->gender;
        $invoice->patient_address   = $request->patient_address;
        $invoice->patient_phone_no  = $request->patient_phone_no;
        $invoice->booking_date      = $request->booking_date;
        $normalizedTime             = $this->normalizeTime($invoiceMaster->start_time ?? '10:00');
        $invoice->booking_time      = $normalizedTime;
        $invoice->queue_number      = $queueNumber;
        $invoice->created_at        = now();
        $invoice->updated_at        = now();
        $invoice->save();

        // ── Push notification to doctor ──
        $doctorUser = \App\Models\User::where('id', function($q) use ($invoiceMaster) {
            $q->select('added_by')->from('invoice_master')->where('id', $invoiceMaster->id ?? 0);
        })->first();

        if ($doctorUser && $doctorUser->fcm_token) {
            \App\Services\FirebaseNotification::send(
                $doctorUser->fcm_token,
                '📅 New Appointment Booked',
                "{$request->patient_name} ne {$request->booking_date} ko {$normalizedTime} pe appointment book ki hai.",
                [
                    'type'           => 'new_appointment',
                    'appointment_id' => (string)$invoice->id,
                    'patient_name'   => $request->patient_name,
                    'booking_date'   => $request->booking_date,
                    'booking_time'   => $normalizedTime,
                ]
            );
        }

        return response()->json([
            'status'       => 200,
            'msg'          => 'Appointment booked successfully!',
            'booking'      => [
                'id'               => $invoice->id,
                'queue_number'     => $queueNumber,
                'patient_name'     => $invoice->patient_name,
                'patient_phone_no' => $invoice->patient_phone_no,
                'age'              => $invoice->age,
                'gender'           => $invoice->gender,
                'patient_address'  => $invoice->patient_address,
                'booking_date'     => $invoice->booking_date,
                'booking_time'     => $invoice->booking_time,
                'doctor_name'      => $invoiceMaster ? optional(\App\Models\Doctor::find($invoiceMaster->doctor_id))->name : null,
                'clinic_name'      => $invoiceMaster->hospital_clinic_name ?? null,
            ],
        ]);
    }

    private function normalizeTime($time)
    {
        $time = trim($time);
        // Already h:i A format
        if (preg_match('/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i', $time, $m)) {
            return sprintf('%02d:%02d %s', (int)$m[1], (int)$m[2], strtoupper($m[3]));
        }
        // 24-hour HH:MM format — convert manually
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $time, $m)) {
            $h   = (int)$m[1];
            $min = (int)$m[2];
            $ampm = $h >= 12 ? 'PM' : 'AM';
            $h12  = $h % 12 ?: 12;
            return sprintf('%02d:%02d %s', $h12, $min, $ampm);
        }
        return $time;
    }
}
