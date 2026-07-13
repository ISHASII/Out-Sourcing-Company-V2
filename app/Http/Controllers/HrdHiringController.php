<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;

class HrdHiringController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->role !== 'hrd') {
                return redirect()->route('pelamar.dashboard');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $postings = JobPosting::latest()->paginate(5);

        return view('hrd.hiring', [
            'postings' => $postings,
        ]);
    }

    public function create()
    {
        $allCriteria = \App\Models\Criterion::orderBy('sort_order')->get();
        return view('hrd.hiring.create', [
            'categories' => $this->categories(),
            'educationLevels' => $this->educationLevels(),
            'allCriteria' => $allCriteria,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePosting($request);
        
        $data['created_by'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['salary_hidden'] = $request->boolean('salary_hidden');

        $activeCriteriaData = json_decode($request->input('active_criteria_data', '[]'), true);
        $criteriaConfigs = [];

        foreach ($activeCriteriaData as $item) {
            $key = $item['key'];
            $status = $item['status'] ?? 'nonaktif';
            $weight = (int) ($item['weight'] ?? 0);

            // Dynamically register if it doesn't exist in Criterion table
            $c = \App\Models\Criterion::where('key', $key)->first();
            if (!$c) {
                $c = \App\Models\Criterion::create([
                    'category' => 'General',
                    'key' => $key,
                    'label' => $item['label'] ?? ucwords(str_replace('_', ' ', $key)),
                    'type' => $item['type'] ?? 'file',
                    'config' => [],
                    'default_status' => $status,
                    'default_weight' => $weight,
                    'sort_order' => \App\Models\Criterion::max('sort_order') + 1
                ]);
            }

            $value = null;
            if ($key === 'gender') {
                $value = $request->input('req_gender_value', 'both');
            } 
            elseif ($key === 'age') {
                $value = [
                    'min' => (int) $request->input('req_age_min', 18),
                    'max' => (int) $request->input('req_age_max', 65),
                ];
            } 
            elseif ($key === 'education') {
                $value = $request->input('req_education_value', 'SMA/SMK');
            } 
            elseif ($key === 'experience') {
                $value = (int) $request->input('req_experience_value', 0);
            } 
            elseif ($key === 'placement_ready') {
                $value = [
                    'type' => $request->input('req_placement_type', 'anywhere'),
                    'city' => $request->input('req_placement_type') === 'specific' ? $request->input('location_city') : null,
                ];
            } 
            elseif ($key === 'major') {
                $value = $request->input('req_major_value');
            } 
            elseif ($key === 'placement_choices') {
                $value = $request->input('req_placement_choices_value');
            }

            $criteriaConfigs[] = [
                'key' => $key,
                'label' => $c->label,
                'type' => $c->type,
                'status' => $status,
                'weight' => $weight,
                'value' => $value
            ];
        }

        $config = ['criteria' => $criteriaConfigs];
        $data['requirements_config'] = $config;

        // Populate fallback columns for backward compatibility
        $genderConf = collect($criteriaConfigs)->firstWhere('key', 'gender');
        $ageConf = collect($criteriaConfigs)->firstWhere('key', 'age');
        $eduConf = collect($criteriaConfigs)->firstWhere('key', 'education');
        $expConf = collect($criteriaConfigs)->firstWhere('key', 'experience');
        $placementConf = collect($criteriaConfigs)->firstWhere('key', 'placement_ready');
        
        $agdConf = collect($criteriaConfigs)->firstWhere('key', 'agd') 
            ?? collect($criteriaConfigs)->firstWhere('key', 'sertifikat_agd_ambulance');
        $simcConf = collect($criteriaConfigs)->firstWhere('key', 'sim_c') 
            ?? collect($criteriaConfigs)->firstWhere('key', 'lisensi_sim_c_motor');
        $simb1Conf = collect($criteriaConfigs)->firstWhere('key', 'sim_b1') 
            ?? collect($criteriaConfigs)->firstWhere('key', 'lisensi_sim_b1_mobil_berat');

        $data['core_gender'] = ($genderConf && $genderConf['status'] !== 'nonaktif') ? $genderConf['value'] : 'male';
        $data['core_min_age'] = ($ageConf && $ageConf['status'] !== 'nonaktif') ? $ageConf['value']['min'] : 18;
        $data['core_max_age'] = ($ageConf && $ageConf['status'] !== 'nonaktif') ? $ageConf['value']['max'] : 65;
        $data['core_min_education'] = ($eduConf && $eduConf['status'] !== 'nonaktif') ? $eduConf['value'] : 'SMA/SMK';
        
        $data['core_requires_agd'] = $agdConf && $agdConf['status'] === 'core';
        $data['core_requires_sim_c'] = $simcConf && $simcConf['status'] === 'core';
        $data['core_requires_sim_b1'] = $simb1Conf && $simb1Conf['status'] === 'core';
        
        $data['second_min_experience'] = ($expConf && $expConf['status'] !== 'nonaktif') ? $expConf['value'] : 0;
        $data['second_requires_placement_ready'] = $placementConf && $placementConf['status'] === 'core';

        if ($placementConf && isset($placementConf['value']['type']) && $placementConf['value']['type'] === 'anywhere') {
            $data['location_city'] = null;
        }

        if (($data['shift_type'] ?? null) === 'none') {
            $data['shift_type'] = null;
        }

        if ($data['salary_hidden']) {
            $data['salary_min'] = null;
            $data['salary_max'] = null;
        }

        JobPosting::create($data);


        return redirect()->route('hrd.hiring')->with('success', 'Lowongan berhasil dibuat.');
    }

    public function show(JobPosting $jobPosting)
    {
        $priorityApplications = $jobPosting->applications()
            ->with(['user.profile'])
            ->where('is_priority', true)
            ->orderBy('matching_score', 'desc')
            ->orderBy('birth_date', 'desc')
            ->orderBy('experience_years', 'desc')
            ->orderBy('placement_ready', 'desc')
            ->get();

        $nonPriorityApplications = $jobPosting->applications()
            ->with(['user.profile'])
            ->where('is_priority', false)
            ->orderBy('matching_score', 'desc')
            ->latest()
            ->get();

        return view('hrd.hiring.show', [
            'posting' => $jobPosting,
            'priorityApplications' => $priorityApplications,
            'nonPriorityApplications' => $nonPriorityApplications,
        ]);
    }

    public function edit(JobPosting $jobPosting)
    {
        $allCriteria = \App\Models\Criterion::orderBy('sort_order')->get();
        return view('hrd.hiring.edit', [
            'posting' => $jobPosting,
            'categories' => $this->categories(),
            'educationLevels' => $this->educationLevels(),
            'allCriteria' => $allCriteria,
        ]);
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        $data = $this->validatePosting($request);
        
        $data['is_active'] = $request->boolean('is_active', true);
        $data['salary_hidden'] = $request->boolean('salary_hidden');

        $activeCriteriaData = json_decode($request->input('active_criteria_data', '[]'), true);
        $criteriaConfigs = [];

        foreach ($activeCriteriaData as $item) {
            $key = $item['key'];
            $status = $item['status'] ?? 'nonaktif';
            $weight = (int) ($item['weight'] ?? 0);

            // Dynamically register if it doesn't exist in Criterion table
            $c = \App\Models\Criterion::where('key', $key)->first();
            if (!$c) {
                $c = \App\Models\Criterion::create([
                    'category' => 'General',
                    'key' => $key,
                    'label' => $item['label'] ?? ucwords(str_replace('_', ' ', $key)),
                    'type' => $item['type'] ?? 'file',
                    'config' => [],
                    'default_status' => $status,
                    'default_weight' => $weight,
                    'sort_order' => \App\Models\Criterion::max('sort_order') + 1
                ]);
            }

            $value = null;
            if ($key === 'gender') {
                $value = $request->input('req_gender_value', 'both');
            } 
            elseif ($key === 'age') {
                $value = [
                    'min' => (int) $request->input('req_age_min', 18),
                    'max' => (int) $request->input('req_age_max', 65),
                ];
            } 
            elseif ($key === 'education') {
                $value = $request->input('req_education_value', 'SMA/SMK');
            } 
            elseif ($key === 'experience') {
                $value = (int) $request->input('req_experience_value', 0);
            } 
            elseif ($key === 'placement_ready') {
                $value = [
                    'type' => $request->input('req_placement_type', 'anywhere'),
                    'city' => $request->input('req_placement_type') === 'specific' ? $request->input('location_city') : null,
                ];
            } 
            elseif ($key === 'major') {
                $value = $request->input('req_major_value');
            } 
            elseif ($key === 'placement_choices') {
                $value = $request->input('req_placement_choices_value');
            }

            $criteriaConfigs[] = [
                'key' => $key,
                'label' => $c->label,
                'type' => $c->type,
                'status' => $status,
                'weight' => $weight,
                'value' => $value
            ];
        }

        $config = ['criteria' => $criteriaConfigs];
        $data['requirements_config'] = $config;

        // Populate fallback columns for backward compatibility
        $genderConf = collect($criteriaConfigs)->firstWhere('key', 'gender');
        $ageConf = collect($criteriaConfigs)->firstWhere('key', 'age');
        $eduConf = collect($criteriaConfigs)->firstWhere('key', 'education');
        $expConf = collect($criteriaConfigs)->firstWhere('key', 'experience');
        $placementConf = collect($criteriaConfigs)->firstWhere('key', 'placement_ready');
        
        $agdConf = collect($criteriaConfigs)->firstWhere('key', 'agd') 
            ?? collect($criteriaConfigs)->firstWhere('key', 'sertifikat_agd_ambulance');
        $simcConf = collect($criteriaConfigs)->firstWhere('key', 'sim_c') 
            ?? collect($criteriaConfigs)->firstWhere('key', 'lisensi_sim_c_motor');
        $simb1Conf = collect($criteriaConfigs)->firstWhere('key', 'sim_b1') 
            ?? collect($criteriaConfigs)->firstWhere('key', 'lisensi_sim_b1_mobil_berat');

        $data['core_gender'] = ($genderConf && $genderConf['status'] !== 'nonaktif') ? $genderConf['value'] : 'male';
        $data['core_min_age'] = ($ageConf && $ageConf['status'] !== 'nonaktif') ? $ageConf['value']['min'] : 18;
        $data['core_max_age'] = ($ageConf && $ageConf['status'] !== 'nonaktif') ? $ageConf['value']['max'] : 65;
        $data['core_min_education'] = ($eduConf && $eduConf['status'] !== 'nonaktif') ? $eduConf['value'] : 'SMA/SMK';
        
        $data['core_requires_agd'] = $agdConf && $agdConf['status'] === 'core';
        $data['core_requires_sim_c'] = $simcConf && $simcConf['status'] === 'core';
        $data['core_requires_sim_b1'] = $simb1Conf && $simb1Conf['status'] === 'core';
        
        $data['second_min_experience'] = ($expConf && $expConf['status'] !== 'nonaktif') ? $expConf['value'] : 0;
        $data['second_requires_placement_ready'] = $placementConf && $placementConf['status'] === 'core';

        if ($placementConf && isset($placementConf['value']['type']) && $placementConf['value']['type'] === 'anywhere') {
            $data['location_city'] = null;
        }

        if (($data['shift_type'] ?? null) === 'none') {
            $data['shift_type'] = null;
        }

        if ($data['salary_hidden']) {
            $data['salary_min'] = null;
            $data['salary_max'] = null;
        }

        $jobPosting->update($data);

        return redirect()->route('hrd.hiring')->with('success', 'Lowongan berhasil diperbarui.');
    }


    public function destroy(JobPosting $jobPosting)
    {
        $jobPosting->delete();

        return redirect()->route('hrd.hiring')->with('success', 'Lowongan berhasil dihapus.');
    }

    /**
     * Toggle the is_active status of a job posting.
     * If active → deactivate (hidden from public).
     * If inactive → activate (visible to public).
     */
    public function toggleActive(JobPosting $jobPosting)
    {
        $jobPosting->update(['is_active' => !$jobPosting->is_active]);

        $status = $jobPosting->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Lowongan \"{$jobPosting->title}\" berhasil {$status}.");
    }

    private function validatePosting(Request $request): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:2000'],
            'location_city' => ['nullable', 'string', 'max:120'],
            'shift_type' => ['nullable', 'in:shift,non_shift,none'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0'],
            'salary_hidden' => ['nullable'],
            'is_active' => ['nullable'],
            'active_until' => ['nullable', 'date'],
        ];

        if ($request->input('category') !== 'Cleaning Service' && $request->input('req_placement_type') === 'specific') {
            $rules['location_city'] = ['required', 'string', 'max:120'];
        }

        $category = $request->input('category');
        $isDriverNurseOrCS = in_array($category, ['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner', 'Gardener']);
        $salaryHidden = $request->boolean('salary_hidden');

        if ($isDriverNurseOrCS) {
            $rules['shift_type'] = ['required', 'in:shift,non_shift,none'];
            if (!$salaryHidden) {
                $rules['salary_min'] = ['required', 'integer', 'min:0'];
                $rules['salary_max'] = ['required', 'integer', 'min:0', 'gte:salary_min'];
            }
        }

        return $request->validate($rules, [
            'location_city.required' => 'Lokasi Penempatan Kerja wajib dipilih jika Kesiapan Penempatan UCI dinonaktifkan.',
            'shift_type.required' => 'Jenis Shift wajib dipilih untuk posisi operasional (Driver Ambulance, Asisten Keperawatan, Cleaning Service, Runner, Gardener).',
            'salary_min.required' => 'Gaji Minimum wajib diisi, atau silakan centang "Sembunyikan Rentang Gaji".',
            'salary_max.required' => 'Gaji Maksimum wajib diisi, atau silakan centang "Sembunyikan Rentang Gaji".',
            'salary_max.gte' => 'Gaji Maksimum harus lebih besar atau sama dengan Gaji Minimum.',
        ]);
    }

    private function categories(): array
    {
        $dbCats = \App\Models\Criterion::distinct('category')->pluck('category')->toArray();
        return !empty($dbCats) ? $dbCats : [
            'Driver Ambulance',
            'Cleaning Service',
            'Asisten Keperawatan',
            'Runner',
            'Gardener',
            'Bell Boy',
        ];
    }

    private function educationLevels(): array
    {
        return ['SMA/SMK', 'D3', 'S1', 'S2', 'S3'];
    }

    /**
     * Display HRD Dashboard with dynamic stats and latest applicants.
     */
    public function dashboard()
    {
        $activePostingsCount = JobPosting::where('is_active', true)->count();
        $totalApplicantsCount = \App\Models\JobApplication::count();
        $priorityCount = \App\Models\JobApplication::where('is_priority', true)->count();
        $nonPriorityCount = \App\Models\JobApplication::where('is_priority', false)->count();

        $latestApplications = \App\Models\JobApplication::with(['user.profile', 'posting'])
            ->latest()
            ->take(5)
            ->get();

        // Calculate daily stats for the last 30 days
        $dates = [];
        for ($i = 29; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-$i days"));
        }

        $registrationsRaw = \App\Models\User::where('created_at', '>=', now()->subDays(30)->startOfDay())->get();
        $applicationsRaw = \App\Models\JobApplication::where('created_at', '>=', now()->subDays(30)->startOfDay())->get();

        $registrations = [];
        foreach ($registrationsRaw as $u) {
            $d = $u->created_at->format('Y-m-d');
            $registrations[$d] = ($registrations[$d] ?? 0) + 1;
        }

        $applications = [];
        foreach ($applicationsRaw as $app) {
            $d = $app->created_at->format('Y-m-d');
            $applications[$d] = ($applications[$d] ?? 0) + 1;
        }

        $chartData = [];
        foreach ($dates as $date) {
            $chartData[] = [
                'raw_date' => $date,
                'label' => date('d M', strtotime($date)),
                'registrations' => $registrations[$date] ?? 0,
                'applications' => $applications[$date] ?? 0,
            ];
        }

        return view('hrd.dashboard', [
            'activePostingsCount' => $activePostingsCount,
            'totalApplicantsCount' => $totalApplicantsCount,
            'priorityCount' => $priorityCount,
            'nonPriorityCount' => $nonPriorityCount,
            'latestApplications' => $latestApplications,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Display all registered applicants in the database.
     */
    public function pelamarAktif(Request $request)
    {
        $query = \App\Models\User::where('role', 'pelamar')
            ->with(['profile', 'applications.posting']);

        // Search filter
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($qp) use ($search) {
                      $qp->where('city', 'like', "%{$search}%")
                         ->orWhere('province', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('education_level', 'like', "%{$search}%");
                  });
            });
        }

        // Education filter
        if ($request->has('education') && $request->get('education') !== 'all') {
            $education = $request->get('education');
            $query->whereHas('profile', function($q) use ($education) {
                $q->where('education_level', $education);
            });
        }

        $pelamarList = $query->latest()->paginate(10)->withQueryString();

        // Get unique educations for filter dropdown
        $educations = \App\Models\UserProfile::whereNotNull('education_level')
            ->select('education_level')
            ->distinct()
            ->pluck('education_level')
            ->sort();

        return view('hrd.pelamar-aktif', [
            'pelamarList' => $pelamarList,
            'educations' => $educations,
        ]);
    }

    public function acceptApplication(\App\Models\JobApplication $jobApplication)
    {
        $jobApplication->update(['status' => 'accepted']);

        \App\Models\Notification::create([
            'user_id' => $jobApplication->user_id,
            'title' => 'Lamaran Diterima 🎉',
            'message' => 'Selamat! Lamaran Anda untuk posisi "' . $jobApplication->posting->title . '" telah Diterima. Silakan tunggu kelanjutan dari tim HRD.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pelamar "' . $jobApplication->user->name . '" berhasil DITERIMA.');
    }

    public function rejectApplication(\App\Models\JobApplication $jobApplication)
    {
        $jobApplication->update(['status' => 'rejected']);

        \App\Models\Notification::create([
            'user_id' => $jobApplication->user_id,
            'title' => 'Lamaran Belum Lolos ✉️',
            'message' => 'Mohon maaf, lamaran Anda untuk posisi "' . $jobApplication->posting->title . '" belum dapat kami terima saat ini.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pelamar "' . $jobApplication->user->name . '" berhasil DITOLAK.');
    }

    public function downloadPdf(\App\Models\JobApplication $jobApplication)
    {
        $posting = $jobApplication->posting;
        $profile = $jobApplication->user->profile;
        $config = $posting->requirements_config;

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

        $calculationDetails = [];
        $coreWeights = [];
        $secondaryWeights = [];

        // Check if new dynamic criteria configuration exists
        if (!empty($config) && isset($config['criteria'])) {
            $totalScore = 0.0;
            $totalWeight = 0.0;

            foreach ($config['criteria'] as $c) {
                $key = $c['key'];
                $status = $c['status'] ?? 'nonaktif';
                $weightPercent = (int) ($c['weight'] ?? 0);

                if ($status === 'nonaktif' || $weightPercent <= 0) {
                    continue;
                }

                $isMatch = false;
                $gap = 0.0;
                
                // Get human readable label from Criterion model if possible
                $critModel = \App\Models\Criterion::where('category', $posting->category)->where('key', $key)->first();
                $criteriaLabel = $critModel ? $critModel->label : ucwords(str_replace('_', ' ', $key));

                $ideal = 5;
                $cand = 5;

                if ($key === 'gender') {
                    $targetGender = $c['value'] ?? 'both';
                    $isMatch = ($targetGender === 'both' || $jobApplication->gender === $targetGender);
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                } 
                elseif ($key === 'age') {
                    $minAge = (int) ($c['value']['min'] ?? 18);
                    $maxAge = (int) ($c['value']['max'] ?? 65);
                    $age = $jobApplication->age;
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
                } 
                elseif ($key === 'education') {
                    $minEducation = $c['value'] ?? 'SMA/SMK';
                    $candRank = JobPosting::educationRank($jobApplication->education_level);
                    $idealRank = JobPosting::educationRank($minEducation);
                    
                    $ideal = $idealRank;
                    $cand = $candRank;
                    $gap = $candRank - $idealRank;
                } 
                elseif ($key === 'experience') {
                    $minExp = (int) ($c['value'] ?? 0);
                    $candExp = (int) $jobApplication->experience_years;
                    
                    $ideal = $minExp;
                    $cand = $candExp;
                    $gap = $candExp - $minExp;
                } 
                elseif ($key === 'placement_ready') {
                    $type = $c['value']['type'] ?? 'anywhere';
                    if ($type === 'specific') {
                        $targetCity = $c['value']['city'] ?? $posting->location_city;
                        $applicantCity = $jobApplication->user->profile?->city ?? '';
                        $isMatch = (!empty($targetCity) && strtolower(trim($applicantCity)) === strtolower(trim($targetCity)));
                    } else {
                        $isMatch = (bool) $jobApplication->placement_ready;
                    }
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                } 
                elseif ($key === 'major') {
                    $allowedMajors = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                    $candMajor = trim(strtolower($jobApplication->major ?? ''));
                    $isMatch = empty($allowedMajors) || in_array($candMajor, $allowedMajors);
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                } 
                elseif ($key === 'placement_choices') {
                    $allowedChoices = !empty($c['value']) ? array_map('trim', explode(',', strtolower($c['value']))) : [];
                    $candChoice = trim(strtolower($jobApplication->placement_choice ?? ''));
                    $isMatch = empty($allowedChoices) || in_array($candChoice, $allowedChoices);
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                } 
                else {
                    $isMatch = !empty($jobApplication->additional_documents[$key]);
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                }

                $weight = $gapToWeight($gap);

                $calculationDetails[] = [
                    'criteria' => $criteriaLabel,
                    'criteria_weight' => $weightPercent,
                    'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                    'target' => $ideal,
                    'candidate' => $cand,
                    'gap' => $gap,
                    'weight' => $weight
                ];

                if ($status === 'core') {
                    $coreWeights[] = ($weightPercent / 100) * $weight;
                } else {
                    $secondaryWeights[] = ($weightPercent / 100) * $weight;
                }
                
                $totalScore += ($weightPercent / 100) * $weight;
                $totalWeight += $weightPercent;
            }

            $ncf = count($coreWeights) > 0 ? array_sum($coreWeights) : 5.0;
            $nsf = count($secondaryWeights) > 0 ? array_sum($secondaryWeights) : 5.0;
            
            $nilaiAkhir = $totalWeight > 0 ? ($totalScore / ($totalWeight / 100)) : 5.0;
            $calculatedScore = (int) round((($nilaiAkhir - 1.0) / 4.0) * 100);
            $calculatedScore = max(0, min(100, $calculatedScore));
        } else {
            // Old fallback check
            if (!empty($config)) {
                // 1. Gender
                if (isset($config['gender']) && $config['gender']['status'] !== 'nonaktif') {
                    $status = $config['gender']['status'];
                    $targetGender = $config['gender']['value'] ?? 'male';
                    $isMatch = ($targetGender === 'both' || $jobApplication->gender === $targetGender);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Jenis Kelamin',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 2. Usia
                if (isset($config['age']) && $config['age']['status'] !== 'nonaktif') {
                    $status = $config['age']['status'];
                    $minAge = (int) ($config['age']['min'] ?? 18);
                    $maxAge = (int) ($config['age']['max'] ?? 65);
                    $age = $jobApplication->age;
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

                    $calculationDetails[] = [
                        'criteria' => 'Rentang Usia (' . $minAge . '-' . $maxAge . ' tahun)',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 3. Pendidikan
                if (isset($config['education']) && $config['education']['status'] !== 'nonaktif') {
                    $status = $config['education']['status'];
                    $minEducation = $config['education']['value'] ?? 'SMA/SMK';
                    $candRank = \App\Models\JobPosting::educationRank($jobApplication->education_level);
                    $idealRank = \App\Models\JobPosting::educationRank($minEducation);
                    $gap = $candRank - $idealRank;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Pendidikan Minimal (' . $minEducation . ')',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $idealRank,
                        'candidate' => $candRank,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 4. AGD
                if (isset($config['agd']) && $config['agd']['status'] !== 'nonaktif') {
                    $status = $config['agd']['status'];
                    $isMatch = ($jobApplication->has_agd && $jobApplication->agd_certificate_path);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Sertifikat AGD (Ambulance)',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 5. SIM C
                if (isset($config['sim_c']) && $config['sim_c']['status'] !== 'nonaktif') {
                    $status = $config['sim_c']['status'];
                    $isMatch = (bool) $jobApplication->sim_c_path;
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Lisensi SIM C (Motor)',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 6. SIM B1
                if (isset($config['sim_b1']) && $config['sim_b1']['status'] !== 'nonaktif') {
                    $status = $config['sim_b1']['status'];
                    $isMatch = (bool) $jobApplication->sim_b1_path;
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Lisensi SIM B1 (Mobil Berat)',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 7. Pengalaman
                if (isset($config['experience']) && $config['experience']['status'] !== 'nonaktif') {
                    $status = $config['experience']['status'];
                    $minExp = (int) ($config['experience']['value'] ?? 0);
                    $candExp = (int) $jobApplication->experience_years;
                    $gap = $candExp - $minExp;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Pengalaman Kerja Minimal (' . $minExp . ' tahun)',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $minExp,
                        'candidate' => $candExp,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 8. Placement Ready
                if (isset($config['placement_ready']) && $config['placement_ready']['status'] !== 'nonaktif') {
                    $status = $config['placement_ready']['status'];
                    $isMatch = (bool) $jobApplication->placement_ready;
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Kesiapan Penempatan',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 9. Jurusan (Major)
                if (isset($config['major']) && $config['major']['status'] !== 'nonaktif') {
                    $status = $config['major']['status'];
                    $allowedMajors = !empty($config['major']['value']) ? array_map('trim', explode(',', strtolower($config['major']['value']))) : [];
                    $candMajor = trim(strtolower($jobApplication->major ?? ''));
                    $isMatch = empty($allowedMajors) || in_array($candMajor, $allowedMajors);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Jurusan Pendidikan (' . ($config['major']['value'] ?? '') . ')',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 10. Placement Choices
                if (isset($config['placement_choices']) && $config['placement_choices']['status'] !== 'nonaktif') {
                    $status = $config['placement_choices']['status'];
                    $allowedChoices = !empty($config['placement_choices']['value']) ? array_map('trim', explode(',', strtolower($config['placement_choices']['value']))) : [];
                    $candChoice = trim(strtolower($jobApplication->placement_choice ?? ''));
                    $isMatch = empty($allowedChoices) || in_array($candChoice, $allowedChoices);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Pilihan Kota Penempatan (' . ($config['placement_choices']['value'] ?? '') . ')',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 11. Custom / General Criteria (Dynamic)
                $predefinedKeys = [
                    'gender', 'age', 'education', 'major', 'experience', 
                    'agd', 'sertifikat_agd_ambulance', 'sertifikat_agd',
                    'sim_c', 'lisensi_sim_c_motor', 'sim_c_aktif',
                    'sim_b1', 'lisensi_sim_b1_mobil_berat',
                    'placement_ready', 'placement_choices',
                    'medical_support', 'medical_terms', 
                    'gardener_tech_understanding', 'gardener_nursery_skill', 'gardener_tools_skill'
                ];
                
                $customDocs = [];
                if (isset($config['criteria']) && is_array($config['criteria'])) {
                    foreach ($config['criteria'] as $c) {
                        if (($c['status'] ?? 'nonaktif') !== 'nonaktif' && !in_array($c['key'], $predefinedKeys)) {
                            $customDocs[] = $c;
                        }
                    }
                }

                foreach ($customDocs as $doc) {
                    $key = $doc['key'];
                    $status = $doc['status'];
                    $label = $doc['label'] ?? ucwords(str_replace('_', ' ', $key));
                    $isMatch = !empty($jobApplication->additional_documents[$key]);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => $label,
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 12. Runner Medical Support
                if (isset($config['medical_support']) && $config['medical_support']['status'] !== 'nonaktif') {
                    $status = $config['medical_support']['status'];
                    $isMatch = !empty($jobApplication->additional_documents['medical_support']);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Menguasai Kebutuhan Penunjang Medis',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 13. Runner Medical Terms
                if (isset($config['medical_terms']) && $config['medical_terms']['status'] !== 'nonaktif') {
                    $status = $config['medical_terms']['status'];
                    $isMatch = !empty($jobApplication->additional_documents['medical_terms']);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Mengetahui Istilah-Istilah Medis',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 14. Gardener Tech Understanding
                if (isset($config['gardener_tech_understanding']) && $config['gardener_tech_understanding']['status'] !== 'nonaktif') {
                    $status = $config['gardener_tech_understanding']['status'];
                    $isMatch = !empty($jobApplication->additional_documents['gardener_tech_understanding']);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Memahami Teknis Pertumbuhan Tanaman',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 15. Gardener Nursery Skill
                if (isset($config['gardener_nursery_skill']) && $config['gardener_nursery_skill']['status'] !== 'nonaktif') {
                    $status = $config['gardener_nursery_skill']['status'];
                    $isMatch = !empty($jobApplication->additional_documents['gardener_nursery_skill']);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Mampu Mengelola Pembibitan Tanaman',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }

                // 16. Gardener Tools Skill
                if (isset($config['gardener_tools_skill']) && $config['gardener_tools_skill']['status'] !== 'nonaktif') {
                    $status = $config['gardener_tools_skill']['status'];
                    $isMatch = !empty($jobApplication->additional_documents['gardener_tools_skill']);
                    $ideal = 5;
                    $cand = $isMatch ? 5 : 1;
                    $gap = $cand - $ideal;
                    $weight = $gapToWeight($gap);

                    $calculationDetails[] = [
                        'criteria' => 'Menguasai Skill Penggunaan Alat-Alat Teknis',
                        'factor_type' => $status === 'core' ? 'Core Factor' : 'Secondary Factor',
                        'target' => $ideal,
                        'candidate' => $cand,
                        'gap' => $gap,
                        'weight' => $weight
                    ];
                    if ($status === 'core') $coreWeights[] = $weight; else $secondaryWeights[] = $weight;
                }
            }

            $ncf = count($coreWeights) > 0 ? array_sum($coreWeights) / count($coreWeights) : 5.0;
            $nsf = count($secondaryWeights) > 0 ? array_sum($secondaryWeights) / count($secondaryWeights) : 5.0;
            $nilaiAkhir = (0.6 * $ncf) + (0.4 * $nsf);
            $calculatedScore = (int) round((($nilaiAkhir - 1.0) / 4.0) * 100);
            $calculatedScore = max(0, min(100, $calculatedScore));
        }

        return view('hrd.hiring.application_pdf', [
            'application' => $jobApplication,
            'posting' => $posting,
            'profile' => $profile,
            'calculationDetails' => $calculationDetails,
            'ncf' => isset($ncf) ? $ncf : 5.0,
            'nsf' => isset($nsf) ? $nsf : 5.0,
            'nilaiAkhir' => $nilaiAkhir,
            'calculatedScore' => $calculatedScore,
        ]);
    }
}
