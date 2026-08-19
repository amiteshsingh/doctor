<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('report_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('report_type')->nullable();
            $table->text('patient_info')->nullable();
            $table->text('summary')->nullable();
            $table->integer('normal_count')->default(0);
            $table->integer('abnormal_count')->default(0);
            $table->longText('sections_json'); // full AI response
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('report_histories');
    }
};
