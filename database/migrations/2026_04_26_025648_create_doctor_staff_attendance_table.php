<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_staff_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('added_by');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'half_day', 'leave'])->default('present');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('doctor_staff')->onDelete('cascade');
            $table->unique(['staff_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_staff_attendance');
    }
};
