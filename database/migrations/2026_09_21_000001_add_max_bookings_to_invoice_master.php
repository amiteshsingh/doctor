<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('invoice_master', function (Blueprint $table) {
            $table->unsignedInteger('max_bookings')->default(20)->after('duration_time_slot');
        });
    }
    public function down(): void {
        Schema::table('invoice_master', function (Blueprint $table) {
            $table->dropColumn('max_bookings');
        });
    }
};
