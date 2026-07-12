<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'user_id',
        'gender',
        'birth_date',
        'education_level',
        'major',
        'has_agd',
        'agd_certificate_path',
        'sim_c_path',
        'sim_b1_path',
        'additional_documents',
        'experience_years',
        'placement_ready',
        'placement_choice',
        'is_priority',
        'matching_score',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'has_agd' => 'boolean',
        'placement_ready' => 'boolean',
        'is_priority' => 'boolean',
        'additional_documents' => 'array',
    ];

    protected $appends = ['age'];

    public function posting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        return Carbon::parse($this->birth_date)->age;
    }
}
