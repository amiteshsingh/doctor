<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\PrescriptionInvoice;
use App\Models\InvoiceMaster;
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

    public function bookedSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|integer',
            'date'      => 'required|date',
        ]);

        $bookedTimes = PrescriptionInvoice::whereHas('invoiceMaster', function ($q) use ($request) {
                $q->where('doctor_id', $request->doctor_id);
            })
            ->where('booking_date', $request->date)
            ->pluck('booking_time');

        return response()->json([
            'status' => 200,
            'data'   => $bookedTimes
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
