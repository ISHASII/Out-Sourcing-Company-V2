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
        'spk_status',
        'spk_execution_logs',
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
        'spk_execution_logs' => 'array',
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
        $detailed = $this->calculateSpkScoreDetailed($application);
        return [
            'is_priority' => $detailed['is_priority'],
            'matching_score' => $detailed['matching_score'],
        ];
    }

    public function calculateSpkScoreDetailed(JobApplication $application): array
    {
        $config = $this->requirements_config;

        if (empty($config) || !isset($config['criteria'])) {
            $isPriority = $this->meetsRequirements($application);
            return [
                'is_priority' => $isPriority,
                'matching_score' => $isPriority ? 100 : 50,
                'criteria_details' => [],
                'ncf' => 0,
                'nsf' => 0,
                'cf_weight_percent' => 0,
                'sf_weight_percent' => 0,
                'nilai_akhir' => $isPriority ? 5.0 : 2.5,
                'total_score_raw' => 0,
            ];
        }

        $isPriority = true;
        $totalScore = 0.0;
        $totalWeight = 0.0;
        $criteriaDetails = [];

        // Core & Secondary aggregators for NCF/NSF display
        $coreBobotSum = 0.0;
        $coreCount = 0;
        $secondaryBobotSum = 0.0;
        $secondaryCount = 0;
        $cfWeightPercent = 0;
        $sfWeightPercent = 0;

        // Langkah 3: Konversi Gap ke Bobot Nilai
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
            $label = $c['label'] ?? ucwords(str_replace('_', ' ', $key));
            $status = $c['status'] ?? 'nonaktif';
            $weightPercent = (int) ($c['weight'] ?? 0);
            
            if ($status === 'nonaktif' || $weightPercent <= 0) {
                continue;
            }

            $isMatch = false;
            $gap = 0.0;
            $standardDisplay = '-';
            $applicantDisplay = '-';
            $ideal = 5;
            $cand = 5;

            if ($key === 'gender') {
                $targetGender = $c['value'] ?? 'both';
                $isMatch = ($targetGender === 'both' || $application->gender === $targetGender);
                $cand = $isMatch ? 5 : 1;
                $gap = $cand - $ideal;
                $genderLabels = ['male' => 'Pria', 'female' => 'Wanita', 'both' => 'Semua'];
                $standardDisplay = $genderLabels[$targetGender] ?? $targetGender;
                $applicantDisplay = $application->gender === 'male' ? 'Pria' : 'Wanita';
            } 
            elseif ($key === 'age') {
                $minAge = (int) ($c['value']['min'] ?? 18);
                $maxAge = (int) ($c['value']['max'] ?? 65);
                $age = $application->age;
                $isMatch = ($age !== null && $age >= $minAge && $age <= $maxAge);
                if ($isMatch) {
                    $cand = 5;
                } else {
                    if ($age === null) {
                        $cand = 1;
                    } else if ($age < $minAge) {
                        $cand = max(1, 5 - ($minAge - $age));
                    } else {
                        $cand = max(1, 5 - ($age - $maxAge));
                    }
                }
                $gap = $cand - $ideal;
                $standardDisplay = $minAge . '–' . $maxAge . ' thn';
                $applicantDisplay = $age !== null ? $age . ' thn' : 'N/A';
            } 
            elseif ($key === 'education') {
                $minEducation = $c['value'] ?? 'SMA/SMK';
                $candRank = self::educationRank($application->education_level);
                $idealRank = self::educationRank($minEducation);
                $isMatch = ($candRank >= $idealRank);
                $ideal = $idealRank;
                $cand = $candRank;
                $gap = $cand - $ideal;
                $standardDisplay = $minEducation;
                $applicantDisplay = $application->education_level ?? 'N/A';
            } 
            elseif ($key === 'experience') {
                $minExp = (int) ($c['value'] ?? 0);
                $candExp = (int) $application->experience_years;
                $isMatch = ($candExp >= $minExp);
                $ideal = $minExp;
                $cand = $candExp;
                $gap = $cand - $ideal;
                $standardDisplay = 'Min. ' . $minExp . ' thn';
                $applicantDisplay = $candExp . ' thn';
            } 
            elseif ($key === 'placement_ready') {
                $type = $c['value']['type'] ?? 'anywhere';
                if ($type === 'specific') {
                    $targetCity = $c['value']['city'] ?? $this->location_city;
                    $applicantCity = $application->user->profile?->city ?? '';
                    $isMatch = (!empty($targetCity) && strtolower(trim($applicantCity)) === strtolower(trim($targetCity)));
                    $standardDisplay = $targetCity ?? 'Kota tertentu';
                    $applicantDisplay = $applicantCity ?: 'N/A';
                } else {
                    $isMatch = (bool) $application->placement_ready;
                    $standardDisplay = 'Siap dimana saja';
                    $applicantDisplay = $application->placement_ready ? 'Siap' : 'Tidak';
                }
                $cand = $isMatch ? 5 : 1;
                $gap = $cand - $ideal;
            } 
            elseif ($key === 'major') {
                $allowedMajors = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                $candMajor = trim(strtolower($application->major ?? ''));
                $isMatch = empty($allowedMajors) || in_array($candMajor, $allowedMajors);
                $cand = $isMatch ? 5 : 1;
                $gap = $cand - $ideal;
                $standardDisplay = $c['value'] ?? 'Semua';
                $applicantDisplay = $application->major ?? 'N/A';
            } 
            elseif ($key === 'placement_choices') {
                $allowedChoices = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                $candChoice = trim(strtolower($application->placement_choice ?? ''));
                $isMatch = empty($allowedChoices) || in_array($candChoice, $allowedChoices);
                $cand = $isMatch ? 5 : 1;
                $gap = $cand - $ideal;
                $standardDisplay = $c['value'] ?? 'Semua';
                $applicantDisplay = $application->placement_choice ?? 'N/A';
            } 
            else {
                // Documents & Custom checkbox check
                $isMatch = !empty($application->additional_documents[$key]);
                $cand = $isMatch ? 5 : 1;
                $gap = $cand - $ideal;
                $standardDisplay = 'Wajib upload';
                $applicantDisplay = $isMatch ? 'Ada' : 'Tidak ada';
            }

            // Core Factor requirement check
            if ($status === 'core') {
                if (!$isMatch) {
                    $isPriority = false;
                }
            }

            $bobotNilai = $gapToWeight($gap);
            $totalScore += ($weightPercent / 100) * $bobotNilai;
            $totalWeight += $weightPercent;

            // Aggregate for NCF / NSF
            if ($status === 'core') {
                $coreBobotSum += ($weightPercent / 100) * $bobotNilai;
                $coreCount++;
                $cfWeightPercent += $weightPercent;
            } else {
                $secondaryBobotSum += ($weightPercent / 100) * $bobotNilai;
                $secondaryCount++;
                $sfWeightPercent += $weightPercent;
            }

            $criteriaDetails[] = [
                'key' => $key,
                'label' => $label,
                'status' => $status,
                'weight' => $weightPercent,
                'standard_display' => $standardDisplay,
                'applicant_display' => $applicantDisplay,
                'is_match' => $isMatch,
                'target' => $ideal,
                'applicant_value' => $cand,
                'gap' => (int) round($gap),
                'bobot_nilai' => $bobotNilai,
            ];
        }

        // NCF = rata-rata berbobot bobot nilai kriteria Core
        $ncf = $cfWeightPercent > 0 ? round($coreBobotSum / ($cfWeightPercent / 100), 2) : 0;
        // NSF = rata-rata berbobot bobot nilai kriteria Secondary
        $nsf = $sfWeightPercent > 0 ? round($secondaryBobotSum / ($sfWeightPercent / 100), 2) : 0;

        // Normalize matching score to 0 - 100
        // Nilai Akhir is a weighted sum (scale 1.0 - 5.0)
        $nilaiAkhir = $totalWeight > 0 ? ($totalScore / ($totalWeight / 100)) : 5.0;
        $matchingScore = (int) round((($nilaiAkhir - 1.0) / 4.0) * 100);
        $matchingScore = max(0, min(100, $matchingScore));

        return [
            'is_priority' => $isPriority,
            'matching_score' => $matchingScore,
            'criteria_details' => $criteriaDetails,
            'ncf' => $ncf,
            'nsf' => $nsf,
            'cf_weight_percent' => $cfWeightPercent,
            'sf_weight_percent' => $sfWeightPercent,
            'nilai_akhir' => round($nilaiAkhir, 4),
            'total_score_raw' => round($totalScore, 4),
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

