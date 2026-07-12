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

    public function calculateSpkScore(JobApplication $application): array
    {
        $config = $this->requirements_config;

        if (empty($config)) {
            $isPriority = $this->meetsRequirements($application);
            return [
                'is_priority' => $isPriority,
                'matching_score' => $isPriority ? 100 : 50
            ];
        }

        $isPriority = true;
        $coreWeights = [];
        $secondaryWeights = [];

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
            if (isset($map[$key])) {
                return $map[$key];
            }
            return $gap > 0 ? 1.5 : 1.0;
        };

        // 1. GENDER
        if (isset($config['gender']) && $config['gender']['status'] !== 'nonaktif') {
            $status = $config['gender']['status'];
            $targetGender = $config['gender']['value'] ?? 'male';
            
            $isMatch = ($targetGender === 'both' || $application->gender === $targetGender);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 2. USIA
        if (isset($config['age']) && $config['age']['status'] !== 'nonaktif') {
            $status = $config['age']['status'];
            $minAge = (int) ($config['age']['min'] ?? 18);
            $maxAge = (int) ($config['age']['max'] ?? 65);
            $age = $application->age;
            
            $isMatch = ($age !== null && $age >= $minAge && $age <= $maxAge);
            
            $ideal = 5;
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
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 3. PENDIDIKAN
        if (isset($config['education']) && $config['education']['status'] !== 'nonaktif') {
            $status = $config['education']['status'];
            $minEducation = $config['education']['value'] ?? 'SMA/SMK';
            
            $candRank = self::educationRank($application->education_level);
            $idealRank = self::educationRank($minEducation);
            
            $gap = $candRank - $idealRank;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if ($candRank < $idealRank) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 4. AGD CERTIFICATE
        if (isset($config['agd']) && $config['agd']['status'] !== 'nonaktif') {
            $status = $config['agd']['status'];
            $isMatch = ($application->has_agd && $application->agd_certificate_path);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 5. SIM C
        if (isset($config['sim_c']) && $config['sim_c']['status'] !== 'nonaktif') {
            $status = $config['sim_c']['status'];
            $isMatch = (bool) $application->sim_c_path;
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 6. SIM B1
        if (isset($config['sim_b1']) && $config['sim_b1']['status'] !== 'nonaktif') {
            $status = $config['sim_b1']['status'];
            $isMatch = (bool) $application->sim_b1_path;
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 7. PENGALAMAN
        if (isset($config['experience']) && $config['experience']['status'] !== 'nonaktif') {
            $status = $config['experience']['status'];
            $minExp = (int) ($config['experience']['value'] ?? 0);
            $candExp = (int) $application->experience_years;
            
            $gap = $candExp - $minExp;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if ($candExp < $minExp) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 8. PLACEMENT READY
        if (isset($config['placement_ready']) && $config['placement_ready']['status'] !== 'nonaktif') {
            $status = $config['placement_ready']['status'];
            $type = $config['placement_ready']['type'] ?? 'anywhere';
            
            if ($type === 'specific') {
                $targetCity = $config['placement_ready']['value'] ?? $this->location_city;
                $applicantCity = $application->user->profile?->city ?? '';
                $isMatch = (!empty($targetCity) && strtolower(trim($applicantCity)) === strtolower(trim($targetCity)));
            } else {
                $isMatch = (bool) $application->placement_ready;
            }
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 9. JURUSAN (MAJOR)
        if (isset($config['major']) && $config['major']['status'] !== 'nonaktif') {
            $status = $config['major']['status'];
            $allowedMajors = !empty($config['major']['value']) ? array_map('trim', explode(',', strtolower($config['major']['value']))) : [];
            $candMajor = trim(strtolower($application->major ?? ''));
            
            $isMatch = empty($allowedMajors) || in_array($candMajor, $allowedMajors);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 10. PLACEMENT CHOICES
        if (isset($config['placement_choices']) && $config['placement_choices']['status'] !== 'nonaktif') {
            $status = $config['placement_choices']['status'];
            $allowedChoices = !empty($config['placement_choices']['value']) ? array_map('trim', explode(',', strtolower($config['placement_choices']['value']))) : [];
            $candChoice = trim(strtolower($application->placement_choice ?? ''));
            
            $isMatch = empty($allowedChoices) || in_array($candChoice, $allowedChoices);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 11. CUSTOM DOCUMENTS
        if (isset($config['custom_documents']) && is_array($config['custom_documents'])) {
            foreach ($config['custom_documents'] as $doc) {
                $key = $doc['key'];
                $status = $doc['status'];
                $isMatch = !empty($application->additional_documents[$key]);
                
                $ideal = 5;
                $cand = $isMatch ? 5 : 1;
                $gap = $cand - $ideal;
                $weight = $gapToWeight($gap);

                if ($status === 'core') {
                    $coreWeights[] = $weight;
                    if (!$isMatch) $isPriority = false;
                } else {
                    $secondaryWeights[] = $weight;
                }
            }
        }

        // 12. RUNNER MEDICAL SUPPORT CHECKBOX
        if (isset($config['medical_support']) && $config['medical_support']['status'] !== 'nonaktif') {
            $status = $config['medical_support']['status'];
            $isMatch = !empty($application->additional_documents['medical_support']);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 13. RUNNER MEDICAL TERMS CHECKBOX
        if (isset($config['medical_terms']) && $config['medical_terms']['status'] !== 'nonaktif') {
            $status = $config['medical_terms']['status'];
            $isMatch = !empty($application->additional_documents['medical_terms']);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 14. GARDENER TECH UNDERSTANDING CHECKBOX
        if (isset($config['gardener_tech_understanding']) && $config['gardener_tech_understanding']['status'] !== 'nonaktif') {
            $status = $config['gardener_tech_understanding']['status'];
            $isMatch = !empty($application->additional_documents['gardener_tech_understanding']);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 15. GARDENER NURSERY SKILL CHECKBOX
        if (isset($config['gardener_nursery_skill']) && $config['gardener_nursery_skill']['status'] !== 'nonaktif') {
            $status = $config['gardener_nursery_skill']['status'];
            $isMatch = !empty($application->additional_documents['gardener_nursery_skill']);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // 16. GARDENER TOOLS SKILL CHECKBOX
        if (isset($config['gardener_tools_skill']) && $config['gardener_tools_skill']['status'] !== 'nonaktif') {
            $status = $config['gardener_tools_skill']['status'];
            $isMatch = !empty($application->additional_documents['gardener_tools_skill']);
            
            $ideal = 5;
            $cand = $isMatch ? 5 : 1;
            $gap = $cand - $ideal;
            $weight = $gapToWeight($gap);

            if ($status === 'core') {
                $coreWeights[] = $weight;
                if (!$isMatch) $isPriority = false;
            } else {
                $secondaryWeights[] = $weight;
            }
        }

        // Langkah 4: Hitung NCF (Core Factor) & NSF (Secondary Factor)
        $ncf = count($coreWeights) > 0 ? array_sum($coreWeights) / count($coreWeights) : 5.0;
        $nsf = count($secondaryWeights) > 0 ? array_sum($secondaryWeights) / count($secondaryWeights) : 5.0;

        // Langkah 5: Hitung Nilai Akhir (60% NCF + 40% NSF)
        $nilaiAkhir = (0.6 * $ncf) + (0.4 * $nsf);

        // Konversi Nilai Akhir (Skala 1.0 - 5.0) ke Persentase (0 - 100%)
        $matchingScore = (int) round((($nilaiAkhir - 1.0) / 4.0) * 100);
        if ($matchingScore < 0) $matchingScore = 0;
        if ($matchingScore > 100) $matchingScore = 100;

        return [
            'is_priority' => $isPriority,
            'matching_score' => $matchingScore,
        ];
    }

    public function meetsRequirements(JobApplication $application): bool
    {
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

        if ($this->core_requires_agd && !$application->has_agd) {
            return false;
        }

        if ($this->core_requires_agd && !$application->agd_certificate_path) {
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

        $config = $this->requirements_config;
        $hasSpecificPlacement = !empty($config) && ($config['placement_ready']['type'] ?? 'anywhere') === 'specific';

        if ($this->second_requires_placement_ready && !$hasSpecificPlacement && !$application->placement_ready) {
            return false;
        }

        // Custom Dynamic Checks
        if (!empty($config)) {
            if (isset($config['placement_ready']) && $config['placement_ready']['status'] === 'core') {
                $type = $config['placement_ready']['type'] ?? 'anywhere';
                if ($type === 'specific') {
                    $targetCity = $config['placement_ready']['value'] ?? $this->location_city;
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
            if (isset($config['major']) && $config['major']['status'] === 'core') {
                $allowedMajors = !empty($config['major']['value']) ? array_map('trim', explode(',', strtolower($config['major']['value']))) : [];
                $candMajor = trim(strtolower($application->major ?? ''));
                if (!empty($allowedMajors) && !in_array($candMajor, $allowedMajors)) {
                    return false;
                }
            }
            if (isset($config['placement_choices']) && $config['placement_choices']['status'] === 'core') {
                $allowedChoices = !empty($config['placement_choices']['value']) ? array_map('trim', explode(',', strtolower($config['placement_choices']['value']))) : [];
                $candChoice = trim(strtolower($application->placement_choice ?? ''));
                if (!empty($allowedChoices) && !in_array($candChoice, $allowedChoices)) {
                    return false;
                }
            }
            if (isset($config['custom_documents']) && is_array($config['custom_documents'])) {
                foreach ($config['custom_documents'] as $doc) {
                    if ($doc['status'] === 'core' && empty($application->additional_documents[$doc['key']])) {
                        return false;
                    }
                }
            }
            if (isset($config['medical_support']) && $config['medical_support']['status'] === 'core') {
                if (empty($application->additional_documents['medical_support'])) {
                    return false;
                }
            }
            if (isset($config['medical_terms']) && $config['medical_terms']['status'] === 'core') {
                if (empty($application->additional_documents['medical_terms'])) {
                    return false;
                }
            }
            if (isset($config['gardener_tech_understanding']) && $config['gardener_tech_understanding']['status'] === 'core') {
                if (empty($application->additional_documents['gardener_tech_understanding'])) {
                    return false;
                }
            }
            if (isset($config['gardener_nursery_skill']) && $config['gardener_nursery_skill']['status'] === 'core') {
                if (empty($application->additional_documents['gardener_nursery_skill'])) {
                    return false;
                }
            }
            if (isset($config['gardener_tools_skill']) && $config['gardener_tools_skill']['status'] === 'core') {
                if (empty($application->additional_documents['gardener_tools_skill'])) {
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
