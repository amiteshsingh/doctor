<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuizAttempt extends Model
{
    protected $fillable = [
        'user_id', 'question_id', 'selected_answer', 'is_correct', 'time_taken',
    ];

    protected $casts = ['is_correct' => 'boolean'];

    public function question()
    {
        return $this->belongsTo(DailyQuizQuestion::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
