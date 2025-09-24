<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionInvoice extends Model
{
    protected $table = 'prescription_invoice';

    protected $fillable = [
        'invoice_master_id',
        'invoice_number',
        'patient_name',
        'patient_address',
        'patient_phone_no',
    ];

    public function master()
    {
        return $this->belongsTo(InvoiceMaster::class, 'invoice_master_id');
    }
}
