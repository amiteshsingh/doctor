<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorLocation extends Model
{
    protected $table = 'doctor_locations';
    protected $fillable = ['doctor_id', 'practice_name', 'address', 'city', 'state', 'zip_code', 'phone', 'website'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
