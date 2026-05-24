<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    protected $table = 'doctor_availability'; // table name
    protected $fillable = ['doctor_id', 'day', 'start_time', 'end_time'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
