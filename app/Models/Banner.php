<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model {
    protected $fillable = ['title', 'subtitle', 'icon', 'color', 'image', 'link', 'button_text', 'button_url', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
