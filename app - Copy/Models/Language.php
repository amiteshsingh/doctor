<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected  $table = 'languages';

    public function doctorLanguages()
    {
        return $this->hasMany(DoctorLanguage::class, 'language_id');
    }
}
