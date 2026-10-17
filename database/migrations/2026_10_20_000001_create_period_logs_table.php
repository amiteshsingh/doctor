<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('period_logs')) return;
        Schema::create('period_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('log_date');
            $table->json('symptoms')->nullable();
            $table->string('flow', 20)->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'log_date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('period_logs');
    }
};
