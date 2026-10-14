<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_module_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('module_key', 64);
            $table->boolean('access_module')->default(false);
            $table->boolean('view_data')->default(false);
            $table->boolean('delete_data')->default(false);
            $table->timestamps();

            $table->unique(['admin_user_id', 'module_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_module_permissions');
    }
};
