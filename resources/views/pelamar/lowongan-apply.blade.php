@extends('layouts.dashboard')

@section('dashboard-title', 'Form Lamaran')

@section('dashboard-content')
    @php
        $config = $posting->requirements_config ?? [];
        
        $genderStatus = $config['gender']['status'] ?? 'core';
        $genderVal = $config['gender']['value'] ?? 'both';
        
        $ageStatus = $config['age']['status'] ?? 'core';
        $ageMin = $config['age']['min'] ?? 18;
        $ageMax = $config['age']['max'] ?? 65;
        
        $educationStatus = $config['education']['status'] ?? 'core';
        $educationVal = $config['education']['value'] ?? 'SMA/SMK';
        
        $agdStatus = $config['agd']['status'] ?? 'nonaktif';
        $simcStatus = $config['sim_c']['status'] ?? 'nonaktif';
        $simb1Status = $config['sim_b1']['status'] ?? 'nonaktif';
        
        $experienceStatus = $config['experience']['status'] ?? 'nonaktif';
        $experienceVal = $config['experience']['value'] ?? 0;
        
        $placementStatus = $config['placement_ready']['status'] ?? 'core';
        $placementType = $config['placement_ready']['type'] ?? 'anywhere';

        // Custom configs for major, placement choices, and custom files
        $majorStatus = $config['major']['status'] ?? 'nonaktif';
        $majorVal = $config['major']['value'] ?? '';
        
        $placementChoicesStatus = $config['placement_choices']['status'] ?? 'nonaktif';
        $placementChoicesVal = $config['placement_choices']['value'] ?? '';
        $placementChoicesArray = !empty($placementChoicesVal) ? array_map('trim', explode(',', $placementChoicesVal)) : [];
        
        $medicalSupportStatus = $config['medical_support']['status'] ?? 'nonaktif';
        $medicalTermsStatus = $config['medical_terms']['status'] ?? 'nonaktif';
        
        $medicalSupportChecked = old('medical_support', $defaults['additional_documents']['medical_support'] ?? false);
        $medicalTermsChecked = old('medical_terms', $defaults['additional_documents']['medical_terms'] ?? false);

        $gardenerTechStatus = $config['gardener_tech_understanding']['status'] ?? 'nonaktif';
        $gardenerNurseryStatus = $config['gardener_nursery_skill']['status'] ?? 'nonaktif';
        $gardenerToolsStatus = $config['gardener_tools_skill']['status'] ?? 'nonaktif';
        
        $gardenerTechChecked = old('gardener_tech_understanding', $defaults['additional_documents']['gardener_tech_understanding'] ?? false);
        $gardenerNurseryChecked = old('gardener_nursery_skill', $defaults['additional_documents']['gardener_nursery_skill'] ?? false);
        $gardenerToolsChecked = old('gardener_tools_skill', $defaults['additional_documents']['gardener_tools_skill'] ?? false);

        // If both placement choices and placement ready are inactive, show placement ready as core/active
        $effectivePlacementStatus = $placementStatus;
        if ($placementStatus === 'nonaktif' && $placementChoicesStatus === 'nonaktif') {
            $effectivePlacementStatus = 'core';
        }

        $customDocsConfig = $config['custom_documents'] ?? [];

        // Custom helpers for requirement status badge styling
        $getBadgeClass = function($status) {
            if ($status === 'core') {
                return 'text-[9px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-100/50 uppercase tracking-wider shrink-0';
            }
            return 'hidden';
        };

        $getBadgeText = function($status) {
            if ($status === 'core') return 'Wajib';
            return '';
        };
    @endphp

    <div class="space-y-6 animate-fade-in" x-data="{ agdFileName: '', simcFileName: '', simb1FileName: '', customFiles: {} }">
        <!-- Main Form Card Container -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border-2 border-slate-250 shadow-md shadow-slate-100/60 relative overflow-hidden">
            <!-- Decorative gradient banner top -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#003d7c] to-[#005fb8]"></div>

            <!-- Form Header Section (Matching Image 2 detail card header format) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-200">
                <div class="flex items-start gap-4">
                    <!-- Initial Monogram Logo (Matching card layout) -->
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#003d7c] to-[#005fb8] text-white flex items-center justify-center font-black text-2xl shadow-md shrink-0 uppercase tracking-widest">
                        <span>{{ substr($posting->title, 0, 2) }}</span>
                    </div>
                    <div class="flex-grow min-w-0">
                        <h3 class="text-xl md:text-2xl font-black text-slate-800 leading-snug">{{ $posting->title }}</h3>
                        <p class="text-xs font-extrabold text-[#003d7c] mt-1 uppercase tracking-wider">{{ $posting->category }}</p>
                        
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <!-- Location Badge -->
                            <span class="inline-flex items-center text-xs font-bold text-slate-600 bg-slate-50 border border-slate-300 px-3 py-1 rounded-xl">
                                <svg class="w-4 h-4 mr-1.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $posting->location_city ?? 'Seluruh Area (Bebas)' }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Back button on top-right -->
                <a href="{{ route('pelamar.lowongan') }}"
                    class="text-xs font-bold text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-4 py-2.5 rounded-xl transition-all border border-slate-200 shrink-0 text-center flex items-center justify-center gap-1.5 self-start sm:self-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            @if($isEdit)
                <div class="mb-6 p-4 rounded-2xl bg-blue-50 border border-blue-200 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#003d7c] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h5 class="text-xs font-extrabold text-[#003d7c] uppercase tracking-wider mb-1">Mode Pembaruan Lamaran</h5>
                        <p class="text-[11px] text-slate-650 leading-relaxed font-semibold">Anda telah melamar pada lowongan ini sebelumnya. Anda dapat memperbarui data kualifikasi atau berkas terunggah di bawah ini sampai waktu aktif loker habis. Sistem akan otomatis menghitung ulang nilai prioritas dan kecocokan Anda setelah data diperbarui.</p>
                    </div>
                </div>
            @endif

            <!-- Qualification Heading Block -->
            <div class="flex items-center justify-between pb-4 mb-6">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Kualifikasi & Bobot Penilaian
                </h4>
            </div>

            <!-- Main Interactive Form -->
            <form action="{{ route('pelamar.lowongan.store', $posting) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- 8-Card Interactive Qualification Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Card 1: Gender (Jenis Kelamin) -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('gender') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Gender</span>
                                    <span class="text-xs font-black text-slate-700 block">
                                        Syarat: 
                                        @if($genderVal === 'both')
                                            Pria & Wanita
                                        @elseif($genderVal === 'male')
                                            Pria
                                        @else
                                            Wanita
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($genderStatus) }}">{{ $getBadgeText($genderStatus) }}</span>
                        </div>
                        <div>
                            <!-- Read-only text input for UI -->
                            <input type="text" name="gender_disabled" value="{{ ($defaults['gender'] ?? '') === 'male' ? 'Pria' : 'Wanita' }}"
                                class="w-full px-4 py-2.5 rounded-xl border focus:outline-none text-sm text-slate-500 bg-slate-50 border-slate-200 cursor-not-allowed transition-all" disabled>
                            <!-- Hidden Input for Form Submission -->
                            <input type="hidden" name="gender" value="{{ old('gender', $defaults['gender'] ?? '') }}">
                            @error('gender')
                                <p class="text-[10px] text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
 
                    <!-- Card 2: Batas Usia (Tanggal Lahir) -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('birth_date') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Batas Usia</span>
                                    <span class="text-xs font-black text-slate-700 block">Syarat Usia: {{ $ageMin }} - {{ $ageMax }} thn</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($ageStatus) }}">{{ $getBadgeText($ageStatus) }}</span>
                        </div>
                        <div>
                            <!-- Read-only text input for UI -->
                            <input type="text" name="birth_date_disabled" value="{{ !empty($defaults['birth_date']) ? \Carbon\Carbon::parse($defaults['birth_date'])->format('d/m/Y') : '' }}"
                                class="w-full px-4 py-2.5 rounded-xl border focus:outline-none text-sm text-slate-500 bg-slate-50 border-slate-200 cursor-not-allowed transition-all" disabled>
                            <!-- Hidden Input for Form Submission -->
                            <input type="hidden" name="birth_date" value="{{ old('birth_date', $defaults['birth_date'] ?? '') }}">
                            @error('birth_date')
                                <p class="text-[10px] text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
 
                    <!-- Card 3: Pendidikan Terakhir -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('education_level') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Pendidikan</span>
                                    <span class="text-xs font-black text-slate-700 block">Syarat Jenjang: Min. {{ $educationVal }}</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($educationStatus) }}">{{ $getBadgeText($educationStatus) }}</span>
                        </div>
                        <div>
                            <!-- Read-only text input for UI -->
                            <input type="text" name="education_level_disabled" value="{{ $defaults['education_level'] ?? '' }}"
                                class="w-full px-4 py-2.5 rounded-xl border focus:outline-none text-sm text-slate-500 bg-slate-50 border-slate-200 cursor-not-allowed transition-all" disabled>
                            <!-- Hidden Input for Form Submission -->
                            <input type="hidden" name="education_level" value="{{ old('education_level', $defaults['education_level'] ?? '') }}">
                            @error('education_level')
                                <p class="text-[10px] text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if($majorStatus !== 'nonaktif')
                        <!-- Card 3B: Jurusan Pendidikan -->
                        <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('major') ? 'border-rose-400' : 'border-slate-300' }}">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 shrink-0">
                                        <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Jurusan</span>
                                        <span class="text-xs font-black text-slate-700 block">Kriteria: {{ $majorVal }}</span>
                                    </div>
                                </div>
                                <span class="{{ $getBadgeClass($majorStatus) }}">{{ $getBadgeText($majorStatus) }}</span>
                            </div>
                            <div>
                                <!-- Disabled Input for UI -->
                                <input type="text" name="major_disabled" value="{{ old('major', $defaults['major'] ?? '') }}" placeholder="Contoh: Keperawatan, Asisten Keperawatan"
                                    class="w-full px-4 py-2.5 rounded-xl border focus:outline-none text-sm text-slate-500 bg-slate-50 border-slate-200 cursor-not-allowed transition-all" disabled>
                                <!-- Hidden Input for Form Submission -->
                                <input type="hidden" name="major" value="{{ old('major', $defaults['major'] ?? '') }}">
                                @error('major')
                                    <p class="text-[10px] text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Card 4: Pengalaman Kerja (Tahun) -->
                    <div class="p-6 rounded-3xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all md:col-span-2 {{ $errors->has('experience_years') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-250 text-slate-500 shrink-0">
                                    <svg class="w-6 h-6 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-bold text-slate-800">
                                            Pengalaman Kerja / <span class="text-xs font-normal text-slate-500">Work Experience</span>
                                        </h3>
                                        <span class="{{ $getBadgeClass($experienceStatus) }}">{{ $getBadgeText($experienceStatus) }}</span>
                                    </div>
                                    <span class="text-xs font-bold text-slate-500 block mt-0.5">
                                        Syarat: 
                                        @if($experienceStatus !== 'nonaktif')
                                            Min. {{ $experienceVal }} tahun
                                        @else
                                            Tidak Dinilai
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            @if($experienceStatus !== 'nonaktif')
                                <label class="flex items-center cursor-pointer space-x-2.5 text-sm font-bold text-slate-700 select-none bg-slate-50 hover:bg-slate-100/80 border border-slate-300 px-4 py-2.5 rounded-xl transition-all">
                                    <input type="checkbox" id="has_experience" name="has_experience" value="1"
                                        class="w-4 h-4 rounded border-slate-350 hover:border-slate-400 text-[#003d7c] focus:ring-[#003d7c] focus:ring-offset-0 focus:ring-2 cursor-pointer"
                                        {{ old('has_experience', ($defaults['experience_years'] ?? 0) > 0 ? '1' : '') ? 'checked' : '' }}>
                                    <span>Saya memiliki pengalaman kerja / <span class="text-xs text-slate-500 font-normal">I have work experience</span></span>
                                </label>
                            @endif
                        </div>

                        <div>
                            @if($experienceStatus !== 'nonaktif')
                                <div class="space-y-4">
                                    <!-- Hidden experience_years which will be updated by JS -->
                                    <input type="hidden" id="experience_years" name="experience_years" value="{{ old('experience_years', $defaults['experience_years'] ?? 0) }}">
                                    
                                    <!-- Experience Container -->
                                    <div id="experience_container" class="{{ old('has_experience', ($defaults['experience_years'] ?? 0) > 0 ? '1' : '') ? '' : 'hidden' }} space-y-4 mt-2">
                                        <!-- Dynamic list -->
                                        <div class="experience-list space-y-4">
                                            @php
                                                $profileExps = auth()->user()->profile?->extras['experiences'] ?? [];
                                                $defaultPerusahaan = count($profileExps) > 0 ? array_column($profileExps, 'company') : [''];
                                                $defaultPosisi = count($profileExps) > 0 ? array_column($profileExps, 'position') : [''];
                                                $defaultTanggalMulai = count($profileExps) > 0 ? array_column($profileExps, 'start_date') : [''];
                                                $defaultTanggalSelesai = count($profileExps) > 0 ? array_column($profileExps, 'end_date') : [''];
                                                $defaultDeskripsi = count($profileExps) > 0 ? array_column($profileExps, 'description') : [''];

                                                $oldPerusahaan = old('perusahaan', $defaultPerusahaan);
                                                $oldPosisi = old('posisi', $defaultPosisi);
                                                $oldTanggalMulai = old('tanggal_mulai', $defaultTanggalMulai);
                                                $oldTanggalSelesai = old('tanggal_selesai', $defaultTanggalSelesai);
                                                $oldDeskripsi = old('deskripsi_pekerjaan', $defaultDeskripsi);
                                                $count = count($oldPerusahaan);
                                            @endphp
                                            @for($i = 0; $i < $count; $i++)
                                                <div class="experience-item relative p-6 bg-slate-50/50 border border-slate-300 rounded-2xl space-y-4 shadow-sm">
                                                    <!-- Delete button -->
                                                    <div class="absolute top-4 right-4 {{ $i === 0 ? 'hidden' : '' }} remove-exp-btn cursor-pointer text-slate-400 hover:text-rose-600 transition-colors"
                                                        title="Hapus Pengalaman / Remove Experience">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                                                Nama Pekerjaan / <span class="text-[11px] text-slate-500 font-normal">Job Title</span> <span class="text-rose-500">*</span>
                                                            </label>
                                                            <input name="posisi[]" type="text" value="{{ $oldPosisi[$i] ?? '' }}"
                                                                class="exp-input w-full px-4 py-2.5 rounded-xl border border-slate-350 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/10 focus:border-[#003d7c] text-sm text-slate-700 bg-white transition-all"
                                                                placeholder="Contoh / Example: Staff Administrasi"
                                                                {{ old('has_experience', ($defaults['experience_years'] ?? 0) > 0 ? '1' : '') ? 'required' : '' }}>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                                                Nama Perusahaan / <span class="text-[11px] text-slate-500 font-normal">Company Name</span> <span class="text-rose-500">*</span>
                                                            </label>
                                                            <input name="perusahaan[]" type="text" value="{{ $oldPerusahaan[$i] ?? '' }}"
                                                                class="exp-input w-full px-4 py-2.5 rounded-xl border border-slate-350 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/10 focus:border-[#003d7c] text-sm text-slate-700 bg-white transition-all"
                                                                placeholder="Nama Tempat Bekerja / Workplace Name"
                                                                {{ old('has_experience', ($defaults['experience_years'] ?? 0) > 0 ? '1' : '') ? 'required' : '' }}>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                                                Tanggal Mulai / <span class="text-[11px] text-slate-500 font-normal">Start Date</span> <span class="text-rose-500">*</span>
                                                            </label>
                                                            <input name="tanggal_mulai[]" type="month" value="{{ $oldTanggalMulai[$i] ?? '' }}" onclick="this.showPicker()"
                                                                class="exp-input w-full px-4 py-2.5 rounded-xl border border-slate-350 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/10 focus:border-[#003d7c] text-sm text-slate-700 bg-white transition-all cursor-pointer"
                                                                {{ old('has_experience', ($defaults['experience_years'] ?? 0) > 0 ? '1' : '') ? 'required' : '' }}>
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                                                Tanggal Selesai / <span class="text-[11px] text-slate-500 font-normal">End Date</span> <span class="text-rose-500">*</span>
                                                            </label>
                                                            <input name="tanggal_selesai[]" type="month" value="{{ $oldTanggalSelesai[$i] ?? '' }}" onclick="this.showPicker()"
                                                                class="exp-input w-full px-4 py-2.5 rounded-xl border border-slate-350 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/10 focus:border-[#003d7c] text-sm text-slate-700 bg-white transition-all cursor-pointer"
                                                                {{ old('has_experience', ($defaults['experience_years'] ?? 0) > 0 ? '1' : '') ? 'required' : '' }}>
                                                        </div>
                                                        <div class="md:col-span-2">
                                                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                                                Deskripsi Pekerjaan / <span class="text-[11px] text-slate-500 font-normal">Job Description</span> <span class="text-rose-500">*</span>
                                                            </label>
                                                            <textarea name="deskripsi_pekerjaan[]" rows="3"
                                                                class="exp-input w-full px-4 py-2.5 rounded-xl border border-slate-355 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/10 focus:border-[#003d7c] text-sm text-slate-700 bg-white transition-all"
                                                                placeholder="Jelaskan secara singkat tugas dan tanggung jawab Anda / Briefly explain your tasks and responsibilities"
                                                                {{ old('has_experience', ($defaults['experience_years'] ?? 0) > 0 ? '1' : '') ? 'required' : '' }}>{{ $oldDeskripsi[$i] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>

                                        <!-- Add Experience Button -->
                                        <button type="button" id="add_experience"
                                            class="w-full py-3.5 border-2 border-dashed border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 rounded-xl font-bold transition-all flex items-center justify-center gap-2 group text-xs mt-4">
                                            <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Tambah Pengalaman Kerja Lainnya
                                        </button>
                                    </div>

                                    @error('experience_years')
                                        <p class="text-[10px] text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" name="experience_years" value="{{ old('experience_years', $defaults['experience_years'] ?? 0) }}">
                                <div class="px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs text-slate-400 select-none">
                                    Kriteria pengalaman dinonaktifkan UCI
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($agdStatus !== 'nonaktif')
                    <!-- Card 5: Sertifikat AGD -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('agd_certificate') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 00.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Sertifikat AGD</span>
                                    <span class="text-xs font-black text-slate-700 block">Kualifikasi: Kepemilikan AGD</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($agdStatus) }}">{{ $getBadgeText($agdStatus) }}</span>
                        </div>
                        <div>
                            <div class="space-y-3">
                                <!-- Informational checkbox -->
                                <label class="flex items-center gap-2.5 text-xs text-slate-700 font-semibold cursor-pointer select-none pb-2 border-b border-slate-100">
                                    <input type="checkbox" id="has_agd" name="has_agd" value="1" class="rounded border-slate-350 hover:border-slate-400 text-[#003d7c] focus:ring-[#003d7c]/20" {{ old('has_agd', $defaults['has_agd'] ?? false) ? 'checked' : '' }}>
                                    <span>Saya menyatakan memiliki sertifikat AGD / <span class="text-[10px] text-slate-500 font-normal">I have AGD Certificate</span></span>
                                </label>

                                <!-- Upload Container -->
                                <div id="agd_upload_container" class="{{ old('has_agd', $defaults['has_agd'] ?? false) ? '' : 'hidden' }} space-y-3 mt-3 animate-fade-in">
                                    @if(!empty($defaults['agd_certificate_path']))
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                                            <div class="flex items-center gap-2 text-emerald-800 text-xs font-bold">
                                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Sertifikat AGD sudah terunggah</span>
                                            </div>
                                            <a href="{{ asset('storage/' . $defaults['agd_certificate_path']) }}" target="_blank" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[10px] rounded-lg transition-all shadow-sm">
                                                Lihat Berkas
                                            </a>
                                        </div>
                                    @endif

                                    <!-- High-fidelity Drag and Drop Box -->
                                    <div class="relative border-2 border-dashed rounded-2xl p-4 transition-all text-center bg-white group cursor-pointer hover:bg-slate-50/50 {{ $errors->has('agd_certificate') ? 'border-rose-400' : 'border-slate-300 hover:border-[#003d7c]' }}">
                                        <input type="file" name="agd_certificate" id="agd_certificate"
                                            @change="agdFileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                            {{ old('has_agd', $defaults['has_agd'] ?? false) && empty($defaults['agd_certificate_path']) ? 'required' : '' }}>
                                        <div class="space-y-1.5 pointer-events-none">
                                            <svg class="w-8 h-8 text-slate-400 group-hover:text-[#003d7c] transition-colors mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <div class="text-xs font-bold text-slate-700 group-hover:text-[#003d7c] transition-colors">
                                                <span x-text="agdFileName || '{{ !empty($defaults['agd_certificate_path']) ? 'Sudah Diunggah (Klik / seret untuk ganti)' : 'Pilih Berkas Sertifikat AGD' }}'"></span>
                                            </div>
                                            <p class="text-[9px] text-slate-400">PDF, JPG, PNG (Maks. 2MB)</p>
                                        </div>
                                    </div>
                                    @error('agd_certificate')
                                        <p class="text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                    
                                    <!-- Dynamic Preview Pill when selected -->
                                    <div x-show="agdFileName" class="flex justify-center select-none animate-fade-in" style="display: none;">
                                        <button type="button" onclick="previewFile('agd')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 text-[10px] font-black transition-all border border-blue-200 shadow-sm relative z-20">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Pratinjau Detail Berkas
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($simcStatus !== 'nonaktif')
                    <!-- Card 6: SIM C (Motor) -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('sim_c_photo') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">SIM C (Motor)</span>
                                    <span class="text-xs font-black text-slate-700 block">Kualifikasi: Kepemilikan SIM C</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($simcStatus) }}">{{ $getBadgeText($simcStatus) }}</span>
                        </div>
                        <div>
                            <div class="space-y-3">
                                @if(!empty($defaults['sim_c_path']))
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                                        <div class="flex items-center gap-2 text-emerald-800 text-xs font-bold">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Foto SIM C sudah terunggah</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $defaults['sim_c_path']) }}" target="_blank" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[10px] rounded-lg transition-all shadow-sm">
                                            Lihat Berkas
                                        </a>
                                    </div>
                                @endif

                                <!-- High-fidelity Drag and Drop Box -->
                                <div class="relative border-2 border-dashed rounded-2xl p-4 transition-all text-center bg-white group cursor-pointer hover:bg-slate-50/50 {{ $errors->has('sim_c_photo') ? 'border-rose-400' : 'border-slate-300 hover:border-[#003d7c]' }}">
                                    <input type="file" name="sim_c_photo" 
                                        @change="simcFileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        {{ empty($defaults['sim_c_path']) && $simcStatus === 'core' ? 'required' : '' }}>
                                    <div class="space-y-1.5 pointer-events-none">
                                        <svg class="w-8 h-8 text-slate-400 group-hover:text-[#003d7c] transition-colors mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="text-xs font-bold text-slate-700 group-hover:text-[#003d7c] transition-colors">
                                            <span x-text="simcFileName || '{{ !empty($defaults['sim_c_path']) ? 'Sudah Diunggah (Klik / seret untuk ganti)' : 'Unggah Foto SIM C' }}'"></span>
                                        </div>
                                        <p class="text-[9px] text-slate-400">PDF, JPG, PNG (Maks. 2MB)</p>
                                    </div>
                                </div>
                                @error('sim_c_photo')
                                    <p class="text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                                
                                <!-- Dynamic Preview Pill when selected -->
                                <div x-show="simcFileName" class="flex justify-center select-none animate-fade-in" style="display: none;">
                                    <button type="button" onclick="previewFile('sim_c')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 text-[10px] font-black transition-all border border-blue-200 shadow-sm relative z-20">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Pratinjau Detail Berkas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($simb1Status !== 'nonaktif')
                    <!-- Card 7: SIM B1 (Mobil Berat) -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('sim_b1_photo') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 011-1v-4a1 1 0 01.4-.8l3-2.25A1 1 0 0119 9v7a1 1 0 01-1 1h-1m-4-1a1 1 0 00-1-1H9a1 1 0 00-1 1v1a1 1 0 001 1h4a1 1 0 001-1v-1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">SIM B1 (Mobil Berat)</span>
                                    <span class="text-xs font-black text-slate-700 block">Kualifikasi: Kepemilikan SIM B1</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($simb1Status) }}">{{ $getBadgeText($simb1Status) }}</span>
                        </div>
                        <div>
                            <div class="space-y-3">
                                @if(!empty($defaults['sim_b1_path']))
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                                        <div class="flex items-center gap-2 text-emerald-800 text-xs font-bold">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Foto SIM B1 sudah terunggah</span>
                                        </div>
                                        <a href="{{ asset('storage/' . $defaults['sim_b1_path']) }}" target="_blank" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[10px] rounded-lg transition-all shadow-sm">
                                            Lihat Berkas
                                        </a>
                                    </div>
                                @endif

                                <!-- High-fidelity Drag and Drop Box -->
                                <div class="relative border-2 border-dashed rounded-2xl p-4 transition-all text-center bg-white group cursor-pointer hover:bg-slate-50/50 {{ $errors->has('sim_b1_photo') ? 'border-rose-400' : 'border-slate-300 hover:border-[#003d7c]' }}">
                                    <input type="file" name="sim_b1_photo" 
                                        @change="simb1FileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        {{ empty($defaults['sim_b1_path']) && $simb1Status === 'core' ? 'required' : '' }}>
                                    <div class="space-y-1.5 pointer-events-none">
                                        <svg class="w-8 h-8 text-slate-400 group-hover:text-[#003d7c] transition-colors mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="text-xs font-bold text-slate-700 group-hover:text-[#003d7c] transition-colors">
                                            <span x-text="simb1FileName || '{{ !empty($defaults['sim_b1_path']) ? 'Sudah Diunggah (Klik / seret untuk ganti)' : 'Unggah Foto SIM B1' }}'"></span>
                                        </div>
                                        <p class="text-[9px] text-slate-400">PDF, JPG, PNG (Maks. 2MB)</p>
                                    </div>
                                </div>
                                @error('sim_b1_photo')
                                    <p class="text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                                
                                <!-- Dynamic Preview Pill when selected -->
                                <div x-show="simb1FileName" class="flex justify-center select-none animate-fade-in" style="display: none;">
                                    <button type="button" onclick="previewFile('sim_b1')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 text-[10px] font-black transition-all border border-blue-200 shadow-sm relative z-20">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Pratinjau Detail Berkas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($placementChoicesStatus !== 'nonaktif' && count($placementChoicesArray) > 0)
                        <!-- Card 8B: Opsi Lokasi Penempatan -->
                        <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('placement_choice') ? 'border-rose-400' : 'border-slate-300' }}">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                        <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Wilayah Kerja</span>
                                        <span class="text-xs font-black text-slate-700 block">Pilihan Penempatan</span>
                                    </div>
                                </div>
                                <span class="{{ $getBadgeClass($placementChoicesStatus) }}">{{ $getBadgeText($placementChoicesStatus) }}</span>
                            </div>
                            <div>
                                <!-- Read-only text input for UI -->
                                <input type="text" name="placement_choice_disabled" value="{{ old('placement_choice', $defaults['placement_choice'] ?? '') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border focus:outline-none text-sm text-slate-500 bg-slate-50 border-slate-200 cursor-not-allowed transition-all" disabled>
                                <!-- Hidden Input for Form Submission -->
                                <input type="hidden" name="placement_choice" value="{{ old('placement_choice', $defaults['placement_choice'] ?? '') }}">
                                @error('placement_choice')
                                    <p class="text-[10px] text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if($placementType === 'specific')
                        <!-- Card 8: Lokasi Penempatan Spesifik -->
                        <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                        <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Wilayah Kerja</span>
                                        <span class="text-xs font-black text-slate-700 block">Penempatan Spesifik</span>
                                    </div>
                                </div>
                                <span class="{{ $getBadgeClass($placementStatus) }}">{{ $getBadgeText($placementStatus) }}</span>
                            </div>
                            <div>
                                <input type="text" value="{{ $posting->location_city }}"
                                    class="w-full px-4 py-2.5 rounded-xl border focus:outline-none text-sm text-slate-500 bg-slate-50 border-slate-200 cursor-not-allowed transition-all font-semibold" disabled>
                                <input type="hidden" name="placement_ready" value="1">
                                <p class="text-[10px] text-slate-400 mt-1">Sistem mencocokkan kota domisili profil Anda dengan kota penempatan kerja.</p>
                            </div>
                        </div>
                    @else
                        <!-- Card 8: Penempatan (Siap Ditempatkan) -->
                        <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('placement_ready') ? 'border-rose-400' : 'border-slate-300' }}">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                        <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Penempatan</span>
                                        <span class="text-xs font-black text-slate-700 block">Kesiapan: {{ $posting->category === 'Gardener' ? 'Area Kota Tangerang' : 'Seluruh Area Kerja' }}</span>
                                    </div>
                                </div>
                                <span class="{{ $getBadgeClass($effectivePlacementStatus) }}">{{ $getBadgeText($effectivePlacementStatus) }}</span>
                            </div>
                            <div>
                                @if($effectivePlacementStatus !== 'nonaktif')
                                    <div class="space-y-1">
                                        <label class="flex items-start gap-3 p-3 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer select-none">
                                            <input type="checkbox" name="placement_ready" value="1" class="mt-0.5 rounded border-slate-350 hover:border-slate-400 text-blue-600 focus:ring-blue-500/20" {{ old('placement_ready', $defaults['placement_ready'] ?? false) ? 'checked' : '' }} required>
                                            <div class="min-w-0">
                                                <strong class="text-slate-800 font-bold text-xs block mb-0.5">{{ $posting->category === 'Gardener' ? 'Kesiapan Penempatan Area Kota Tangerang' : 'Kesiapan Penempatan Kerja UCI' }}</strong>
                                                <span class="text-[10px] text-slate-500 block leading-normal">{{ $posting->category === 'Gardener' ? 'Saya bersedia dan siap untuk ditugaskan di Area Kota Tangerang.' : 'Saya bersedia dan siap untuk ditugaskan di seluruh area operasional UCI.' }}</span>
                                            </div>
                                        </label>
                                        @error('placement_ready')
                                            <p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="placement_ready" value="{{ old('placement_ready', '1') }}">
                                    <div class="px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs text-slate-400 select-none">
                                        Persyaratan penempatan otomatis disetujui
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($medicalSupportStatus !== 'nonaktif')
                    <!-- Card: Menguasai Kebutuhan Penunjang Medis -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('medical_support') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Penunjang Medis</span>
                                    <span class="text-xs font-black text-slate-700 block">Kriteria: Penunjang Medis</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($medicalSupportStatus) }}">{{ $getBadgeText($medicalSupportStatus) }}</span>
                        </div>
                        <div>
                            <div class="space-y-1">
                                <label class="flex items-start gap-3 p-3 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer select-none">
                                    <input type="checkbox" name="medical_support" value="1" class="mt-0.5 rounded border-slate-350 hover:border-slate-400 text-blue-600 focus:ring-blue-500/20" {{ $medicalSupportChecked ? 'checked' : '' }} {{ $medicalSupportStatus === 'core' ? 'required' : '' }}>
                                    <div class="min-w-0">
                                        <strong class="text-slate-800 font-bold text-xs block mb-0.5">Menguasai Kebutuhan Penunjang Medis</strong>
                                        <span class="text-[10px] text-slate-500 block leading-normal">Saya menguasai kebutuhan Penunjang Medis yang dibutuhkan pasien atau perawat.</span>
                                    </div>
                                </label>
                                @error('medical_support')
                                    <p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($medicalTermsStatus !== 'nonaktif')
                    <!-- Card: Mengetahui Istilah-Istilah Medis -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('medical_terms') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Istilah Medis</span>
                                    <span class="text-xs font-black text-slate-700 block">Kriteria: Pemahaman Istilah Medis</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($medicalTermsStatus) }}">{{ $getBadgeText($medicalTermsStatus) }}</span>
                        </div>
                        <div>
                            <div class="space-y-1">
                                <label class="flex items-start gap-3 p-3 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer select-none">
                                    <input type="checkbox" name="medical_terms" value="1" class="mt-0.5 rounded border-slate-350 hover:border-slate-400 text-blue-600 focus:ring-blue-500/20" {{ $medicalTermsChecked ? 'checked' : '' }} {{ $medicalTermsStatus === 'core' ? 'required' : '' }}>
                                    <div class="min-w-0">
                                        <strong class="text-slate-800 font-bold text-xs block mb-0.5">Mengetahui Istilah-Istilah dalam Medis</strong>
                                        <span class="text-[10px] text-slate-500 block leading-normal">Saya mengetahui istilah-istilah dalam medis secara baik.</span>
                                    </div>
                                </label>
                                @error('medical_terms')
                                    <p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($gardenerTechStatus !== 'nonaktif')
                    <!-- Card: Memahami Teknis Pertumbuhan Tanaman -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('gardener_tech_understanding') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M12 8c-3-3-8-2-8 3 0 4 5 5 8 5m0-8c3-3 8-2 8 3 0 4-5 5-8 5" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Pertumbuhan Tanaman</span>
                                    <span class="text-xs font-black text-slate-700 block">Kriteria: Teknis Pertumbuhan Tanaman</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($gardenerTechStatus) }}">{{ $getBadgeText($gardenerTechStatus) }}</span>
                        </div>
                        <div>
                            <div class="space-y-1">
                                <label class="flex items-start gap-3 p-3 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer select-none">
                                    <input type="checkbox" name="gardener_tech_understanding" value="1" class="mt-0.5 rounded border-slate-350 hover:border-slate-400 text-blue-600 focus:ring-blue-500/20" {{ $gardenerTechChecked ? 'checked' : '' }} {{ $gardenerTechStatus === 'core' ? 'required' : '' }}>
                                    <div class="min-w-0">
                                        <strong class="text-slate-800 font-bold text-xs block mb-0.5">Memahami Teknis Pertumbuhan Tanaman</strong>
                                        <span class="text-[10px] text-slate-500 block leading-normal">Saya memahami teknis pertumbuhan tanaman dengan baik.</span>
                                    </div>
                                </label>
                                @error('gardener_tech_understanding')
                                    <p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($gardenerNurseryStatus !== 'nonaktif')
                    <!-- Card: Mampu Mengelola Pembibitan Tanaman -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('gardener_nursery_skill') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V9m0 0L8 5m4 4l4-4m-7 8h6" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Pembibitan Tanaman</span>
                                    <span class="text-xs font-black text-slate-700 block">Kriteria: Pembibitan Tanaman</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($gardenerNurseryStatus) }}">{{ $getBadgeText($gardenerNurseryStatus) }}</span>
                        </div>
                        <div>
                            <div class="space-y-1">
                                <label class="flex items-start gap-3 p-3 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer select-none">
                                    <input type="checkbox" name="gardener_nursery_skill" value="1" class="mt-0.5 rounded border-slate-350 hover:border-slate-400 text-blue-600 focus:ring-blue-500/20" {{ $gardenerNurseryChecked ? 'checked' : '' }} {{ $gardenerNurseryStatus === 'core' ? 'required' : '' }}>
                                    <div class="min-w-0">
                                        <strong class="text-slate-800 font-bold text-xs block mb-0.5">Mampu Mengelola Pembibitan Tanaman</strong>
                                        <span class="text-[10px] text-slate-500 block leading-normal">Saya memiliki kemampuan untuk mengelola pembibitan tanaman dengan baik.</span>
                                    </div>
                                </label>
                                @error('gardener_nursery_skill')
                                    <p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($gardenerToolsStatus !== 'nonaktif')
                    <!-- Card: Menguasai Skill Penggunaan Alat-Alat Teknis -->
                    <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('gardener_tools_skill') ? 'border-rose-400' : 'border-slate-300' }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                    <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">Alat Teknis</span>
                                    <span class="text-xs font-black text-slate-700 block">Kriteria: Alat Teknis Gardener</span>
                                </div>
                            </div>
                            <span class="{{ $getBadgeClass($gardenerToolsStatus) }}">{{ $getBadgeText($gardenerToolsStatus) }}</span>
                        </div>
                        <div>
                            <div class="space-y-1">
                                <label class="flex items-start gap-3 p-3 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer select-none">
                                    <input type="checkbox" name="gardener_tools_skill" value="1" class="mt-0.5 rounded border-slate-350 hover:border-slate-400 text-blue-600 focus:ring-blue-500/20" {{ $gardenerToolsChecked ? 'checked' : '' }} {{ $gardenerToolsStatus === 'core' ? 'required' : '' }}>
                                    <div class="min-w-0">
                                        <strong class="text-slate-800 font-bold text-xs block mb-0.5">Menguasai Skill Penggunaan Alat-Alat Teknis</strong>
                                        <span class="text-[10px] text-slate-500 block leading-normal">Saya menguasai penggunaan alat-alat teknis untuk pertamanan dengan baik.</span>
                                    </div>
                                </label>
                                @error('gardener_tools_skill')
                                    <p class="text-[10px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    @foreach($customDocsConfig as $doc)
                        @php
                            $key = $doc['key'];
                            $label = $doc['label'];
                            $status = $doc['status'];
                            $existingPath = $defaults['additional_documents'][$key] ?? null;
                        @endphp
                        <div class="p-5 rounded-2xl border bg-white shadow-sm flex flex-col justify-between hover:border-[#003d7c]/30 hover:shadow-md transition-all {{ $errors->has('custom_doc_' . $key) ? 'border-rose-400' : 'border-slate-300' }}">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 shrink-0">
                                        <svg class="w-4 h-4 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block">{{ $label }}</span>
                                        <span class="text-xs font-black text-slate-700 block">Syarat: Berkas Pendukung</span>
                                    </div>
                                </div>
                                <span class="{{ $getBadgeClass($status) }}">{{ $getBadgeText($status) }}</span>
                            </div>
                            <div>
                                <div class="space-y-3">
                                    @if(!empty($existingPath))
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                                            <div class="flex items-center gap-2 text-emerald-800 text-xs font-bold">
                                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>Berkas sudah terunggah</span>
                                            </div>
                                            <a href="{{ asset('storage/' . $existingPath) }}" target="_blank" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[10px] rounded-lg transition-all shadow-sm">
                                                Lihat Berkas
                                            </a>
                                        </div>
                                    @endif

                                    <!-- High-fidelity Drag and Drop Box -->
                                    <div class="relative border-2 border-dashed rounded-2xl p-4 transition-all text-center bg-white group cursor-pointer hover:bg-slate-50/50 {{ $errors->has('custom_doc_' . $key) ? 'border-rose-400' : 'border-slate-300 hover:border-[#003d7c]' }}">
                                        <input type="file" name="custom_doc_{{ $key }}" 
                                            @change="customFiles['{{ $key }}'] = $event.target.files[0] ? $event.target.files[0].name : ''"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                            {{ empty($existingPath) && $status === 'core' ? 'required' : '' }}>
                                        <div class="space-y-1.5 pointer-events-none">
                                            <svg class="w-8 h-8 text-slate-400 group-hover:text-[#003d7c] transition-colors mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 00-2 2z"></path>
                                            </svg>
                                            <div class="text-xs font-bold text-slate-700 group-hover:text-[#003d7c] transition-colors">
                                                <span x-text="customFiles['{{ $key }}'] || '{{ !empty($existingPath) ? 'Sudah Diunggah (Klik / seret untuk ganti)' : 'Unggah Berkas ' . $label }}'"></span>
                                            </div>
                                            <p class="text-[9px] text-slate-400">PDF, JPG, PNG (Maks. 2MB)</p>
                                        </div>
                                    </div>
                                    @error('custom_doc_' . $key)
                                        <p class="text-[10px] text-rose-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Footer Section (Submit & Deadline) -->
                <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="text-[10px] text-slate-400 font-bold flex items-center select-none">
                        <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Aktif Sampai: <span class="text-slate-500 font-black ml-1">{{ $posting->active_until ? $posting->active_until->format('d M Y') : 'Selamanya' }}</span>
                    </span>
                    <button type="submit"
                                                        class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-[#003d7c] to-[#005fb8] text-white text-xs font-black shadow-md shadow-blue-900/10 hover:shadow-blue-900/25 hover:brightness-105 active:scale-95 transition-all uppercase tracking-widest text-center">
                                                        {{ $isEdit ? 'Perbarui Lamaran' : 'Lamar Pekerjaan Ini' }}
                                                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reusable Premium File Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity animate-fade-in" onclick="closePreviewModal()"></div>

        <!-- Modal Content Container -->
        <div class="bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-200 max-w-3xl w-full mx-4 relative z-10 transform transition-all flex flex-col max-h-[85vh] animate-scale-up">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <h3 class="text-sm font-bold text-slate-800" id="previewModalTitle">Pratinjau Detail Berkas</h3>
                </div>
                <button type="button" onclick="closePreviewModal()" class="text-slate-400 hover:text-slate-650 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto flex-grow flex items-center justify-center bg-slate-50 min-h-[300px]" id="previewModalBody">
                <!-- Content will be dynamically inserted here by JS -->
            </div>
            
            <!-- Footer -->
            <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
                <span class="text-[10px] text-slate-500 font-bold" id="previewModalSize">Ukuran Berkas: -</span>
                <button type="button" onclick="closePreviewModal()" class="px-4 py-2 bg-[#003d7c] hover:bg-[#005fb8] text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Client-side Dynamic Experience & Preview Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxExp = document.getElementById('has_experience');
            const containerExp = document.getElementById('experience_container');
            const btnAddExp = document.getElementById('add_experience');
            const experienceList = document.querySelector('.experience-list');

            function toggleRequired(container, isRequired) {
                const inputs = container.querySelectorAll('.exp-input');
                inputs.forEach(input => {
                    if (isRequired) {
                        input.setAttribute('required', 'required');
                    } else {
                        input.removeAttribute('required');
                    }
                });
            }

            if (checkboxExp && containerExp) {
                checkboxExp.addEventListener('change', function () {
                    if (this.checked) {
                        containerExp.classList.remove('hidden');
                        toggleRequired(containerExp, true);
                    } else {
                        containerExp.classList.add('hidden');
                        toggleRequired(containerExp, false);
                    }
                });
            }

            // Tambah Pengalaman Baru
            if (btnAddExp && experienceList) {
                btnAddExp.addEventListener('click', function () {
                    const expItems = experienceList.querySelectorAll('.experience-item');
                    if (expItems.length > 0) {
                        const firstExp = expItems[0];
                        const clone = firstExp.cloneNode(true);

                        // Kosongkan value input dan textarea
                        const inputs = clone.querySelectorAll('.exp-input');
                        inputs.forEach(input => {
                            input.value = '';
                            input.classList.remove('border-rose-400');
                            input.classList.add('border-slate-350');
                        });

                        // Tampilkan & fungsikan tombol hapus
                        const removeBtn = clone.querySelector('.remove-exp-btn');
                        if (removeBtn) {
                            removeBtn.classList.remove('hidden');
                            removeBtn.addEventListener('click', function () {
                                clone.remove();
                            });
                        }

                        // Sisipkan ke list
                        experienceList.appendChild(clone);
                        toggleRequired(clone, true);
                    }
                });
            }

            // Handler Hapus untuk item inisial hasil Blade render old()
            if (experienceList) {
                const initialItems = experienceList.querySelectorAll('.experience-item');
                initialItems.forEach((item, index) => {
                    if (index > 0) {
                        const removeBtn = item.querySelector('.remove-exp-btn');
                        if (removeBtn) {
                            removeBtn.classList.remove('hidden');
                            removeBtn.addEventListener('click', function () {
                                item.remove();
                            });
                        }
                    }
                });
            }

            // Toggle upload Sertifikat AGD berdasarkan checkbox
            const checkboxAgd = document.getElementById('has_agd');
            const containerAgd = document.getElementById('agd_upload_container');
            const inputAgd = document.querySelector('input[name="agd_certificate"]');
            const hasExistingAgd = {{ ($defaults['agd_certificate_path'] ?? null) ? 'true' : 'false' }};

            if (checkboxAgd && containerAgd) {
                checkboxAgd.addEventListener('change', function () {
                    if (this.checked) {
                        containerAgd.classList.remove('hidden');
                        if (inputAgd && !hasExistingAgd) {
                            inputAgd.setAttribute('required', 'required');
                        }
                    } else {
                        containerAgd.classList.add('hidden');
                        if (inputAgd) {
                            inputAgd.removeAttribute('required');
                            inputAgd.value = '';
                            // Dispatch event to clear Alpine filename
                            const event = new Event('change');
                            inputAgd.dispatchEvent(event);
                        }
                    }
                });
            }
        });

        // Global preview modal handler
        let activeObjectURL = null;

        function previewFile(type) {
            let inputName = '';
            let title = 'Pratinjau Detail Berkas';
            
            if (type === 'agd') {
                inputName = 'agd_certificate';
                title = 'Pratinjau Sertifikat AGD';
            } else if (type === 'sim_c') {
                inputName = 'sim_c_photo';
                title = 'Pratinjau Foto SIM C';
            } else if (type === 'sim_b1') {
                inputName = 'sim_b1_photo';
                title = 'Pratinjau Foto SIM B1';
            }

            const input = document.querySelector(`input[name="${inputName}"]`);
            if (!input || !input.files || input.files.length === 0) {
                alert('Silakan pilih berkas terlebih dahulu.');
                return;
            }

            const file = input.files[0];
            const sizeStr = formatBytes(file.size);
            
            // Clean up any existing active object URL
            if (activeObjectURL) {
                URL.revokeObjectURL(activeObjectURL);
            }

            activeObjectURL = URL.createObjectURL(file);

            const modal = document.getElementById('previewModal');
            const modalTitle = document.getElementById('previewModalTitle');
            const modalBody = document.getElementById('previewModalBody');
            const modalSize = document.getElementById('previewModalSize');

            modalTitle.innerText = title + ' - ' + file.name;
            modalSize.innerText = 'Ukuran Berkas: ' + sizeStr;

            // Clear body
            modalBody.innerHTML = '';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = activeObjectURL;
                img.className = 'max-w-full max-h-[60vh] rounded-2xl shadow-md border border-slate-200 object-contain';
                modalBody.appendChild(img);
            } else if (file.type === 'application/pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = activeObjectURL;
                iframe.className = 'w-full h-[60vh] rounded-2xl border border-slate-200';
                iframe.frameBorder = '0';
                modalBody.appendChild(iframe);
            } else {
                // Fallback for other file types
                const fallbackContainer = document.createElement('div');
                fallbackContainer.className = 'text-center p-8 space-y-4';
                fallbackContainer.innerHTML = `
                    <svg class="w-16 h-16 text-slate-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-xs font-bold text-slate-600">Format file ini tidak dapat dipratinjau langsung.</p>
                    <a href="${activeObjectURL}" download="${file.name}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">Unduh untuk melihat</a>
                `;
                modalBody.appendChild(fallbackContainer);
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Lock background scroll
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Unlock scroll

            if (activeObjectURL) {
                URL.revokeObjectURL(activeObjectURL);
                activeObjectURL = null;
            }
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Close on Escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePreviewModal();
            }
        });
    </script>
@endsection
