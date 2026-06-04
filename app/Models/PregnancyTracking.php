<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PregnancyTracking extends Model {
    protected $table    = 'pregnancy_tracking';
    protected $fillable = ['user_id', 'lmp_date', 'edd'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
