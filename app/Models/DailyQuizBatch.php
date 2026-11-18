<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DailyQuizAttempt;

class DailyQuizBatch extends Model
{
    protected $fillable = ['title', 'starts_at', 'ends_at', 'is_active', 'max_participants'];

    protected $casts = [
        'starts_at'        => 'datetime',
        'ends_at'          => 'datetime',
        'is_active'        => 'boolean',
        'max_participants' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(DailyQuizQuestion::class, 'batch_id')->orderBy('sort_order');
    }

    /** Count unique users who attempted any question in this batch */
    public function participantCount(): int
    {
        return DailyQuizAttempt::whereIn('question_id', $this->questions()->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');
    }

    public static function activeBatch()
    {
        $now = now();
        return static::where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->first();
    }
}
