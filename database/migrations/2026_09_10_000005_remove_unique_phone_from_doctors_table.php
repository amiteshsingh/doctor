<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $indexes = collect(\DB::select("SHOW INDEX FROM doctors WHERE Column_name = 'phone_no'"))
                ->pluck('Key_name')->unique();
            foreach ($indexes as $index) {
                if ($index !== 'PRIMARY') {
                    $table->dropIndex($index);
                }
            }
        });
    }

    public function down(): void {}
};
