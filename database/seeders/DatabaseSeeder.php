<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobPosting;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Criterion::truncate();
        JobCategory::truncate();

        // 1. DRIVER AMBULANCE CRITERIA
        $catDriver = JobCategory::create(['name' => 'Driver Ambulance', 'is_active' => true]);
        $driverCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 25, 'max_default' => 35]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'sertifikat_agd_ambulance', 'label' => 'Sertifikat AGD (Ambulance)', 'type' => 'file', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null],
            ['key' => 'lisensi_sim_c_motor', 'label' => 'Lisensi SIM C (Motor)', 'type' => 'file', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null],
            ['key' => 'lisensi_sim_b1_mobil_berat', 'label' => 'Lisensi SIM B1 (Mobil Berat)', 'type' => 'file', 'default_status' => 'core', 'default_weight' => 30, 'config' => null],
        ];
        foreach ($driverCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => $catDriver->name, 'sort_order' => $index]));
        }

        // 2. ASISTEN KEPERAWATAN CRITERIA
        $catNurse = JobCategory::create(['name' => 'Asisten Keperawatan', 'is_active' => true]);
        $nurseCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 25, 'max_default' => 65]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'major', 'label' => 'Jurusan', 'type' => 'text', 'default_status' => 'core', 'default_weight' => 10, 'config' => null],
            ['key' => 'str_file', 'label' => 'Surat Tanda Registrasi (STR) / STRTK', 'type' => 'file', 'default_status' => 'core', 'default_weight' => 20, 'config' => null],
            ['key' => 'sertifikat_kompetensi', 'label' => 'Sertifikat Kompetensi Keperawatan', 'type' => 'file', 'default_status' => 'core', 'default_weight' => 20, 'config' => null],
        ];
        foreach ($nurseCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => $catNurse->name, 'sort_order' => $index]));
        }

        // 3. CLEANING SERVICE CRITERIA
        $catCS = JobCategory::create(['name' => 'Cleaning Service', 'is_active' => true]);
        $csCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['min_default' => 25, 'max_default' => 65]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'placement_choices', 'label' => 'Pilihan Penempatan', 'type' => 'text', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'sim_c_aktif', 'label' => 'SIM C Aktif', 'type' => 'file', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null],
        ];
        foreach ($csCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => $catCS->name, 'sort_order' => $index]));
        }

        // 4. RUNNER CRITERIA
        $catRunner = JobCategory::create(['name' => 'Runner', 'is_active' => true]);
        $runnerCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 23, 'max_default' => 35]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'major', 'label' => 'Jurusan', 'type' => 'text', 'default_status' => 'core', 'default_weight' => 10, 'config' => null],
            ['key' => 'medical_support', 'label' => 'Dukungan Medis', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'medical_terms', 'label' => 'Istilah-istilah Medis', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
        ];
        foreach ($runnerCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => $catRunner->name, 'sort_order' => $index]));
        }

        // 5. GARDENER CRITERIA
        $catGardener = JobCategory::create(['name' => 'Gardener', 'is_active' => true]);
        $gardenerCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 25, 'max_default' => 40]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'gardener_tech_understanding', 'label' => 'Memahami Teknis Pertumbuhan Tanaman', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'gardener_nursery_skill', 'label' => 'Mampu Mengelola Pembibitan Tanaman', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'gardener_tools_skill', 'label' => 'Menguasai Skill Penggunaan Alat-Alat Teknis', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null],
        ];
        foreach ($gardenerCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => $catGardener->name, 'sort_order' => $index]));
        }

        // CREATE CORE ACCOUNTS
        User::updateOrCreate(['email' => 'superadmin@gmail.com'], [
            'name' => 'Super Administrator', 'role' => 'superadmin', 'is_active' => true, 'password' => bcrypt('1234567890'),
        ]);

        $hrd = User::updateOrCreate(['email' => 'hrd@gmail.com'], [
            'name' => 'Aditya Wijaya, S.Psi', 'role' => 'hrd', 'password' => bcrypt('1234567890'),
        ]);

        $pimpinan = User::updateOrCreate(['email' => 'pimpinan@gmail.com'], [
            'name' => 'Bapak Direktur Pimpinan', 'role' => 'pimpinan', 'password' => bcrypt('1234567890'),
        ]);

        // MITRAS
        $mitra1 = \App\Models\Mitra::create(['name' => 'RSUD Kota Tangerang', 'logo_path' => '']);
        $mitra2 = \App\Models\Mitra::create(['name' => 'Klinik Bhakti Asih', 'logo_path' => '']);
        $mitra3 = \App\Models\Mitra::create(['name' => 'PT Bumi Serpong Damai Tbk', 'logo_path' => '']);

        // JOB POSTINGS
        $postings = [];

        $postings['Driver'] = JobPosting::create([
            'title' => 'Driver Ambulans Gawat Darurat', 'category' => $catDriver->name,
            'mitra_id' => $mitra1->id,
            'description' => 'Dibutuhkan Driver Ambulans Gawat Darurat yang sigap.',
            'core_gender' => 'male', 'core_min_age' => 25, 'core_max_age' => 35, 'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0, 'second_requires_placement_ready' => true,
            'shift_type' => 'shift', 'salary_min' => 4500000, 'salary_max' => 5500000, 'salary_hidden' => false,
            'is_active' => true, 'active_until' => Carbon::now()->addDays(30), 'created_by' => $hrd->id,
            'requirements_config' => [
                'criteria' => [
                    ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'status' => 'core', 'weight' => 20, 'value' => 'male'],
                    ['key' => 'age', 'label' => 'Usia', 'type' => 'range', 'status' => 'core', 'weight' => 20, 'value' => ['min' => 25, 'max' => 35]],
                    ['key' => 'lisensi_sim_b1_mobil_berat', 'label' => 'Lisensi SIM B1 (Mobil Berat)', 'type' => 'file', 'status' => 'core', 'weight' => 60, 'value' => ''],
                ]
            ],
        ]);

        $postings['CS'] = JobPosting::create([
            'title' => 'Cleaning Service Kantor Regional', 'category' => $catCS->name,
            'mitra_id' => $mitra3->id,
            'description' => 'Melakukan pemeliharaan kebersihan gedung kantor.',
            'core_gender' => 'both', 'core_min_age' => 25, 'core_max_age' => 65, 'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0, 'second_requires_placement_ready' => true,
            'shift_type' => 'non_shift', 'salary_min' => 3800000, 'salary_max' => 4300000, 'salary_hidden' => false,
            'is_active' => true, 'active_until' => Carbon::now()->addDays(30), 'created_by' => $hrd->id,
            'requirements_config' => [
                'criteria' => [
                    ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'status' => 'core', 'weight' => 20, 'value' => 'both'],
                    ['key' => 'experience', 'label' => 'Pengalaman Kerja', 'type' => 'number', 'status' => 'core', 'weight' => 40, 'value' => 0],
                    ['key' => 'sim_c_aktif', 'label' => 'SIM C Aktif', 'type' => 'file', 'status' => 'secondary', 'weight' => 40, 'value' => ''],
                ]
            ],
        ]);

        $postings['Nurse'] = JobPosting::create([
            'title' => 'Perawat Klinik', 'category' => $catNurse->name,
            'mitra_id' => $mitra2->id,
            'description' => 'Membantu tugas keperawatan di klinik.',
            'core_gender' => 'both', 'core_min_age' => 25, 'core_max_age' => 65, 'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0, 'second_requires_placement_ready' => true,
            'shift_type' => 'shift', 'salary_min' => 4800000, 'salary_max' => 5800000, 'salary_hidden' => false,
            'is_active' => true, 'active_until' => Carbon::now()->addDays(30), 'created_by' => $hrd->id,
            'spk_status' => 'completed',
            'requirements_config' => [
                'criteria' => [
                    ['key' => 'education', 'label' => 'Pendidikan', 'type' => 'select', 'status' => 'core', 'weight' => 30, 'value' => 'D3'],
                    ['key' => 'major', 'label' => 'Jurusan', 'type' => 'text', 'status' => 'core', 'weight' => 30, 'value' => 'Keperawatan'],
                    ['key' => 'str_file', 'label' => 'Surat Tanda Registrasi (STR) / STRTK', 'type' => 'file', 'status' => 'core', 'weight' => 40, 'value' => ''],
                ]
            ],
        ]);

        $postings['Runner'] = JobPosting::create([
            'title' => 'Runner Penunjang Operasional Medis', 'category' => $catRunner->name,
            'custom_mitra_name' => 'Siloam Hospitals (Custom Mitra)',
            'description' => 'Petugas lapangan untuk pengantaran.',
            'core_gender' => 'male', 'core_min_age' => 23, 'core_max_age' => 35, 'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0, 'second_requires_placement_ready' => false,
            'shift_type' => 'shift', 'salary_min' => 4100000, 'salary_max' => 4600000, 'salary_hidden' => false,
            'is_active' => true, 'active_until' => Carbon::now()->addDays(30), 'created_by' => $hrd->id,
            'spk_status' => 'completed',
            'requirements_config' => [
                'criteria' => [
                    ['key' => 'age', 'label' => 'Usia', 'type' => 'range', 'status' => 'core', 'weight' => 60, 'value' => ['min' => 23, 'max' => 35]],
                    ['key' => 'medical_support', 'label' => 'Pemahaman Dukungan Medis', 'type' => 'checkbox', 'status' => 'secondary', 'weight' => 20, 'value' => ''],
                    ['key' => 'medical_terms', 'label' => 'Pemahaman Istilah Medis', 'type' => 'checkbox', 'status' => 'secondary', 'weight' => 20, 'value' => ''],
                ]
            ],
        ]);

        $postings['Gardener'] = JobPosting::create([
            'title' => 'Gardener Area Lansekap PT UCI', 'category' => $catGardener->name,
            'custom_mitra_name' => 'Internal PT UCI',
            'description' => 'Mengelola pertamanan luar ruangan.',
            'core_gender' => 'male', 'core_min_age' => 25, 'core_max_age' => 40, 'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0, 'second_requires_placement_ready' => false,
            'shift_type' => 'non_shift', 'salary_min' => 4000000, 'salary_max' => 4500000, 'salary_hidden' => false,
            'is_active' => true, 'active_until' => Carbon::now()->addDays(30), 'created_by' => $hrd->id,
            'spk_status' => 'completed',
            'requirements_config' => [
                'criteria' => [
                    ['key' => 'experience', 'label' => 'Pengalaman Kerja', 'type' => 'number', 'status' => 'core', 'weight' => 50, 'value' => 0],
                    ['key' => 'gardener_tech_understanding', 'label' => 'Pemahaman Teknik Berkebun', 'type' => 'checkbox', 'status' => 'core', 'weight' => 50, 'value' => ''],
                ]
            ],
        ]);

        // HELPER TO CREATE APPLICANTS
        $createApplicant = function ($name, $email, $role, $gender, $birth, $edu, $major, $exp, $docs, $placement, $choice = null, $statusOverride = null) use ($postings) {
            $user = User::create(['name' => $name, 'email' => $email, 'role' => 'pelamar', 'password' => bcrypt('1234567890')]);
            UserProfile::create([
                'user_id' => $user->id, 'gender' => $gender, 'birth_place' => 'Tangerang', 'birth_date' => $birth,
                'phone' => '08000000000', 'education_level' => $edu, 'major' => $major, 'experience_years' => $exp,
                'address' => 'Jl. Dummy', 'city' => 'Tangerang', 'province' => 'Banten', 'postal_code' => '15111',
                'cv_path' => 'cv.pdf', 'photo_path' => 'photo.jpg', 'extras' => ['experiences' => []],
            ]);
            $app = JobApplication::create([
                'job_posting_id' => $postings[$role]->id, 'user_id' => $user->id, 'gender' => $gender,
                'birth_date' => $birth, 'education_level' => $edu, 'major' => $major, 'experience_years' => $exp,
                'placement_ready' => $placement, 'placement_choice' => $choice, 'additional_documents' => $docs,
            ]);
            $spk = $postings[$role]->calculateSpkScore($app);
            $app->update([
                'is_priority' => $spk['is_priority'], 
                'matching_score' => $spk['matching_score'],
                'status' => $statusOverride ?? 'pending',
                'interview_status' => $statusOverride === 'accepted' ? 'valid' : null
            ]);
        };

        // 1. 5 APPLICANTS FOR DRIVER (Min 25-35, Male, Core: SIM B1, Sec: AGD, SIM C)
        $createApplicant('Budi Driver Sempurna', 'd1@gmail.com', 'Driver', 'male', '1996-01-01', 'SMA/SMK', 'Umum', 3,
            ['sertifikat_agd_ambulance' => 'y.pdf', 'lisensi_sim_c_motor' => 'y.pdf', 'lisensi_sim_b1_mobil_berat' => 'y.pdf'], true, null, 'accepted'); // 100% Perfect -> Diterima Final

        $createApplicant('Andi Driver No C', 'd2@gmail.com', 'Driver', 'male', '1995-01-01', 'SMA/SMK', 'Umum', 1,
            ['sertifikat_agd_ambulance' => 'y.pdf', 'lisensi_sim_b1_mobil_berat' => 'y.pdf'], true, null, 'rejected'); // Misses secondary (SIM C) => Priority: Yes, Score < 100 (Ditolak)

        $createApplicant('Candra Driver No B1', 'd3@gmail.com', 'Driver', 'male', '1998-01-01', 'SMA/SMK', 'Umum', 0,
            ['sertifikat_agd_ambulance' => 'y.pdf', 'lisensi_sim_c_motor' => 'y.pdf'], true); // Misses core (SIM B1) => Priority: No

        $createApplicant('Dani Driver Muda', 'd4@gmail.com', 'Driver', 'male', '2004-01-01', 'SMA/SMK', 'Umum', 0,
            ['sertifikat_agd_ambulance' => 'y.pdf', 'lisensi_sim_c_motor' => 'y.pdf', 'lisensi_sim_b1_mobil_berat' => 'y.pdf'], true); // Underage (22 yrs) => Priority: No

        $createApplicant('Eka Driver Wanita', 'd5@gmail.com', 'Driver', 'female', '1996-01-01', 'SMA/SMK', 'Umum', 2,
            ['sertifikat_agd_ambulance' => 'y.pdf', 'lisensi_sim_c_motor' => 'y.pdf', 'lisensi_sim_b1_mobil_berat' => 'y.pdf'], true); // Wrong gender => Priority: No

        // 2. 5 APPLICANTS FOR NURSE (Min 25-65, Both, Core: Keperawatan, STR, Kompetensi)
        $createApplicant('Siti Nurse Sempurna', 'n1@gmail.com', 'Nurse', 'female', '1996-01-01', 'D3', 'Keperawatan', 2,
            ['str_file' => 'y.pdf', 'sertifikat_kompetensi' => 'y.pdf'], true, null, 'accepted'); // 100% Perfect -> Diterima Final

        $createApplicant('Agus Nurse Pria', 'n2@gmail.com', 'Nurse', 'male', '1998-01-01', 'SMA/SMK', 'Keperawatan', 1,
            ['str_file' => 'y.pdf', 'sertifikat_kompetensi' => 'y.pdf'], true, null, 'rejected'); // Perfect, male => Priority: Yes, Ditolak

        $createApplicant('Rini Nurse No STR', 'n3@gmail.com', 'Nurse', 'female', '2000-01-01', 'D3', 'Keperawatan', 0,
            ['sertifikat_kompetensi' => 'y.pdf'], true); // Misses core doc (STR) => Priority: No

        $createApplicant('Dina Nurse Umum', 'n4@gmail.com', 'Nurse', 'female', '1992-01-01', 'SMA/SMK', 'Umum', 0,
            ['str_file' => 'y.pdf', 'sertifikat_kompetensi' => 'y.pdf'], true); // Wrong major (Umum) => Priority: No

        $createApplicant('Ayu Nurse Muda', 'n5@gmail.com', 'Nurse', 'female', '2005-01-01', 'D3', 'Keperawatan', 0,
            ['str_file' => 'y.pdf', 'sertifikat_kompetensi' => 'y.pdf'], true); // Underage (21 yrs) => Priority: No

        // 3. 5 APPLICANTS FOR CLEANING SERVICE (Min 25-65, Both, Core: SMA, Placement, Sec: SIM C)
        $createApplicant('Joko CS Sempurna', 'c1@gmail.com', 'CS', 'male', '1991-01-01', 'SMA/SMK', 'Umum', 3,
            ['sim_c_aktif' => 'y.pdf'], true, 'Tangerang', 'accepted'); // 100% Perfect -> Diterima Final

        $createApplicant('Wati CS Wanita', 'c2@gmail.com', 'CS', 'female', '1986-01-01', 'SMA/SMK', 'Umum', 2,
            ['sim_c_aktif' => 'y.pdf'], true, 'Jakarta', 'rejected'); // Perfect, female => Priority: Yes, Ditolak

        $createApplicant('Heri CS No SIM', 'c3@gmail.com', 'CS', 'male', '1998-01-01', 'SMA/SMK', 'Umum', 1,
            [], true, 'Tangerang'); // Misses secondary (SIM C) => Priority: Yes, Score < 100

        $createApplicant('Bagas CS Muda', 'c4@gmail.com', 'CS', 'male', '2004-01-01', 'SMA/SMK', 'Umum', 0,
            ['sim_c_aktif' => 'y.pdf'], true, 'Tangerang'); // Underage (22 yrs) => Priority: No

        $createApplicant('Linda CS Tolak Penempatan', 'c5@gmail.com', 'CS', 'female', '1996-01-01', 'SMA/SMK', 'Umum', 0,
            ['sim_c_aktif' => 'y.pdf'], false, null); // Misses core (Placement) => Priority: No

        // 4. 5 APPLICANTS FOR RUNNER (Min 23-35, Male, Core: SMA, Sec: Med Support, Med Terms)
        $createApplicant('Fahmi Runner Sempurna', 'r1@gmail.com', 'Runner', 'male', '2001-01-01', 'SMA/SMK', 'Umum', 1,
            ['medical_support' => true, 'medical_terms' => true], true, null, 'accepted'); // 100% Perfect

        $createApplicant('Rizky Runner No Support', 'r2@gmail.com', 'Runner', 'male', '1998-01-01', 'SMA/SMK', 'Umum', 0,
            ['medical_support' => false, 'medical_terms' => true], true, null, 'rejected'); // Misses secondary (Med support) => Priority: Yes, Score < 100, Ditolak

        $createApplicant('Gani Runner Polos', 'r3@gmail.com', 'Runner', 'male', '2002-01-01', 'SMA/SMK', 'Umum', 0,
            ['medical_support' => false, 'medical_terms' => false], true); // Misses both secondary => Priority: Yes, Score lower

        $createApplicant('Tomi Runner Muda', 'r4@gmail.com', 'Runner', 'male', '2006-01-01', 'SMA/SMK', 'Umum', 0,
            ['medical_support' => true, 'medical_terms' => true], true); // Underage (20 yrs) => Priority: No

        $createApplicant('Siska Runner Wanita', 'r5@gmail.com', 'Runner', 'female', '2001-01-01', 'SMA/SMK', 'Umum', 0,
            ['medical_support' => true, 'medical_terms' => true], true); // Wrong gender => Priority: No

        // 5. 5 APPLICANTS FOR GARDENER (Min 25-40, Male, Core: Tech, Nursery. Sec: Tools)
        $createApplicant('Wahyu Gardener Sempurna', 'g1@gmail.com', 'Gardener', 'male', '1996-01-01', 'SMA/SMK', 'Umum', 2,
            ['gardener_tech_understanding' => true, 'gardener_nursery_skill' => true, 'gardener_tools_skill' => true], true, null, 'accepted'); // 100% Perfect

        $createApplicant('Dedi Gardener No Tools', 'g2@gmail.com', 'Gardener', 'male', '1998-01-01', 'SMA/SMK', 'Umum', 1,
            ['gardener_tech_understanding' => true, 'gardener_nursery_skill' => true, 'gardener_tools_skill' => false], true, null, 'rejected'); // Misses secondary (Tools) => Priority: Yes, Score < 100, Ditolak

        $createApplicant('Tono Gardener No Tech', 'g3@gmail.com', 'Gardener', 'male', '1999-01-01', 'SMA/SMK', 'Umum', 0,
            ['gardener_tech_understanding' => false, 'gardener_nursery_skill' => true, 'gardener_tools_skill' => true], true); // Misses core (Tech) => Priority: No

        $createApplicant('Aldo Gardener Muda', 'g4@gmail.com', 'Gardener', 'male', '2004-01-01', 'SMA/SMK', 'Umum', 0,
            ['gardener_tech_understanding' => true, 'gardener_nursery_skill' => true, 'gardener_tools_skill' => true], true); // Underage (22 yrs) => Priority: No

        $createApplicant('Yuni Gardener Wanita', 'g5@gmail.com', 'Gardener', 'female', '1994-01-01', 'SMA/SMK', 'Umum', 1,
            ['gardener_tech_understanding' => true, 'gardener_nursery_skill' => true, 'gardener_tools_skill' => true], true); // Wrong gender => Priority: No
    }
}
