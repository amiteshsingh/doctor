<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildVaccine extends Model {
    protected $fillable = ['child_id', 'vaccine_name', 'due_date', 'given_date'];

    public function child() {
        return $this->belongsTo(Child::class);
    }
}
