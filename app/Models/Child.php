<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model {
    protected $fillable = ['user_id', 'name', 'dob', 'gender'];

    public function vaccines() {
        return $this->hasMany(ChildVaccine::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
