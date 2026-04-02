<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('my_favourites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('doctor_id');
            $table->timestamps();
            $table->unique(['user_id', 'doctor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('my_favourites');
    }
};
