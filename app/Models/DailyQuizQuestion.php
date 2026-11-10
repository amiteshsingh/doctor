<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuizQuestion extends Model
{
    protected $fillable = [
        'question', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_answer', 'explanation', 'category', 'quiz_date',
        'time_limit', 'is_active',
    ];

    protected $casts = [
        'quiz_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function attempts()
    {
        return $this->hasMany(DailyQuizAttempt::class, 'question_id');
    }

    public static function todayQuestion()
    {
        return static::where('quiz_date', today())->where('is_active', true)->first();
    }
}
