@extends('layouts.dashboard')

@section('dashboard-title', 'Buat Lowongan')

@section('dashboard-content')
    <div class="space-y-6 animate-fade-in">
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <!-- Decorative gradient banner top -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Buat Lowongan Baru</h3>
                    <p class="text-xs text-slate-500 mt-1">Konfigurasikan detail lowongan dan kriteria SPK (Core vs Secondary Factor).</p>
                </div>
                <a href="{{ route('hrd.hiring') }}"
                    class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-100">
                    Kembali
                </a>
            </div>

            <form action="{{ route('hrd.hiring.store') }}" method="POST" class="space-y-8" x-data="{
                category: '{{ old('category', '') }}',
                genderStatus: '{{ old('req_gender_status', 'core') }}',
                ageStatus: '{{ old('req_age_status', 'core') }}',
                educationStatus: '{{ old('req_education_status', 'core') }}',
                agdStatus: '{{ old('req_agd_status', 'secondary') }}',
                simcStatus: '{{ old('req_sim_c_status', 'secondary') }}',
                simb1Status: '{{ old('req_sim_b1_status', 'secondary') }}',
                experienceStatus: '{{ old('req_experience_status', 'secondary') }}',
                placementStatus: '{{ old('req_placement_ready_status', 'core') }}',
                placementType: '{{ old('req_placement_type', 'anywhere') }}',
                majorStatus: '{{ old('req_major_status', 'nonaktif') }}',
                placementChoicesStatus: '{{ old('req_placement_choices_status', 'nonaktif') }}',
                medicalSupportStatus: '{{ old('req_medical_support_status', 'nonaktif') }}',
                medicalTermsStatus: '{{ old('req_medical_terms_status', 'nonaktif') }}',
                salaryHidden: {{ old('salary_hidden') ? 'true' : 'false' }},
                customDocs: [],
                slugify(text) {
                    if (!text) return '';
                    return text
                        .toString()
                        .toLowerCase()
                        .trim()
                        .replace(/\s+/g, '_')
                        .replace(/[^\w\-]+/g, '')
                        .replace(/\-\-+/g, '-');
                },
                init() {
                    this.$watch('category', val => {
                        if (val === 'Asisten Keperawatan') {
                            this.genderStatus = 'core';
                            document.getElementsByName('req_gender_value')[0].value = 'male';
                            this.ageStatus = 'core';
                            document.getElementsByName('req_age_min')[0].value = 25;
                            this.educationStatus = 'core';
                            document.getElementsByName('req_education_value')[0].value = 'SMA/SMK';
                            this.agdStatus = 'nonaktif';
                            this.simcStatus = 'nonaktif';
                            this.simb1Status = 'nonaktif';
                            this.placementStatus = 'core';
                            this.experienceStatus = 'secondary';
                            document.getElementsByName('req_experience_value')[0].value = 0;
                            this.majorStatus = 'core';
                            this.placementChoicesStatus = 'nonaktif';
                            this.medicalSupportStatus = 'nonaktif';
                            this.medicalTermsStatus = 'nonaktif';
                            this.customDocs = [
                                { key: 'str_file', label: 'Surat Tanda Registrasi (STR) / STRTK', status: 'core' },
                                { key: 'sertifikat_kompetensi', label: 'Sertifikat Kompetensi Keperawatan', status: 'core' }
                            ];
                            document.getElementsByName('title')[0].value = 'Asisten Keperawatan';
                        } else if (val === 'Driver Ambulance') {
                            this.genderStatus = 'core';
                            document.getElementsByName('req_gender_value')[0].value = 'male';
                            this.ageStatus = 'core';
                            document.getElementsByName('req_age_min')[0].value = 25;
                            document.getElementsByName('req_age_max')[0].value = 35;
                            this.educationStatus = 'core';
                            document.getElementsByName('req_education_value')[0].value = 'SMA/SMK';
                            this.agdStatus = 'nonaktif';
                            this.simcStatus = 'nonaktif';
                            this.simb1Status = 'nonaktif';
                            this.placementStatus = 'core';
                            this.experienceStatus = 'secondary';
                            document.getElementsByName('req_experience_value')[0].value = 0;
                            this.majorStatus = 'nonaktif';
                            this.placementChoicesStatus = 'nonaktif';
                            this.medicalSupportStatus = 'nonaktif';
                            this.medicalTermsStatus = 'nonaktif';
                            this.customDocs = [
                                { key: 'sertifikat_agd_ambulance', label: 'Sertifikat AGD (Ambulance)', status: 'secondary' },
                                { key: 'lisensi_sim_c_motor', label: 'Lisensi SIM C (Motor)', status: 'secondary' },
                                { key: 'lisensi_sim_b1_mobil_berat', label: 'Lisensi SIM B1 (Mobil Berat)', status: 'core' }
                            ];
                            document.getElementsByName('title')[0].value = 'Driver Ambulance';
                        } else if (val === 'Cleaning Service') {
                            this.genderStatus = 'core';
                            document.getElementsByName('req_gender_value')[0].value = 'male';
                            this.ageStatus = 'core';
                            document.getElementsByName('req_age_min')[0].value = 25;
                            document.getElementsByName('req_age_max')[0].value = 65;
                            this.educationStatus = 'core';
                            document.getElementsByName('req_education_value')[0].value = 'SMA/SMK';
                            this.agdStatus = 'nonaktif';
                            this.simcStatus = 'nonaktif';
                            this.simb1Status = 'nonaktif';
                            this.placementStatus = 'core';
                            this.experienceStatus = 'core';
                            document.getElementsByName('req_experience_value')[0].value = 0;
                            this.majorStatus = 'nonaktif';
                            this.placementChoicesStatus = 'secondary';
                            document.getElementsByName('req_placement_choices_value')[0].value = 'Jakarta Barat';
                            this.medicalSupportStatus = 'nonaktif';
                            this.medicalTermsStatus = 'nonaktif';
                            this.customDocs = [
                                { key: 'sim_c_aktif', label: 'SIM C Aktif', status: 'secondary' }
                            ];
                            document.getElementsByName('title')[0].value = 'Cleaning Service';
                        } else if (val === 'Runner') {
                            this.genderStatus = 'core';
                            document.getElementsByName('req_gender_value')[0].value = 'male';
                            this.ageStatus = 'core';
                            document.getElementsByName('req_age_min')[0].value = 23;
                            document.getElementsByName('req_age_max')[0].value = 35;
                            this.educationStatus = 'core';
                            document.getElementsByName('req_education_value')[0].value = 'SMA/SMK';
                            this.agdStatus = 'nonaktif';
                            this.simcStatus = 'nonaktif';
                            this.simb1Status = 'nonaktif';
                            this.placementStatus = 'secondary';
                            this.experienceStatus = 'core';
                            document.getElementsByName('req_experience_value')[0].value = 0;
                            this.majorStatus = 'core';
                            document.getElementsByName('req_major_value')[0].value = 'Kesehatan, Umum';
                            this.placementChoicesStatus = 'nonaktif';
                            this.medicalSupportStatus = 'secondary';
                            this.medicalTermsStatus = 'secondary';
                            this.customDocs = [];
                            document.getElementsByName('title')[0].value = 'Runner';
                        }
                    });
                }
            }">
                @csrf
                
                <!-- Section 1: Informasi Dasar -->
                <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100 space-y-4">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200/55 pb-2">Informasi Dasar Lowongan</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-600">Judul Lowongan</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Driver Medis"
                                class="w-full mt-2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition-all" required>
                            @error('title')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-600">Kategori Pekerjaan</label>
                            <select name="category" id="category-select" x-model="category"
                                class="w-full mt-2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 text-sm transition-all" required>
                                <option value="" @selected(old('category') === null || old('category') === '')>-- Pilih Kategori Pekerjaan --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected(old('category') === $cat)>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-600">Deskripsi Lowongan (Opsional)</label>
                        <textarea name="description" rows="3"
                            class="w-full mt-2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 text-sm transition-all">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Section 2: Kriteria Persyaratan (Dynamic & Simplified) -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#003d7c] flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-800">Persyaratan Kualifikasi Jabatan</h4>
                            <p class="text-xs text-slate-505">Tentukan kualifikasi kandidat. Sesuaikan status persyaratan (Wajib vs Tambahan) dengan klik tombol pill di setiap kriteria.</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- 1. Gender (Semua Posisi) -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Jenis Kelamin
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="genderStatus = 'core'" :class="genderStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="genderStatus = 'secondary'" :class="genderStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="genderStatus = 'nonaktif'" :class="genderStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_gender_status" :value="genderStatus">
                            </div>
                            <select name="req_gender_value" :disabled="genderStatus === 'nonaktif'" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 transition-all" :class="genderStatus === 'nonaktif' ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-white text-slate-800'">
                                <option value="male" @selected(old('req_gender_value') === 'male')>Pria saja</option>
                                <option value="female" @selected(old('req_gender_value') === 'female')>Wanita saja</option>
                                <option value="both" @selected(old('req_gender_value') === 'both')>Pria dan Wanita</option>
                            </select>
                        </div>

                        <!-- 2. Pendidikan Minimal (Semua Posisi) -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pendidikan Minimal
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="educationStatus = 'core'" :class="educationStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="educationStatus = 'secondary'" :class="educationStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="educationStatus = 'nonaktif'" :class="educationStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_education_status" :value="educationStatus">
                            </div>
                            <select name="req_education_value" :disabled="educationStatus === 'nonaktif'" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 transition-all" :class="educationStatus === 'nonaktif' ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-white text-slate-800'">
                                @foreach($educationLevels as $level)
                                    <option value="{{ $level }}" @selected(old('req_education_value', 'SMA/SMK') === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. Usia Minimum & Maksimum (Semua Posisi) -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Batasan Usia
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="ageStatus = 'core'" :class="ageStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="ageStatus = 'secondary'" :class="ageStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="ageStatus = 'nonaktif'" :class="ageStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_age_status" :value="ageStatus">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center gap-1 bg-white rounded-xl border border-slate-200 px-3 py-2 text-xs focus-within:border-blue-500 transition-all" :class="ageStatus === 'nonaktif' ? 'bg-slate-50 text-slate-400 border-slate-105 shadow-none' : 'bg-white'">
                                    <span class="text-slate-400">Min:</span>
                                    <input type="number" name="req_age_min" :disabled="ageStatus === 'nonaktif'" value="{{ old('req_age_min', 25) }}" min="18" max="60" class="w-full bg-transparent focus:outline-none font-bold" :class="ageStatus === 'nonaktif' ? 'text-slate-400' : 'text-slate-800'">
                                </div>
                                <div class="flex items-center gap-1 bg-white rounded-xl border border-slate-200 px-3 py-2 text-xs focus-within:border-blue-500 transition-all" :class="ageStatus === 'nonaktif' ? 'bg-slate-50 text-slate-400 border-slate-105 shadow-none' : 'bg-white'">
                                    <span class="text-slate-400">Max:</span>
                                    <input type="number" name="req_age_max" :disabled="ageStatus === 'nonaktif'" value="{{ old('req_age_max', 35) }}" min="18" max="65" class="w-full bg-transparent focus:outline-none font-bold" :class="ageStatus === 'nonaktif' ? 'text-slate-400' : 'text-slate-800'">
                                </div>
                            </div>
                        </div>

                        <!-- 4. Pengalaman Kerja (Semua Posisi) -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Pengalaman Minimum (Tahun)
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="experienceStatus = 'core'" :class="experienceStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="experienceStatus = 'secondary'" :class="experienceStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="experienceStatus = 'nonaktif'" :class="experienceStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_experience_status" :value="experienceStatus">
                            </div>
                            <input type="number" name="req_experience_value" :disabled="experienceStatus === 'nonaktif'" value="{{ old('req_experience_value', 0) }}" min="0" max="50" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 transition-all" :class="experienceStatus === 'nonaktif' ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-white text-slate-800'">
                        </div>

                        <!-- ==================== NURSE ONLY FIELDS ==================== -->
                        <!-- 9. Jurusan Pendidikan (Asisten Keperawatan saja) -->
                        <div class="space-y-2" x-show="category === 'Asisten Keperawatan'" x-transition>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Jurusan yang Diperbolehkan
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="majorStatus = 'core'" :class="majorStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="majorStatus = 'secondary'" :class="majorStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="majorStatus = 'nonaktif'" :class="majorStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_major_status" :value="majorStatus">
                            </div>
                            <input type="text" name="req_major_value" :disabled="majorStatus === 'nonaktif'" value="{{ old('req_major_value', 'Keperawatan, Asisten Keperawatan') }}" placeholder="Contoh: Keperawatan, Asisten Keperawatan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 transition-all" :class="majorStatus === 'nonaktif' ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-white text-slate-800'">
                        </div>

                        <!-- 8. Persyaratan Penempatan Kerja (Unified) -->
                        <div class="space-y-4 border border-slate-100 bg-slate-50/30 p-4 rounded-2xl" x-show="category !== 'Asisten Keperawatan' || placementChoicesStatus === 'nonaktif'" x-transition>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Persyaratan Penempatan Kerja
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="placementStatus = 'core'" :class="placementStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="placementStatus = 'secondary'" :class="placementStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="placementStatus = 'nonaktif'" :class="placementStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_placement_ready_status" :value="placementStatus">
                            </div>

                            <!-- Pilihan Tipe Penempatan (Hanya Tampil Jika placementStatus !== 'nonaktif') -->
                            <div class="grid grid-cols-2 gap-3" x-show="placementStatus !== 'nonaktif'" x-transition>
                                <button type="button" @click="placementType = 'anywhere'" 
                                    :class="placementType === 'anywhere' ? 'border-[#003d7c] bg-blue-50/50 text-[#003d7c] font-semibold' : 'border-slate-200 text-slate-600'" 
                                    class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all">
                                    <span class="text-xs">Seluruh Wilayah</span>
                                    <span class="text-[10px] font-normal text-slate-400 mt-1">Siap ditempatkan di mana saja</span>
                                </button>
                                <button type="button" @click="placementType = 'specific'" 
                                    :class="placementType === 'specific' ? 'border-[#003d7c] bg-blue-50/50 text-[#003d7c] font-semibold' : 'border-slate-200 text-slate-600'" 
                                    class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition-all">
                                    <span class="text-xs">Spesifik Lokasi</span>
                                    <span class="text-[10px] font-normal text-slate-400 mt-1">Pilih Provinsi & Kota</span>
                                </button>
                                <input type="hidden" name="req_placement_type" :value="placementType">
                            </div>

                            <!-- Keterangan jika tipe Anywhere -->
                            <div x-show="placementStatus !== 'nonaktif' && placementType === 'anywhere'" x-transition 
                                class="text-xs text-slate-500 bg-slate-50/50 px-3.5 py-3 rounded-xl border border-slate-150 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-slate-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Menuntut pelamar siap ditempatkan di seluruh area operasional PT UCI.</span>
                            </div>

                            <!-- Pilihan Provinsi & Kota (Tampil jika tipe specific ATAU jika status nonaktif tapi category bukan Cleaning Service agar tetap bisa pilih lokasi default) -->
                            <div class="space-y-2" x-show="(placementStatus !== 'nonaktif' && placementType === 'specific') || (placementStatus === 'nonaktif' && category !== 'Cleaning Service')" x-transition>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Provinsi <span class="text-rose-500 font-bold">*</span></label>
                                        <select id="location-province" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 transition-all bg-white" 
                                            :required="(placementStatus !== 'nonaktif' && placementType === 'specific') || (placementStatus === 'nonaktif' && category !== 'Cleaning Service')">
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Kota/Kabupaten <span class="text-rose-500 font-bold">*</span></label>
                                        <select id="location-city" name="location_city"
                                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 transition-all bg-white" 
                                            :required="(placementStatus !== 'nonaktif' && placementType === 'specific') || (placementStatus === 'nonaktif' && category !== 'Cleaning Service')">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                        </select>
                                    </div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1" id="location-helper">Pilih wilayah penempatan spesifik untuk lowongan ini.</p>
                                @error('location_city')
                                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- 10. Opsi Area Penempatan (Asisten Keperawatan & Cleaning Service) -->
                        <div class="space-y-2" x-show="category === 'Cleaning Service' && placementStatus === 'nonaktif'" x-transition>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-705 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Pilihan Wilayah Penempatan <span class="text-rose-500 font-bold">*</span>
                                </span>
                                <input type="hidden" name="req_placement_choices_status" :value="placementStatus === 'nonaktif' ? 'secondary' : 'nonaktif'">
                            </div>
                            <input type="text" name="req_placement_choices_value" :disabled="placementStatus !== 'nonaktif'" :required="category === 'Cleaning Service' && placementStatus === 'nonaktif'" value="{{ old('req_placement_choices_value', 'Cakung (Jakarta Timur), Lebak Bulus (Jakarta Selatan)') }}" placeholder="Pisahkan wilayah dengan koma, contoh: Cakung, Lebak Bulus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 transition-all" :class="placementStatus !== 'nonaktif' ? 'bg-slate-50 text-slate-400 border-slate-100' : 'bg-white text-slate-800'">
                        </div>

                        <!-- ==================== RUNNER ONLY FIELDS ==================== -->
                        <!-- 12. Menguasai Kebutuhan Penunjang Medis (Runner saja) -->
                        <div class="space-y-2" x-show="category === 'Runner'" x-transition>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-705 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Penunjang Medis (Pasien/Perawat)
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="medicalSupportStatus = 'core'" :class="medicalSupportStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="medicalSupportStatus = 'secondary'" :class="medicalSupportStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="medicalSupportStatus = 'nonaktif'" :class="medicalSupportStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_medical_support_status" :value="medicalSupportStatus">
                            </div>
                            <div class="text-[11px] text-slate-550 bg-slate-50/50 px-3.5 py-3 rounded-xl border border-slate-150 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Kriteria: Menguasai kebutuhan Penunjang Medis yang dibutuhkan pasien/perawat.</span>
                            </div>
                        </div>

                        <!-- 13. Mengetahui Istilah-Istilah Medis (Runner saja) -->
                        <div class="space-y-2" x-show="category === 'Runner'" x-transition>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-705 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pemahaman Istilah Medis
                                </span>
                                <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px]">
                                    <button type="button" @click="medicalTermsStatus = 'core'" :class="medicalTermsStatus === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Wajib</button>
                                    <button type="button" @click="medicalTermsStatus = 'secondary'" :class="medicalTermsStatus === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Tambahan</button>
                                    <button type="button" @click="medicalTermsStatus = 'nonaktif'" :class="medicalTermsStatus === 'nonaktif' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2 py-0.5 rounded-md transition-all">Nonaktif</button>
                                </div>
                                <input type="hidden" name="req_medical_terms_status" :value="medicalTermsStatus">
                            </div>
                            <div class="text-[11px] text-slate-550 bg-slate-50/50 px-3.5 py-3 rounded-xl border border-slate-150 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Kriteria: Mengetahui istilah-istilah dalam medis.</span>
                            </div>
                        </div>

                        <!-- 11. Berkas Tambahan Dinamis (Asisten Keperawatan, Driver Ambulance, Cleaning Service, & Runner) -->
                        <div class="col-span-full border-t border-slate-100 pt-6" x-show="['Asisten Keperawatan', 'Driver Ambulance', 'Cleaning Service', 'Runner'].includes(category)" x-transition>
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h5 class="text-xs font-extrabold text-slate-855">Dokumen & Berkas Kustom Tambahan</h5>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Berkas penting tambahan yang wajib diunggah pelamar (misal: STR, Sertifikat Kompetensi, SIM, dll).</p>
                                </div>
                                <button type="button" @click="customDocs.push({ key: '', label: '', status: 'core' })" class="text-[11px] font-bold bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-xl transition-all">
                                    + Tambah Berkas Baru
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(doc, index) in customDocs" :key="index">
                                    <div class="flex items-center gap-4 p-3.5 bg-slate-50 rounded-2xl border border-slate-100 animate-fade-in">
                                        <!-- Nama/Label Berkas -->
                                        <div class="w-7/12">
                                            <label class="text-[10px] font-extrabold text-slate-500">Nama/Label Berkas</label>
                                            <input type="text" x-model="doc.label" @input="doc.key = slugify(doc.label)" name="req_custom_doc_labels[]" placeholder="Contoh: Surat Tanda Registrasi (STR)" class="w-full mt-1 px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none bg-white" required>
                                            <!-- Hidden input to store the calculated key code -->
                                            <input type="hidden" x-model="doc.key" name="req_custom_doc_keys[]">
                                        </div>
                                        <!-- Status Kriteria -->
                                        <div class="w-4/12">
                                            <label class="text-[10px] font-extrabold text-slate-500">Status Kriteria</label>
                                            <select x-model="doc.status" name="req_custom_doc_statuses[]" class="w-full mt-1 px-2.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none bg-white font-semibold">
                                                <option value="core">Wajib (Core)</option>
                                                <option value="secondary">Tambahan</option>
                                            </select>
                                        </div>
                                        <!-- Delete Button -->
                                        <div class="shrink-0 pt-4 flex items-center justify-center">
                                            <button type="button" @click="customDocs.splice(index, 1)" class="text-rose-600 hover:text-white p-2.5 rounded-xl bg-rose-50 hover:bg-rose-600 transition-all border border-rose-200 hover:border-rose-600 flex items-center justify-center shadow-xs" title="Hapus Berkas">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="customDocs.length === 0" class="text-center py-5 text-xs text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                                    Belum ada berkas kustom yang ditambahkan. Cocok untuk lowongan umum.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Informasi Pendukung (Lokasi, Shift, Gaji) -->
                <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100 space-y-5">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200/55 pb-2">Informasi Operasional & Finansial</h4>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Batas Waktu & Lokasi -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-600">Lowongan Aktif Sampai</label>
                                <input type="date" name="active_until" value="{{ old('active_until') }}"
                                    class="w-full mt-2 px-4 py-2 rounded-xl border border-slate-200 text-sm">
                            </div>
                        </div>

                        <!-- Shift & Finansial -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Jenis Shift <span class="text-rose-500 font-bold" x-show="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category)">*</span></label>
                                    <select name="shift_type" class="w-full mt-2 px-3 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500 transition-all bg-white" :required="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category)">
                                        <option value="" @selected(old('shift_type') === null || old('shift_type') === '')>-- Pilih Jenis Shift --</option>
                                        <option value="none" @selected(old('shift_type') === 'none')>Tidak ada</option>
                                        <option value="shift" @selected(old('shift_type') === 'shift')>Menggunakan Shift</option>
                                        <option value="non_shift" @selected(old('shift_type') === 'non_shift')>Non-Shift (Reguler)</option>
                                    </select>
                                    @error('shift_type')
                                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Visibilitas Gaji</label>
                                    <label class="mt-3.5 flex items-center gap-2 text-xs text-slate-600 cursor-pointer">
                                        <input type="checkbox" name="salary_hidden" value="1" class="rounded border-slate-300"
                                            x-model="salaryHidden">
                                        Sembunyikan Rentang Gaji
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 transition-all" :class="salaryHidden ? 'opacity-40 pointer-events-none' : ''">
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Gaji Minimum (Rupiah) <span class="text-rose-500 font-bold" x-show="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden">*</span></label>
                                    <input type="number" name="salary_min" min="0" value="{{ old('salary_min') }}"
                                        :disabled="salaryHidden"
                                        :required="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden"
                                        class="w-full mt-2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm placeholder-slate-350 bg-white"
                                        placeholder="Contoh: 4000000">
                                    @error('salary_min')
                                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Gaji Maksimum (Rupiah) <span class="text-rose-500 font-bold" x-show="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden">*</span></label>
                                    <input type="number" name="salary_max" min="0" value="{{ old('salary_max') }}"
                                        :disabled="salaryHidden"
                                        :required="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden"
                                        class="w-full mt-2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm placeholder-slate-350 bg-white"
                                        placeholder="Contoh: 6000000">
                                    @error('salary_max')
                                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="is_active" value="1">
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">
                    <button type="submit"
                        class="px-8 py-3 rounded-2xl bg-gradient-to-r from-[#003d7c] to-[#005fb8] text-white text-sm font-bold shadow-md hover:shadow-lg hover:brightness-105 active:scale-95 transition-all">
                        Simpan Lowongan Kerja
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const locationCity = document.getElementById('location-city');
            const locationProvince = document.getElementById('location-province');

            if (locationProvince && locationCity) {
                fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                    .then((response) => response.json())
                    .then((provinces) => {
                        provinces.forEach((province) => {
                            const option = document.createElement('option');
                            option.value = province.id;
                            option.textContent = province.name;
                            locationProvince.appendChild(option);
                        });
                    });

                locationProvince.addEventListener('change', function () {
                    const provinceId = this.value;
                    locationCity.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    if (!provinceId) return;

                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                        .then((response) => response.json())
                        .then((cities) => {
                            cities.forEach((city) => {
                                const option = document.createElement('option');
                                option.value = city.name;
                                option.textContent = city.name;
                                locationCity.appendChild(option);
                            });
                        });
                });
            }
        });
    </script>
@endsection
