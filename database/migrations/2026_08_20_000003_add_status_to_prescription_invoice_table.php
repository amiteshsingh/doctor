<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('prescription_invoice', 'status')) {
            Schema::table('prescription_invoice', function (Blueprint $table) {
                $table->string('status')->default('active')->after('booking_time');
            });
        }
    }

    public function down(): void
    {
        Schema::table('prescription_invoice', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
