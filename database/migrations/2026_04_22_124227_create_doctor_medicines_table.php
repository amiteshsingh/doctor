<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('doctor_medicines')) {
            Schema::create('doctor_medicines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('added_by');
                $table->string('name');
                $table->string('category')->nullable();
                $table->string('unit')->nullable();
                $table->integer('stock')->default(0);
                $table->decimal('price', 10, 2)->default(0);
                $table->text('description')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_medicines');
    }
};
