<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Drop unique index on phone_no if exists
            try {
                $table->dropUnique(['phone_no']);
            } catch (\Exception $e) {
                // Index doesn't exist, ignore
            }
        });
    }

    public function down(): void {}
};
