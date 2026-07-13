<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'category',
        'key',
        'label',
        'type',
        'config',
        'default_status',
        'default_weight',
        'sort_order'
    ];

    protected $casts = [
        'config' => 'array',
        'default_weight' => 'integer',
        'sort_order' => 'integer'
    ];

    // Scope: fetch criteria for a category
    public function scopeForCategory($query, string $category)
    {
        return $query->where('category', $category)->orderBy('sort_order');
    }
}
