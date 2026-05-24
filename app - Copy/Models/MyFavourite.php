<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyFavourite extends Model
{
    protected $table = 'my_favourites';
    protected $fillable = ['user_id', 'doctor_id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
