<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            foreach (['phone_no', 'email'] as $col) {
                $indexes = collect(\DB::select("SHOW INDEX FROM doctors WHERE Column_name = '$col'"))
                    ->pluck('Key_name')->unique();
                foreach ($indexes as $index) {
                    if ($index !== 'PRIMARY') {
                        $table->dropIndex($index);
                    }
                }
            }
        });
    }

    public function down(): void {}
};
