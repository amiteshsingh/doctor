<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_master', function (Blueprint $table) {
            $table->string('token_mode', 20)->default('AUTOMATIC')->after('booking_mode');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_master', function (Blueprint $table) {
            $table->dropColumn('token_mode');
        });
    }
};
