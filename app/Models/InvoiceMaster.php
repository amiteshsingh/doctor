<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;

class InvoiceMaster extends Model
{
    protected $table = 'invoice_master';

    protected $fillable = [
        'doctor_id',
        'hospital_clinic_name',
        'consultation_fee',
        'booking_mode',
        'address',
        'phone_no',
        'email',
        'start_time',
        'end_time_slot',
        'duration_time_slot',
        'max_bookings',
    ];

    public function prescriptionInvoices()
    {
        return $this->hasMany(PrescriptionInvoice::class, 'invoice_master_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

}
