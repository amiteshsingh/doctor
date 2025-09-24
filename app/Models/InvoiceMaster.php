<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceMaster extends Model
{
    protected $table = 'invoice_master';

    protected $fillable = [
        'doctor_id',
        'hospital_clinic_name',
        'consultation_fee',
    ];

    public function prescriptionInvoices()
    {
        return $this->hasMany(PrescriptionInvoice::class, 'invoice_master_id');
    }
}
