<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuizBatch extends Model
{
    protected $fillable = ['title', 'starts_at', 'ends_at', 'is_active'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'is_active' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(DailyQuizQuestion::class, 'batch_id')->orderBy('sort_order');
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
