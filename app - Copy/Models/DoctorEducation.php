<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorEducation extends Model
{
    protected $table = 'doctor_educations';
    protected $fillable = ['doctor_id', 'degree_type', 'institution_name', 'graduation_year', 'details'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
