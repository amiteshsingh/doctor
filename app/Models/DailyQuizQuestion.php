<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuizQuestion extends Model
{
    protected $fillable = [
        'batch_id', 'question', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_answer', 'explanation', 'category', 'quiz_date',
        'starts_at', 'ends_at', 'sort_order', 'time_limit', 'is_active',
    ];

    protected $casts = [
        'quiz_date'  => 'date',
        'starts_at'  => 'datetime',
        'ends_at'    => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function batch()
    {
        return $this->belongsTo(DailyQuizBatch::class, 'batch_id');
    }

    public function attempts()
    {
        return $this->hasMany(DailyQuizAttempt::class, 'question_id');
    }

    // Returns all questions of the currently active batch
    public static function activeBatchQuestions()
    {
        $batch = DailyQuizBatch::activeBatch();
        if (!$batch) return collect();
        return static::where('batch_id', $batch->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($q) use ($batch) {
                $q->batch_ends_at = $batch->ends_at;
                return $q;
            });
    }

    public static function todayQuestion()
    {
        return static::where('quiz_date', today())->where('is_active', true)->first();
    }
}
