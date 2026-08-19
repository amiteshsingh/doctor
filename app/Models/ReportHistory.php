<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportHistory extends Model {
    protected $fillable = [
        'user_id', 'report_type', 'patient_info',
        'summary', 'normal_count', 'abnormal_count', 'sections_json', 'image_path',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
