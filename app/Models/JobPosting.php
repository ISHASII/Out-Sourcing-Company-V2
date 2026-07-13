<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'category',
        'description',
        'core_gender',
        'core_min_age',
        'core_max_age',
        'core_min_education',
        'core_requires_agd',
        'core_requires_sim_c',
        'core_requires_sim_b1',
        'second_min_experience',
        'second_requires_placement_ready',
        'location_city',
        'shift_type',
        'salary_min',
        'salary_max',
        'salary_hidden',
        'is_active',
        'active_until',
        'created_by',
        'requirements_config',
    ];

    protected $casts = [
        'core_requires_agd' => 'boolean',
        'core_requires_sim_c' => 'boolean',
        'core_requires_sim_b1' => 'boolean',
        'second_requires_placement_ready' => 'boolean',
        'salary_hidden' => 'boolean',
        'is_active' => 'boolean',
        'active_until' => 'date',
        'requirements_config' => 'array',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function isExpired(): bool
    {
        if (!$this->is_active) {
            return true;
        }
        if ($this->active_until && $this->active_until->lt(\Carbon\Carbon::today())) {
            return true;
        }
        return false;
    }

    public function calculateSpkScore(JobApplication $application): array
    {
        $config = $this->requirements_config;

        if (empty($config) || !isset($config['criteria'])) {
            $isPriority = $this->meetsRequirements($application);
            return [
                'is_priority' => $isPriority,
                'matching_score' => $isPriority ? 100 : 50
            ];
        }

        $isPriority = true;
        $totalScore = 0.0;
        $totalWeight = 0.0;

        // Langkah 3: Konversi Gap ke Bobot Nilai (Kusrini, 2007)
        $gapToWeight = function(float $gap): float {
            $map = [
                '0'  => 5.0,
                '1'  => 4.5,
                '-1' => 4.0,
                '2'  => 3.5,
                '-2' => 3.0,
                '3'  => 2.5,
                '-3' => 2.0,
                '4'  => 1.5,
                '-4' => 1.0,
            ];
            $key = (string) (int) round($gap);
            return $map[$key] ?? ($gap > 0 ? 1.5 : 1.0);
        };

        foreach ($config['criteria'] as $c) {
            $key = $c['key'];
            $status = $c['status'] ?? 'nonaktif';
            $weightPercent = (int) ($c['weight'] ?? 0);
            
            if ($status === 'nonaktif' || $weightPercent <= 0) {
                continue;
            }

            $isMatch = false;
            $gap = 0.0;

            if ($key === 'gender') {
                $targetGender = $c['value'] ?? 'both';
                $isMatch = ($targetGender === 'both' || $application->gender === $targetGender);
                $gap = $isMatch ? 0.0 : -4.0;
            } 
            elseif ($key === 'age') {
                $minAge = (int) ($c['value']['min'] ?? 18);
                $maxAge = (int) ($c['value']['max'] ?? 65);
                $age = $application->age;
                $isMatch = ($age !== null && $age >= $minAge && $age <= $maxAge);
                if ($isMatch) {
                    $gap = 0.0;
                } else {
                    if ($age === null) {
                        $gap = -4.0;
                    } else if ($age < $minAge) {
                        $gap = -max(1, min(4, $minAge - $age));
                    } else {
                        $gap = -max(1, min(4, $age - $maxAge));
                    }
                }
            } 
            elseif ($key === 'education') {
                $minEducation = $c['value'] ?? 'SMA/SMK';
                $candRank = self::educationRank($application->education_level);
                $idealRank = self::educationRank($minEducation);
                $isMatch = ($candRank >= $idealRank);
                $gap = $candRank - $idealRank;
            } 
            elseif ($key === 'experience') {
                $minExp = (int) ($c['value'] ?? 0);
                $candExp = (int) $application->experience_years;
                $isMatch = ($candExp >= $minExp);
                $gap = $candExp - $minExp;
            } 
            elseif ($key === 'placement_ready') {
                $type = $c['value']['type'] ?? 'anywhere';
                if ($type === 'specific') {
                    $targetCity = $c['value']['city'] ?? $this->location_city;
                    $applicantCity = $application->user->profile?->city ?? '';
                    $isMatch = (!empty($targetCity) && strtolower(trim($applicantCity)) === strtolower(trim($targetCity)));
                } else {
                    $isMatch = (bool) $application->placement_ready;
                }
                $gap = $isMatch ? 0.0 : -4.0;
            } 
            elseif ($key === 'major') {
                $allowedMajors = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                $candMajor = trim(strtolower($application->major ?? ''));
                $isMatch = empty($allowedMajors) || in_array($candMajor, $allowedMajors);
                $gap = $isMatch ? 0.0 : -4.0;
            } 
            elseif ($key === 'placement_choices') {
                $allowedChoices = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                $candChoice = trim(strtolower($application->placement_choice ?? ''));
                $isMatch = empty($allowedChoices) || in_array($candChoice, $allowedChoices);
                $gap = $isMatch ? 0.0 : -4.0;
            } 
            else {
                // Documents & Custom checkbox check
                $isMatch = !empty($application->additional_documents[$key]);
                $gap = $isMatch ? 0.0 : -4.0;
            }

            // Core Factor requirement check
            if ($status === 'core') {
                if (!$isMatch) {
                    $isPriority = false;
                }
            }

            $weight = $gapToWeight($gap);
            $totalScore += ($weightPercent / 100) * $weight;
            $totalWeight += $weightPercent;
        }

        // Normalize matching score to 0 - 100
        // Nilai Akhir is a weighted sum (scale 1.0 - 5.0)
        $nilaiAkhir = $totalWeight > 0 ? ($totalScore / ($totalWeight / 100)) : 5.0;
        $matchingScore = (int) round((($nilaiAkhir - 1.0) / 4.0) * 100);
        $matchingScore = max(0, min(100, $matchingScore));

        return [
            'is_priority' => $isPriority,
            'matching_score' => $matchingScore,
        ];
    }

    public function meetsRequirements(JobApplication $application): bool
    {
        $config = $this->requirements_config;

        if (empty($config) || !isset($config['criteria'])) {
            // Fallback for old listings
            $age = $application->age;
            if ($this->core_gender && $this->core_gender !== 'both' && $application->gender !== $this->core_gender) {
                return false;
            }
            if ($age === null || $age < $this->core_min_age || $age > $this->core_max_age) {
                return false;
            }
            if (self::educationRank($application->education_level) < self::educationRank($this->core_min_education)) {
                return false;
            }
            if ($this->core_requires_agd && (!$application->has_agd || !$application->agd_certificate_path)) {
                return false;
            }
            if ($this->core_requires_sim_c && !$application->sim_c_path) {
                return false;
            }
            if ($this->core_requires_sim_b1 && !$application->sim_b1_path) {
                return false;
            }
            if ($application->experience_years < $this->second_min_experience) {
                return false;
            }
            return true;
        }

        // Dynamic checks
        foreach ($config['criteria'] as $c) {
            $key = $c['key'];
            $status = $c['status'] ?? 'nonaktif';
            if ($status !== 'core') {
                continue;
            }

            if ($key === 'gender') {
                $targetGender = $c['value'] ?? 'both';
                if ($targetGender !== 'both' && $application->gender !== $targetGender) {
                    return false;
                }
            } 
            elseif ($key === 'age') {
                $minAge = (int) ($c['value']['min'] ?? 18);
                $maxAge = (int) ($c['value']['max'] ?? 65);
                $age = $application->age;
                if ($age === null || $age < $minAge || $age > $maxAge) {
                    return false;
                }
            } 
            elseif ($key === 'education') {
                $minEducation = $c['value'] ?? 'SMA/SMK';
                $candRank = self::educationRank($application->education_level);
                $idealRank = self::educationRank($minEducation);
                if ($candRank < $idealRank) {
                    return false;
                }
            } 
            elseif ($key === 'experience') {
                $minExp = (int) ($c['value'] ?? 0);
                if ($application->experience_years < $minExp) {
                    return false;
                }
            } 
            elseif ($key === 'placement_ready') {
                $type = $c['value']['type'] ?? 'anywhere';
                if ($type === 'specific') {
                    $targetCity = $c['value']['city'] ?? $this->location_city;
                    $applicantCity = $application->user->profile?->city ?? '';
                    if (empty($targetCity) || strtolower(trim($applicantCity)) !== strtolower(trim($targetCity))) {
                        return false;
                    }
                } else {
                    if (!$application->placement_ready) {
                        return false;
                    }
                }
            } 
            elseif ($key === 'major') {
                $allowedMajors = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                $candMajor = trim(strtolower($application->major ?? ''));
                if (!empty($allowedMajors) && !in_array($candMajor, $allowedMajors)) {
                    return false;
                }
            } 
            elseif ($key === 'placement_choices') {
                $allowedChoices = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                $candChoice = trim(strtolower($application->placement_choice ?? ''));
                if (!empty($allowedChoices) && !in_array($candChoice, $allowedChoices)) {
                    return false;
                }
            } 
            else {
                // Documents & Custom checkbox check
                if (empty($application->additional_documents[$key])) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function educationRank(?string $education): int
    {
        $levels = [
            'sma/smk' => 1,
            'd3' => 2,
            's1' => 3,
            's2' => 4,
            's3' => 5,
        ];

        $key = $education ? strtolower($education) : '';
        return $levels[$key] ?? 0;
    }
}

