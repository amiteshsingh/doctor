<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pregnancy_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('lmp_date'); // Last Menstrual Period
            $table->date('edd');      // Expected Delivery Date
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pregnancy_tracking'); }
};
