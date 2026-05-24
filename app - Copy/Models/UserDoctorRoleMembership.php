<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDoctorRoleMembership extends Model
{
    protected $table = 'user_doctor_role_membership';

    protected $fillable = [
        'user_id',
        'membership_amount',
        'membership_subscription_date',
        'membership_subscription_end_date',
        'attendance_permission',
        'invoice_permission',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
