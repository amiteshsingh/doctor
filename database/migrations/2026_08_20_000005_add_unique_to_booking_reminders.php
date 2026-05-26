<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('booking_reminders', function (Blueprint $table) {
            $table->unique(['invoice_id', 'minutes_before']);
        });
    }

    public function down(): void
    {
        Schema::table('booking_reminders', function (Blueprint $table) {
            $table->dropUnique(['invoice_id', 'minutes_before']);
        });
    }
};
