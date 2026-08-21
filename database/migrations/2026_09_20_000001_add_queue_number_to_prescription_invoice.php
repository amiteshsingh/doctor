<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescription_invoice', function (Blueprint $table) {
            $table->unsignedInteger('queue_number')->nullable()->after('booking_time');
        });
    }

    public function down(): void
    {
        Schema::table('prescription_invoice', function (Blueprint $table) {
            $table->dropColumn('queue_number');
        });
    }
};
