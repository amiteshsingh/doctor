<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_quiz_questions', function (Blueprint $table) {
            // Drop old unique constraint on quiz_date
            $table->dropUnique(['quiz_date']);
            // Add batch schedule columns
            $table->unsignedBigInteger('batch_id')->nullable()->after('id');
            $table->dateTime('starts_at')->nullable()->after('quiz_date');
            $table->dateTime('ends_at')->nullable()->after('starts_at');
            $table->unsignedInteger('sort_order')->default(0)->after('ends_at');
        });

        // Batch groups table
        Schema::create('daily_quiz_batches', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_quiz_batches');
        Schema::table('daily_quiz_questions', function (Blueprint $table) {
            $table->dropColumn(['batch_id', 'starts_at', 'ends_at', 'sort_order']);
            $table->unique('quiz_date');
        });
    }
};
