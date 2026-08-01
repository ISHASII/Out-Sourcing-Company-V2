<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SpkTestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. HRD Account setup just in case
        $hrd = User::updateOrCreate(
            ['email' => 'hrd@gmail.com'],
            [
                'name' => 'Aditya Wijaya, S.Psi',
                'role' => 'hrd',
                'password' => Hash::make('1234567890'),
            ]
        );

        // 2. CREATE LOKER SPK TEST berdasarkan data JobPosting ID 22
        $driverSpkConfig = [
            "criteria" => [
                ["key" => "gender", "type" => "select", "label" => "Jenis Kelamin", "value" => "both", "status" => "core", "weight" => 10],
                ["key" => "age", "type" => "range", "label" => "Batasan Usia", "value" => ["max" => 35, "min" => 25], "status" => "core", "weight" => 5],
                ["key" => "education", "type" => "select", "label" => "Pendidikan Minimal", "value" => "SMA/SMK", "status" => "core", "weight" => 5],
                ["key" => "experience", "type" => "number", "label" => "Pengalaman Minimum", "value" => 1, "status" => "secondary", "weight" => 10],
                ["key" => "placement_ready", "type" => "checkbox", "label" => "Kesiapan Penempatan UCI", "value" => ["city" => null, "type" => "anywhere"], "status" => "core", "weight" => 10],
                ["key" => "sertifikat_agd_ambulance", "type" => "file", "label" => "Sertifikat AGD (Ambulance)", "value" => null, "status" => "secondary", "weight" => 10],
                ["key" => "lisensi_sim_c_motor", "type" => "file", "label" => "Lisensi SIM C (Motor)", "value" => null, "status" => "secondary", "weight" => 20],
                ["key" => "lisensi_sim_b1_mobil_berat", "type" => "file", "label" => "Lisensi SIM B1 (Mobil Berat)", "value" => null, "status" => "core", "weight" => 30],
            ]
        ];
        
        $postingSpk = JobPosting::updateOrCreate(
            ['title' => 'Driver Ambulans Profesional (SPK Test)'],
            [
                'category' => 'Driver Ambulance',
                'description' => 'Loker replika dari ID 22 untuk testing SPK manual.',
                'core_gender' => 'both',
                'core_min_age' => 25,
                'core_max_age' => 35,
                'core_min_education' => 'SMA/SMK',
                'core_requires_agd' => false,
                'core_requires_sim_c' => false,
                'core_requires_sim_b1' => true,
                'second_min_experience' => 1,
                'second_requires_placement_ready' => true,
                'location_city' => null,
                'shift_type' => null,
                'salary_min' => null,
                'salary_max' => null,
                'salary_hidden' => true,
                'is_active' => true,
                'active_until' => Carbon::now()->addDays(30),
                'created_by' => $hrd->id,
                'requirements_config' => $driverSpkConfig
            ]
        );

        JobApplication::where('job_posting_id', $postingSpk->id)->delete();

        // 3. CREATE DUMMY APPLICANTS
        $applicantsData = [
            // Pelamar 1: Perfect Match (Umur pas, exp pas, dokumen core lengkap)
            [
                'name' => '1 - Perfect Candidate',
                'email' => 'spk1_perfect@gmail.com',
                'gender' => 'male',
                'birth_date' => Carbon::now()->subYears(28)->format('Y-m-d'), // Umur 28 (Gap 0)
                'education' => 'SMA/SMK',
                'experience' => 2,
                'placement_ready' => true,
                'sim_b1' => true, // Core
                'sim_c' => true, // Secondary
                'agd' => true, // Secondary
            ],
            // Pelamar 2: Kurang Pengalaman (Pengalaman 0 tahun (kurang 1), tapi usia 30, dokumen core ada)
            [
                'name' => '2 - Kurang Pengalaman',
                'email' => 'spk2_kurangexp@gmail.com',
                'gender' => 'male',
                'birth_date' => Carbon::now()->subYears(30)->format('Y-m-d'), 
                'education' => 'SMA/SMK', 
                'experience' => 0, // Gap -1
                'placement_ready' => true,
                'sim_b1' => true, 
                'sim_c' => true, 
                'agd' => true,
            ],
            // Pelamar 3: Usia Jauh Lebih Tua & Overqualified (Umur 40 (Gap -5), Pendidikan S1 (Gap +2), Pengalaman 5 thn (Gap +4))
            [
                'name' => '3 - Usia Lebih & Overqualified',
                'email' => 'spk3_usialebih@gmail.com',
                'gender' => 'male',
                'birth_date' => Carbon::now()->subYears(40)->format('Y-m-d'), // Umur 40 (Gap -5)
                'education' => 'S1', // Gap +2
                'experience' => 5, // Gap +4
                'placement_ready' => true,
                'sim_b1' => true, 
                'sim_c' => true, 
                'agd' => true,
            ],
            // Pelamar 4: Dokumen Secondary Kurang (Umur 26, Exp 2, Dokumen SIM C gaada (Gap -4))
            [
                'name' => '4 - Dokumen Secondary Kurang',
                'email' => 'spk4_no_sim_c@gmail.com',
                'gender' => 'male',
                'birth_date' => Carbon::now()->subYears(26)->format('Y-m-d'),
                'education' => 'SMA/SMK',
                'experience' => 2,
                'placement_ready' => false, // Gap -4
                'sim_b1' => true,
                'sim_c' => false, // Gap -4
                'agd' => false, // Gap -4
            ],
            // Pelamar 5: Dokumen Core Kurang (SIM B1 tidak ada) (Priority False)
            [
                'name' => '5 - Dokumen Core Kurang',
                'email' => 'spk5_no_sim_b1@gmail.com',
                'gender' => 'female',
                'birth_date' => Carbon::now()->subYears(25)->format('Y-m-d'),
                'education' => 'SMA/SMK',
                'experience' => 0,
                'placement_ready' => true,
                'sim_b1' => false, // Gap -4 (Core)
                'sim_c' => true,
                'agd' => true,
            ]
        ];

        foreach ($applicantsData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => 'pelamar',
                    'password' => Hash::make('1234567890'),
                ]
            );

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => $data['gender'],
                    'birth_date' => $data['birth_date'],
                    'education_level' => $data['education'],
                    'experience_years' => $data['experience'],
                    'phone' => '0812' . rand(10000000, 99999999),
                    'address' => 'Jl. Test SPK Dummy',
                    'city' => 'Tangerang',
                ]
            );

            // Create Application with new custom docs format
            $additionalDocs = [];
            if ($data['sim_b1']) $additionalDocs['lisensi_sim_b1_mobil_berat'] = true;
            if ($data['sim_c']) $additionalDocs['lisensi_sim_c_motor'] = true;
            if ($data['sim_c']) $additionalDocs['sim_c_aktif'] = true; // For backward compat with other custom field names
            if ($data['agd']) $additionalDocs['sertifikat_agd_ambulance'] = true;

            $app = JobApplication::create([
                'job_posting_id' => $postingSpk->id,
                'user_id' => $user->id,
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'education_level' => $data['education'],
                'experience_years' => $data['experience'],
                'placement_ready' => $data['placement_ready'],
                'additional_documents' => $additionalDocs,
                'status' => 'pending',
                'sim_c_path' => $data['sim_c'] ? 'dummy_sim_c.jpg' : null,
                'sim_b1_path' => $data['sim_b1'] ? 'dummy_sim_b1.jpg' : null,
                'agd_certificate_path' => $data['agd'] ? 'dummy_agd.jpg' : null,
            ]);
            
            // Re-calculate to populate spk_details automatically
            $spkResult = $postingSpk->calculateSpkScore($app);
            $app->update([
                'is_priority' => $spkResult['is_priority'],
                'matching_score' => $spkResult['matching_score'],
                'spk_details' => json_encode($spkResult)
            ]);
        }
        
        $this->command->info("Seeder SpkTestingSeeder (Berdasarkan ID 22) berhasil dijalankan!");
    }
}

