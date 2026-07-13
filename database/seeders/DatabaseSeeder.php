<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Criterion;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with a highly comprehensive, real-world dataset.
     */
    public function run(): void
    {
        // Clear old criteria
        Criterion::truncate();

        // 1. DRIVER AMBULANCE CRITERIA
        $driverCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 25, 'max_default' => 35]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'sertifikat_agd_ambulance', 'label' => 'Sertifikat AGD (Ambulance)', 'type' => 'file', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null],
            ['key' => 'lisensi_sim_c_motor', 'label' => 'Lisensi SIM C (Motor)', 'type' => 'file', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null],
            ['key' => 'lisensi_sim_b1_mobil_berat', 'label' => 'Lisensi SIM B1 (Mobil Berat)', 'type' => 'file', 'default_status' => 'core', 'default_weight' => 30, 'config' => null]
        ];
        foreach ($driverCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => 'Driver Ambulance', 'sort_order' => $index]));
        }

        // 2. ASISTEN KEPERAWATAN CRITERIA
        $nurseCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 25, 'max_default' => 65]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'major', 'label' => 'Jurusan', 'type' => 'text', 'default_status' => 'core', 'default_weight' => 10, 'config' => null],
            ['key' => 'str_file', 'label' => 'Surat Tanda Registrasi (STR) / STRTK', 'type' => 'file', 'default_status' => 'core', 'default_weight' => 20, 'config' => null],
            ['key' => 'sertifikat_kompetensi', 'label' => 'Sertifikat Kompetensi Keperawatan', 'type' => 'file', 'default_status' => 'core', 'default_weight' => 20, 'config' => null]
        ];
        foreach ($nurseCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => 'Asisten Keperawatan', 'sort_order' => $index]));
        }

        // 3. CLEANING SERVICE CRITERIA
        $csCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['min_default' => 25, 'max_default' => 65]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'core', 'default_weight' => 15, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'placement_choices', 'label' => 'Pilihan Penempatan', 'type' => 'text', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'sim_c_aktif', 'label' => 'SIM C Aktif', 'type' => 'file', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null]
        ];
        foreach ($csCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => 'Cleaning Service', 'sort_order' => $index]));
        }

        // 4. RUNNER CRITERIA
        $runnerCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 23, 'max_default' => 35]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'major', 'label' => 'Jurusan', 'type' => 'text', 'default_status' => 'core', 'default_weight' => 10, 'config' => null],
            ['key' => 'medical_support', 'label' => 'Dukungan Medis', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'medical_terms', 'label' => 'Istilah-istilah Medis', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null]
        ];
        foreach ($runnerCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => 'Runner', 'sort_order' => $index]));
        }

        // 5. GARDENER CRITERIA
        $gardenerCriteria = [
            ['key' => 'gender', 'label' => 'Jenis Kelamin', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['male', 'female', 'both'], 'labels' => ['Pria', 'Wanita', 'Semua']]],
            ['key' => 'age', 'label' => 'Batasan Usia', 'type' => 'range', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['min_default' => 25, 'max_default' => 40]],
            ['key' => 'education', 'label' => 'Pendidikan Minimal', 'type' => 'select', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['options' => ['SMA/SMK', 'D3', 'S1', 'S2', 'S3']]],
            ['key' => 'experience', 'label' => 'Pengalaman Minimum', 'type' => 'number', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['unit' => 'Tahun', 'min' => 0]],
            ['key' => 'placement_ready', 'label' => 'Kesiapan Penempatan UCI', 'type' => 'checkbox', 'default_status' => 'core', 'default_weight' => 10, 'config' => ['sub_types' => ['anywhere', 'specific']]],
            ['key' => 'gardener_tech_understanding', 'label' => 'Memahami Teknis Pertumbuhan Tanaman', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'gardener_nursery_skill', 'label' => 'Mampu Mengelola Pembibitan Tanaman', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 20, 'config' => null],
            ['key' => 'gardener_tools_skill', 'label' => 'Menguasai Skill Penggunaan Alat-Alat Teknis', 'type' => 'checkbox', 'default_status' => 'secondary', 'default_weight' => 10, 'config' => null]
        ];
        foreach ($gardenerCriteria as $index => $c) {
            Criterion::create(array_merge($c, ['category' => 'Gardener', 'sort_order' => $index]));
        }

        // ----------------------------------------------------
        // 1. CREATE CORE ACCOUNTS
        // ----------------------------------------------------
        // Superadmin account
        User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'      => 'Super Administrator',
                'role'      => 'superadmin',
                'is_active' => true,
                'password'  => bcrypt('1234567890'),
            ]
        );

        $hrd = User::updateOrCreate(
            ['email' => 'hrd@gmail.com'],
            [
                'name'     => 'Aditya Wijaya, S.Psi',
                'role'     => 'hrd',
                'password' => bcrypt('1234567890'),
            ]
        );

        $pelamarUtama = User::updateOrCreate(
            ['email' => 'pelamar@gmail.com'],
            [
                'name' => 'Rian Hidayat',
                'role' => 'pelamar',
                'password' => bcrypt('1234567890'),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $pelamarUtama->id],
            [
                'gender' => 'male',
                'birth_place' => 'Tangerang',
                'birth_date' => '1998-05-15', // Age 28 in 2026
                'phone' => '081234567890',
                'education_level' => 'SMA/SMK',
                'major' => 'Umum',
                'experience_years' => 3,
                'address' => 'Jl. Merdeka No. 45, RT 02/RW 04, Sukasari',
                'city' => 'Tangerang',
                'province' => 'Banten',
                'postal_code' => '15111',
                'cv_path' => 'cv_dummy.pdf',
                'photo_path' => 'photo_dummy.jpg',
                'extras' => [
                    'experiences' => [
                        [
                            'company' => 'PT Indogreen Landscape',
                            'position' => 'Assistant Gardener',
                            'start_date' => '2023-01',
                            'end_date' => '2025-01',
                            'duration' => '2 Tahun',
                            'description' => 'Mengelola pembibitan tanaman hias, pemangkasan rutin, serta perawatan estetika taman korporat.'
                        ],
                        [
                            'company' => 'CV Karya Hijau',
                            'position' => 'Gardener Junior',
                            'start_date' => '2022-01',
                            'end_date' => '2022-12',
                            'duration' => '1 Tahun',
                            'description' => 'Melakukan pembersihan area taman, penyiraman, dan pemupukan tanaman berkala.'
                        ]
                    ]
                ]
            ]
        );

        // ----------------------------------------------------
        // 2. CREATE MULTIPLE ADDITIONAL REALISTIC PELAMAR USERS
        // ----------------------------------------------------
        $applicantsData = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'gender' => 'male',
                'birth_date' => '1996-08-20', // Age 29
                'education' => 'SMA/SMK',
                'major' => 'Umum',
                'experience' => 4,
                'city' => 'Jakarta Barat',
                'phone' => '087788992211',
                'experiences' => [
                    [
                        'company' => 'RS Graha Medika',
                        'position' => 'Driver Ambulans Operasional',
                        'start_date' => '2021-01',
                        'end_date' => '2025-01',
                        'duration' => '4 Tahun',
                        'description' => 'Mengemudikan ambulans dengan respons cepat dalam keadaan darurat medis, memelihara keandalan mesin ambulans.'
                    ]
                ]
            ],
            [
                'name' => 'Ani Lestari',
                'email' => 'ani@gmail.com',
                'gender' => 'female',
                'birth_date' => '2000-03-12', // Age 26
                'education' => 'SMA/SMK',
                'major' => 'Keperawatan',
                'experience' => 2,
                'city' => 'Tangerang',
                'phone' => '081299887766',
                'experiences' => [
                    [
                        'company' => 'Klinik Sehat Utama',
                        'position' => 'Asisten Perawat Gigi & Umum',
                        'start_date' => '2024-01',
                        'end_date' => '2026-01',
                        'duration' => '2 Tahun',
                        'description' => 'Membantu dokter gigi menyiapkan peralatan penunjang medis, melakukan pencatatan rekam medis pasien secara rapi.'
                    ]
                ]
            ],
            [
                'name' => 'Chandra Wijaya',
                'email' => 'chandra@gmail.com',
                'gender' => 'male',
                'birth_date' => '2002-11-05', // Age 23
                'education' => 'SMA/SMK',
                'major' => 'Umum',
                'experience' => 1,
                'city' => 'Tangerang',
                'phone' => '085611223344',
                'experiences' => [
                    [
                        'company' => 'PT Mahakarya Logistik',
                        'position' => 'Kurir & Runner Lapangan',
                        'start_date' => '2025-01',
                        'end_date' => '2026-01',
                        'duration' => '1 Tahun',
                        'description' => 'Mengirimkan berkas penunjang operasional kantor cabang, melakukan pengecekan inventaris harian.'
                    ]
                ]
            ],
            [
                'name' => 'Dewi Rahmawati',
                'email' => 'dewi@gmail.com',
                'gender' => 'female',
                'birth_date' => '1999-07-25', // Age 26
                'education' => 'D3',
                'major' => 'Keperawatan',
                'experience' => 3,
                'city' => 'Jakarta Selatan',
                'phone' => '089911223344',
                'experiences' => [
                    [
                        'company' => 'RS Sentra Medika',
                        'position' => 'Asisten Keperawatan Rawat Inap',
                        'start_date' => '2023-01',
                        'end_date' => '2026-01',
                        'duration' => '3 Tahun',
                        'description' => 'Melakukan pemantauan tanda-tanda vital pasien rawat inap, menyuapi makanan dan mengelola kenyamanan pasien.'
                    ]
                ]
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko@gmail.com',
                'gender' => 'male',
                'birth_date' => '1992-04-18', // Age 34
                'education' => 'SMA/SMK',
                'major' => 'Umum',
                'experience' => 6,
                'city' => 'Tangerang Kota',
                'phone' => '081344556677',
                'experiences' => [
                    [
                        'company' => 'PT Indoclean Services',
                        'position' => 'Cleaning Service Senior',
                        'start_date' => '2020-01',
                        'end_date' => '2026-01',
                        'duration' => '6 Tahun',
                        'description' => 'Melakukan pembersihan area kaca tinggi luar ruangan, poles lantai marmer, serta memimpin tim CS lapangan.'
                    ]
                ]
            ],
            [
                'name' => 'Fajar Sidik',
                'email' => 'fajar@gmail.com',
                'gender' => 'male',
                'birth_date' => '2004-10-10', // Age 21 (Underage for Driver & Gardener, perfect for Runner/CS)
                'education' => 'SMA/SMK',
                'major' => 'Umum',
                'experience' => 0,
                'city' => 'Tangerang',
                'phone' => '087812123434',
                'experiences' => []
            ]
        ];

        $users = [];
        foreach ($applicantsData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => 'pelamar',
                    'password' => bcrypt('1234567890'),
                ]
            );

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'gender' => $data['gender'],
                    'birth_place' => $data['city'],
                    'birth_date' => $data['birth_date'],
                    'phone' => $data['phone'],
                    'education_level' => $data['education'],
                    'major' => $data['major'],
                    'experience_years' => $data['experience'],
                    'address' => 'Jl. Kebagusan Indah No. 12, ' . $data['city'],
                    'city' => $data['city'],
                    'province' => 'DKI Jakarta',
                    'postal_code' => '12000',
                    'cv_path' => 'cv_' . strtolower(explode(' ', $data['name'])[0]) . '.pdf',
                    'photo_path' => 'photo_' . strtolower(explode(' ', $data['name'])[0]) . '.jpg',
                    'extras' => ['experiences' => $data['experiences']]
                ]
            );

            $users[strtolower(explode(' ', $data['name'])[0])] = $user;
        }

        // ----------------------------------------------------
        // 3. CREATE JOB POSTINGS FOR ALL 5 ROLE CATEGORIES
        // ----------------------------------------------------
        
        // 1. DRIVER AMBULANCE
        $driverConfig = [
            'gender' => ['status' => 'core', 'value' => 'male'],
            'age' => ['status' => 'core', 'min' => 25, 'max' => 35],
            'education' => ['status' => 'core', 'value' => 'SMA/SMK'],
            'experience' => ['status' => 'secondary', 'value' => 0],
            'placement_ready' => ['status' => 'core'],
            'custom_documents' => [
                ['key' => 'sertifikat_agd_ambulance', 'label' => 'Sertifikat AGD (Ambulance)', 'status' => 'secondary'],
                ['key' => 'lisensi_sim_c_motor', 'label' => 'Lisensi SIM C (Motor)', 'status' => 'secondary'],
                ['key' => 'lisensi_sim_b1_mobil_berat', 'label' => 'Lisensi SIM B1 (Mobil Berat)', 'status' => 'core']
            ]
        ];
        $postingDriver = JobPosting::create([
            'title' => 'Driver Ambulans Gawat Darurat',
            'category' => 'Driver Ambulance',
            'description' => 'Dibutuhkan Driver Ambulans Gawat Darurat yang sigap, tanggap, dan memiliki keahlian mengemudi tingkat tinggi untuk ditempatkan di operasional unit medis UCI.',
            'core_gender' => 'male',
            'core_min_age' => 25,
            'core_max_age' => 35,
            'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0,
            'second_requires_placement_ready' => true,
            'location_city' => null,
            'shift_type' => 'shift',
            'salary_min' => 4500000,
            'salary_max' => 5500000,
            'salary_hidden' => false,
            'is_active' => true,
            'active_until' => Carbon::now()->addDays(30),
            'created_by' => $hrd->id,
            'requirements_config' => $driverConfig
        ]);

        // 2. CLEANING SERVICE
        $csConfig = [
            'gender' => ['status' => 'core', 'value' => 'both'],
            'age' => ['status' => 'core', 'min' => 25, 'max' => 65],
            'education' => ['status' => 'core', 'value' => 'SMA/SMK'],
            'experience' => ['status' => 'core', 'value' => 0],
            'placement_ready' => ['status' => 'core'],
            'placement_choices' => ['status' => 'secondary', 'value' => 'Jakarta Barat, Tangerang'],
            'custom_documents' => [
                ['key' => 'sim_c_aktif', 'label' => 'SIM C Aktif', 'status' => 'secondary']
            ]
        ];
        $postingCS = JobPosting::create([
            'title' => 'Cleaning Service Kantor Regional',
            'category' => 'Cleaning Service',
            'description' => 'Melakukan pemeliharaan kebersihan gedung kantor, pembersihan kaca tinggi, pengepelan marmer, dan menjaga sterilisasi ruangan kerja karyawan.',
            'core_gender' => 'both',
            'core_min_age' => 25,
            'core_max_age' => 65,
            'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0,
            'second_requires_placement_ready' => true,
            'location_city' => null,
            'shift_type' => 'non_shift',
            'salary_min' => 3800000,
            'salary_max' => 4300000,
            'salary_hidden' => false,
            'is_active' => true,
            'active_until' => Carbon::now()->addDays(30),
            'created_by' => $hrd->id,
            'requirements_config' => $csConfig
        ]);

        // 3. ASISTEN KEPERAWATAN
        $nurseConfig = [
            'gender' => ['status' => 'core', 'value' => 'both'],
            'age' => ['status' => 'core', 'min' => 25, 'max' => 65],
            'education' => ['status' => 'core', 'value' => 'SMA/SMK'],
            'experience' => ['status' => 'secondary', 'value' => 0],
            'placement_ready' => ['status' => 'core'],
            'major' => ['status' => 'core', 'value' => 'Keperawatan'],
            'custom_documents' => [
                ['key' => 'str_file', 'label' => 'Surat Tanda Registrasi (STR) / STRTK', 'status' => 'core'],
                ['key' => 'sertifikat_kompetensi', 'label' => 'Sertifikat Kompetensi Keperawatan', 'status' => 'core']
            ]
        ];
        $postingNurse = JobPosting::create([
            'title' => 'Asisten Keperawatan Rawat Jalan',
            'category' => 'Asisten Keperawatan',
            'description' => 'Membantu tugas keperawatan di klinik rawat jalan UCI, mengawal pemantauan pasien, dan mengelola sterilisasi berkala perkakas medis kotor.',
            'core_gender' => 'both',
            'core_min_age' => 25,
            'core_max_age' => 65,
            'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0,
            'second_requires_placement_ready' => true,
            'location_city' => null,
            'shift_type' => 'shift',
            'salary_min' => 4800000,
            'salary_max' => 5800000,
            'salary_hidden' => false,
            'is_active' => true,
            'active_until' => Carbon::now()->addDays(30),
            'created_by' => $hrd->id,
            'requirements_config' => $nurseConfig
        ]);

        // 4. RUNNER
        $runnerConfig = [
            'gender' => ['status' => 'core', 'value' => 'male'],
            'age' => ['status' => 'core', 'min' => 23, 'max' => 35],
            'education' => ['status' => 'core', 'value' => 'SMA/SMK'],
            'experience' => ['status' => 'core', 'value' => 0],
            'placement_ready' => ['status' => 'secondary'],
            'major' => ['status' => 'core', 'value' => 'Kesehatan, Umum'],
            'medical_support' => ['status' => 'secondary'],
            'medical_terms' => ['status' => 'secondary'],
            'custom_documents' => []
        ];
        $postingRunner = JobPosting::create([
            'title' => 'Runner Penunjang Operasional Medis',
            'category' => 'Runner',
            'description' => 'Petugas lapangan yang tanggap untuk mengantarkan penunjang medis darurat ke kamar pasien, menguasai alur koordinasi apoteker & perawat.',
            'core_gender' => 'male',
            'core_min_age' => 23,
            'core_max_age' => 35,
            'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0,
            'second_requires_placement_ready' => false,
            'location_city' => 'Tangerang',
            'shift_type' => 'shift',
            'salary_min' => 4100000,
            'salary_max' => 4600000,
            'salary_hidden' => false,
            'is_active' => true,
            'active_until' => Carbon::now()->addDays(30),
            'created_by' => $hrd->id,
            'requirements_config' => $runnerConfig
        ]);

        // 5. GARDENER
        $gardenerConfig = [
            'gender' => ['status' => 'core', 'value' => 'male'],
            'age' => ['status' => 'core', 'min' => 25, 'max' => 40],
            'education' => ['status' => 'core', 'value' => 'SMA/SMK'],
            'experience' => ['status' => 'core', 'value' => 0],
            'placement_ready' => ['status' => 'secondary'],
            'gardener_tech_understanding' => ['status' => 'core'],
            'gardener_nursery_skill' => ['status' => 'core'],
            'gardener_tools_skill' => ['status' => 'secondary'],
            'custom_documents' => []
        ];
        $postingGardener = JobPosting::create([
            'title' => 'Gardener Area Lansekap PT UCI',
            'category' => 'Gardener',
            'description' => 'Mengelola pertamanan luar ruangan rumah sakit & kantor regional UCI Tangerang. Wajib menguasai teknik pembibitan dan pemangkasan artistik lansekap.',
            'core_gender' => 'male',
            'core_min_age' => 25,
            'core_max_age' => 40,
            'core_min_education' => 'SMA/SMK',
            'second_min_experience' => 0,
            'second_requires_placement_ready' => false,
            'location_city' => 'Tangerang',
            'shift_type' => 'non_shift',
            'salary_min' => 4000000,
            'salary_max' => 4500000,
            'salary_hidden' => false,
            'is_active' => true,
            'active_until' => Carbon::now()->addDays(30),
            'created_by' => $hrd->id,
            'requirements_config' => $gardenerConfig
        ]);

        // ----------------------------------------------------
        // 4. SEED APPLICATIONS MAPPED TO BOTH PRIORITY AND NON-PRIORITY SKIPS
        // ----------------------------------------------------

        // === APPLICATIONS FOR: GARDENER ===
        // 1. Pelamar Utama (Rian Hidayat) -> Perfect Candidate (Score 100%, Priority: Yes)
        $app1 = JobApplication::create([
            'job_posting_id' => $postingGardener->id,
            'user_id' => $pelamarUtama->id,
            'gender' => 'male',
            'birth_date' => '1998-05-15', // Age 28
            'education_level' => 'SMA/SMK',
            'major' => 'Umum',
            'experience_years' => 0, // Aligned with required min experience 0 for perfect matching
            'placement_ready' => true,
            'additional_documents' => [
                'gardener_tech_understanding' => true,
                'gardener_nursery_skill' => true,
                'gardener_tools_skill' => true,
            ]
        ]);
        $spk = $postingGardener->calculateSpkScore($app1);
        $app1->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 2. Chandra Wijaya -> Priority, but misses 1 secondary tools checkbox (Score 80%, Priority: Yes)
        $app2 = JobApplication::create([
            'job_posting_id' => $postingGardener->id,
            'user_id' => $users['chandra']->id,
            'gender' => 'male',
            'birth_date' => '2002-11-05', // Age 23 (but configuration min is 25, wait! Age 23 is below 25, so Chandra will be non-priority due to age!)
            'education_level' => 'SMA/SMK',
            'major' => 'Umum',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'gardener_tech_understanding' => true,
                'gardener_nursery_skill' => true,
                'gardener_tools_skill' => false,
            ]
        ]);
        $spk = $postingGardener->calculateSpkScore($app2);
        $app2->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 3. Eko Prasetyo -> Priority Candidate with 0 exp alignment (Score 100%, Priority: Yes)
        $app3 = JobApplication::create([
            'job_posting_id' => $postingGardener->id,
            'user_id' => $users['eko']->id,
            'gender' => 'male',
            'birth_date' => '1992-04-18', // Age 34
            'education_level' => 'SMA/SMK',
            'major' => 'Umum',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'gardener_tech_understanding' => true,
                'gardener_nursery_skill' => true,
                'gardener_tools_skill' => true,
            ]
        ]);
        $spk = $postingGardener->calculateSpkScore($app3);
        $app3->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 4. Budi Santoso -> Non-Priority because age 29, but leaves core "gardener_tech_understanding" unchecked (Priority: No)
        $app4 = JobApplication::create([
            'job_posting_id' => $postingGardener->id,
            'user_id' => $users['budi']->id,
            'gender' => 'male',
            'birth_date' => '1996-08-20', // Age 29
            'education_level' => 'SMA/SMK',
            'major' => 'Umum',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'gardener_tech_understanding' => false, // Misses core checkbox!
                'gardener_nursery_skill' => true,
                'gardener_tools_skill' => true,
            ]
        ]);
        $spk = $postingGardener->calculateSpkScore($app4);
        $app4->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);


        // === APPLICATIONS FOR: RUNNER ===
        // 1. Chandra Wijaya -> Perfect Runner (Score 100%, Priority: Yes)
        $appRunner1 = JobApplication::create([
            'job_posting_id' => $postingRunner->id,
            'user_id' => $users['chandra']->id,
            'gender' => 'male',
            'birth_date' => '2002-11-05', // Age 23
            'education_level' => 'SMA/SMK',
            'major' => 'Umum',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'medical_support' => true,
                'medical_terms' => true,
            ]
        ]);
        $spk = $postingRunner->calculateSpkScore($appRunner1);
        $appRunner1->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 2. Fajar Sidik -> Non-Priority due to age 21 (min configuration is 23)
        $appRunner2 = JobApplication::create([
            'job_posting_id' => $postingRunner->id,
            'user_id' => $users['fajar']->id,
            'gender' => 'male',
            'birth_date' => '2004-10-10', // Age 21
            'education_level' => 'SMA/SMK',
            'major' => 'Umum',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'medical_support' => true,
                'medical_terms' => true,
            ]
        ]);
        $spk = $postingRunner->calculateSpkScore($appRunner2);
        $appRunner2->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);


        // === APPLICATIONS FOR: ASISTEN KEPERAWATAN ===
        // 1. Ani Lestari -> Perfect Assistant Nurse (Score 100%, Priority: Yes)
        $appNurse1 = JobApplication::create([
            'job_posting_id' => $postingNurse->id,
            'user_id' => $users['ani']->id,
            'gender' => 'female',
            'birth_date' => '2000-03-12', // Age 26
            'education_level' => 'SMA/SMK',
            'major' => 'Keperawatan',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'str_file' => 'applications/dummy_str.pdf',
                'sertifikat_kompetensi' => 'applications/dummy_cert.pdf'
            ]
        ]);
        $spk = $postingNurse->calculateSpkScore($appNurse1);
        $appNurse1->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 2. Dewi Rahmawati -> Priority with higher D3 education rank (Score 100%, Priority: Yes)
        $appNurse2 = JobApplication::create([
            'job_posting_id' => $postingNurse->id,
            'user_id' => $users['dewi']->id,
            'gender' => 'female',
            'birth_date' => '1999-07-25', // Age 26
            'education_level' => 'D3',
            'major' => 'Keperawatan',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'str_file' => 'applications/dummy_str2.pdf',
                'sertifikat_kompetensi' => 'applications/dummy_cert2.pdf'
            ]
        ]);
        $spk = $postingNurse->calculateSpkScore($appNurse2);
        $appNurse2->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 3. Eko Prasetyo -> Non-Priority because major is 'Umum' (Mandatory core is 'Keperawatan')
        $appNurse3 = JobApplication::create([
            'job_posting_id' => $postingNurse->id,
            'user_id' => $users['eko']->id,
            'gender' => 'male',
            'birth_date' => '1992-04-18', // Age 34
            'education_level' => 'SMA/SMK',
            'major' => 'Umum', // Wrong major!
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'str_file' => 'applications/dummy_str3.pdf',
                'sertifikat_kompetensi' => 'applications/dummy_cert3.pdf'
            ]
        ]);
        $spk = $postingNurse->calculateSpkScore($appNurse3);
        $appNurse3->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);


        // === APPLICATIONS FOR: DRIVER AMBULANCE ===
        // 1. Budi Santoso -> Perfect Ambulance Driver (Score 100%, Priority: Yes)
        $appDriver1 = JobApplication::create([
            'job_posting_id' => $postingDriver->id,
            'user_id' => $users['budi']->id,
            'gender' => 'male',
            'birth_date' => '1996-08-20', // Age 29
            'education_level' => 'SMA/SMK',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'sertifikat_agd_ambulance' => 'applications/dummy_agd.pdf',
                'lisensi_sim_c_motor' => 'applications/dummy_simc.pdf',
                'lisensi_sim_b1_mobil_berat' => 'applications/dummy_simb1.pdf'
            ]
        ]);
        $spk = $postingDriver->calculateSpkScore($appDriver1);
        $appDriver1->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 2. Rian Hidayat (Pelamar Utama) -> Non-Priority because misses mandatory core SIM B1 Mobil Berat upload (Priority: No)
        $appDriver2 = JobApplication::create([
            'job_posting_id' => $postingDriver->id,
            'user_id' => $pelamarUtama->id,
            'gender' => 'male',
            'birth_date' => '1998-05-15', // Age 28
            'education_level' => 'SMA/SMK',
            'experience_years' => 0,
            'placement_ready' => true,
            'additional_documents' => [
                'sertifikat_agd_ambulance' => 'applications/dummy_agd.pdf',
                'lisensi_sim_c_motor' => 'applications/dummy_simc.pdf'
                // Missing SIM B1!
            ]
        ]);
        $spk = $postingDriver->calculateSpkScore($appDriver2);
        $appDriver2->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);


        // === APPLICATIONS FOR: CLEANING SERVICE ===
        // 1. Eko Prasetyo -> Perfect Cleaning Service (Score 100%, Priority: Yes)
        $appCS1 = JobApplication::create([
            'job_posting_id' => $postingCS->id,
            'user_id' => $users['eko']->id,
            'gender' => 'male',
            'birth_date' => '1992-04-18', // Age 34
            'education_level' => 'SMA/SMK',
            'experience_years' => 0,
            'placement_ready' => true,
            'placement_choice' => 'Jakarta Barat',
            'additional_documents' => [
                'sim_c_aktif' => 'applications/dummy_simc_cs.pdf'
            ]
        ]);
        $spk = $postingCS->calculateSpkScore($appCS1);
        $appCS1->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);

        // 2. Rian Hidayat -> Priority, misses optional custom document SIM C (Score 80%, Priority: Yes)
        $appCS2 = JobApplication::create([
            'job_posting_id' => $postingCS->id,
            'user_id' => $pelamarUtama->id,
            'gender' => 'male',
            'birth_date' => '1998-05-15', // Age 28
            'education_level' => 'SMA/SMK',
            'experience_years' => 0,
            'placement_ready' => true,
            'placement_choice' => 'Jakarta Barat',
            'additional_documents' => [] // leaves SIM C document empty
        ]);
        $spk = $postingCS->calculateSpkScore($appCS2);
        $appCS2->update(['is_priority' => $spk['is_priority'], 'matching_score' => $spk['matching_score']]);
    }
}
