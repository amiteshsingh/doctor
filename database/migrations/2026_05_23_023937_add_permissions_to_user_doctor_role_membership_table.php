<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_doctor_role_membership', function (Blueprint $table) {
            $table->tinyInteger('attendance_permission')->default(0)->after('membership_subscription_end_date');
            $table->tinyInteger('invoice_permission')->default(0)->after('attendance_permission');
        });
    }

    public function down(): void
    {
        Schema::table('user_doctor_role_membership', function (Blueprint $table) {
            $table->dropColumn(['attendance_permission', 'invoice_permission']);
        });
    }
};
