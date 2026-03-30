<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('prescription_invoice', 'user_id')) {
            Schema::table('prescription_invoice', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('invoice_master_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('prescription_invoice', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
