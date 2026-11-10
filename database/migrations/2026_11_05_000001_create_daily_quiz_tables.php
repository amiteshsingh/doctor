<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_answer', ['A', 'B', 'C', 'D']);
            $table->text('explanation')->nullable();
            $table->string('category')->default('General');
            $table->date('quiz_date')->unique();
            $table->integer('time_limit')->default(30); // seconds per question
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('daily_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('question_id');
            $table->enum('selected_answer', ['A', 'B', 'C', 'D'])->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('time_taken')->default(0); // seconds
            $table->timestamps();
            $table->unique(['user_id', 'question_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('daily_quiz_questions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_quiz_attempts');
        Schema::dropIfExists('daily_quiz_questions');
    }
};
