<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('doctor_staff')) {
            Schema::create('doctor_staff', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('added_by');
                $table->string('name');
                $table->string('role')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('address')->nullable();
                $table->decimal('salary', 10, 2)->nullable();
                $table->date('joining_date')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_staff');
    }
};
