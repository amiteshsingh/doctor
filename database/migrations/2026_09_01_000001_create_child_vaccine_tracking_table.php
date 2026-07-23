<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->date('dob'); // date of birth
            $table->string('gender')->nullable(); // Male/Female
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('child_vaccines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('child_id');
            $table->string('vaccine_name');
            $table->date('due_date');
            $table->date('given_date')->nullable(); // null = not given yet
            $table->timestamps();
            $table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('child_vaccines');
        Schema::dropIfExists('children');
    }
};
