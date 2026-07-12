<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'birth_place',
        'birth_date',
        'gender',
        'education_level',
        'major',
        'experience_years',
        'address',
        'province',
        'city',
        'postal_code',
        'cv_path',
        'photo_path',
        'extras',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'extras' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
