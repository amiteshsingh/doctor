<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSpecialization extends Model
{
    protected $table = 'doctor_specializations';
    protected $fillable = ['doctor_id', 'specialization_id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    
    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

}
