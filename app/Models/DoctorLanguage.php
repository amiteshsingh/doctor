<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorLanguage extends Model
{
    protected $table = 'doctor_languages';
    protected $fillable = ['doctor_id', 'language_id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

}
