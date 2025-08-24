<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalSpecialization extends Model
{
    protected $table = 'hospital_specializations';
    protected $fillable = ['hospital_id', 'specialization_id'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
    
    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
}
