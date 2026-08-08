<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('doctors', function (Blueprint $table) {
            $table->tinyInteger('is_emergency')->default(0)->after('is_professional');
        });
    }
    public function down(): void {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('is_emergency');
        });
    }
};
