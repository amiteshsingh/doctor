<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Default: payment enabled
        DB::table('app_settings')->insert([
            ['key' => 'razorpay_enabled',  'value' => '1',                                    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'razorpay_key_id',   'value' => 'rzp_test_TG7oWaY3UZvOZo',             'created_at' => now(), 'updated_at' => now()],
            ['key' => 'platform_fee',      'value' => '1',                                    'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('app_settings');
    }
};
