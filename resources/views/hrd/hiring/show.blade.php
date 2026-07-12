@extends('layouts.dashboard')

@section('dashboard-title', 'Pelamar Lowongan')

@section('dashboard-content')
    <div class="space-y-6 animate-fade-in" 
         x-data="{ 
            activeApplicant: null,
            customDocsConfig: {{ json_encode($posting->requirements_config['custom_documents'] ?? []) }},
            priorityPage: 1,
            priorityPerPage: 5,
            totalPriority: {{ $priorityApplications->count() }},
            showPriorityRow(index) {
                const start = (this.priorityPage - 1) * this.priorityPerPage;
                const end = start + this.priorityPerPage;
                return index >= start && index < end;
            },
            
            nonPriorityPage: 1,
            nonPriorityPerPage: 5,
            totalNonPriority: {{ $nonPriorityApplications->count() }},
            showNonPriorityRow(index) {
                const start = (this.nonPriorityPage - 1) * this.nonPriorityPerPage;
                const end = start + this.nonPriorityPerPage;
                return index >= start && index < end;
            }
         }">
        <!-- Header Card -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <!-- Decorative gradient banner top -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Manajemen Pelamar</span>
                    <h3 class="text-2xl font-bold text-slate-800 tracking-tight mt-2">{{ $posting->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Kategori Kerja: <strong class="text-slate-700 font-semibold">{{ $posting->category }}</strong></p>
                </div>
                <a href="{{ route('hrd.hiring') }}"
                    class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-100">
                    Kembali ke Daftar
                </a>
            </div>

            <!-- Configuration Summary in Dashboard -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <details class="group cursor-pointer">
                    <summary class="flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-800 select-none">
                        <span class="transition-transform duration-200 group-open:rotate-90">▶</span>
                        Lihat Pengaturan Kriteria Kualifikasi SPK Lowongan Ini
                    </summary>
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                        @php
                            $config = $posting->requirements_config ?? [];
                            $getConfigLabel = function($key) use ($config) {
                                $status = $config[$key]['status'] ?? 'nonaktif';
                                if ($status === 'core') return '<span class="text-[9px] font-extrabold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100/50">Wajib (Core)</span>';
                                if ($status === 'secondary') return '<span class="text-[9px] font-extrabold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100/50">Nilai Tambah</span>';
                                return '<span class="text-[9px] font-semibold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100/50">Diabaikan</span>';
                            };
                        @endphp
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Gender</span>
                            {!! $getConfigLabel('gender') !!}
                            @if(($config['gender']['status'] ?? 'nonaktif') !== 'nonaktif')
                                <span class="text-[10px] text-slate-600 block mt-0.5">
                                    @if(($config['gender']['value'] ?? 'male') === 'both')
                                        Pria & Wanita
                                    @elseif(($config['gender']['value'] ?? 'male') === 'male')
                                        Pria saja
                                    @else
                                        Wanita saja
                                    @endif
                                </span>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Rentang Usia</span>
                            {!! $getConfigLabel('age') !!}
                            @if(($config['age']['status'] ?? 'nonaktif') !== 'nonaktif')
                                <span class="text-[10px] text-slate-600 block mt-0.5">{{ $config['age']['min'] ?? 18 }} - {{ $config['age']['max'] ?? 65 }} tahun</span>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Min. Pendidikan</span>
                            {!! $getConfigLabel('education') !!}
                            @if(($config['education']['status'] ?? 'nonaktif') !== 'nonaktif')
                                <span class="text-[10px] text-slate-600 block mt-0.5">{{ $config['education']['value'] ?? 'SMA/SMK' }}</span>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Sertifikat AGD</span>
                            {!! $getConfigLabel('agd') !!}
                        </div>
                        <div class="space-y-1 mt-2">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">SIM C (Motor)</span>
                            {!! $getConfigLabel('sim_c') !!}
                        </div>
                        <div class="space-y-1 mt-2">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">SIM B1 (Mobil Berat)</span>
                            {!! $getConfigLabel('sim_b1') !!}
                        </div>
                        <div class="space-y-1 mt-2">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Pengalaman Kerja</span>
                            {!! $getConfigLabel('experience') !!}
                            @if(($config['experience']['status'] ?? 'nonaktif') !== 'nonaktif')
                                <span class="text-[10px] text-slate-600 block mt-0.5">Min. {{ $config['experience']['value'] ?? 0 }} tahun</span>
                            @endif
                        </div>
                        <div class="space-y-1 mt-2">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase">Siap Penempatan</span>
                            {!! $getConfigLabel('placement_ready') !!}
                        </div>
                    </div>
                </details>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="space-y-6">
            
            <!-- Prioritas Table Card -->
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Pelamar Prioritas
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">Lulus semua kriteria wajib (Core Factor). Diurutkan berdasarkan skor SPK tertinggi.</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100/80 px-3 py-1 rounded-xl">
                        {{ $priorityApplications->count() }} Pelamar
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead class="text-slate-400 border-b border-slate-150">
                            <tr>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Nama Pelamar</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Status</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Skor SPK</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Kualifikasi Utama</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Dokumen Pendukung</th>
                                <th class="text-center pb-3 font-bold uppercase tracking-wider w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-750 divide-y divide-slate-100">
                            @forelse($priorityApplications as $application)
                                @php
                                    $customDocsList = [];
                                    if (!empty($application->additional_documents)) {
                                        foreach ($application->additional_documents as $key => $path) {
                                            $customDocsList[$key] = asset('storage/' . $path);
                                        }
                                    }

                                    $appData = json_encode([
                                        'id' => $application->id,
                                        'status' => $application->status,
                                        'name' => $application->user->name,
                                        'email' => $application->user->email,
                                        'phone' => $application->user->profile->phone ?? '-',
                                        'gender' => $application->gender === 'male' ? 'Pria' : 'Wanita',
                                        'age' => $application->age ?? '-',
                                        'birth_place' => $application->user->profile->birth_place ?? null,
                                        'birth_date' => $application->user->profile->birth_date ? \Carbon\Carbon::parse($application->user->profile->birth_date)->translatedFormat('d F Y') : null,
                                        'education_level' => $application->education_level,
                                        'major' => $application->major ?? '-',
                                        'experience_years' => $application->experience_years,
                                        'placement_ready' => $application->placement_ready ? 'Siap' : 'Tidak Siap',
                                        'placement_choice' => $application->placement_choice ?? '-',
                                        'address' => $application->user->profile->address ?? '-',
                                        'city' => $application->user->profile->city ?? null,
                                        'province' => $application->user->profile->province ?? null,
                                        'postal_code' => $application->user->profile->postal_code ?? null,
                                        'matching_score' => $application->matching_score,
                                        'is_priority' => $application->is_priority,
                                        'agd_path' => $application->agd_certificate_path ? asset('storage/' . $application->agd_certificate_path) : null,
                                        'sim_c_path' => $application->sim_c_path ? asset('storage/' . $application->sim_c_path) : null,
                                        'sim_b1_path' => $application->sim_b1_path ? asset('storage/' . $application->sim_b1_path) : null,
                                        'cv_path' => $application->user->profile?->cv_path ? asset('storage/' . $application->user->profile->cv_path) : null,
                                        'photo_path' => $application->user->profile?->photo_path ? asset('storage/' . $application->user->profile->photo_path) : null,
                                        'additional_documents' => $customDocsList,
                                        'experiences' => $application->user->profile?->extras['experiences'] ?? []
                                    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors" x-show="showPriorityRow({{ $loop->index }})">
                                    <td class="py-4 pr-2">
                                        <div class="font-bold text-slate-800 text-sm">{{ $application->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-1 font-semibold">{{ $application->gender === 'male' ? 'Pria' : 'Wanita' }}, {{ $application->age ?? '-' }} Tahun</div>
                                    </td>
                                    <td class="py-4">
                                        @if($application->status === 'accepted')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/70">
                                                Diterima
                                            </span>
                                        @elseif($application->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100/70">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100/70">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/70 shadow-sm">
                                            <svg class="text-emerald-600 shrink-0" style="width: 14px; height: 14px;" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                            {{ $application->matching_score }}% Match
                                        </span>
                                    </td>
                                    <td class="py-4 pr-2">
                                        <div class="space-y-1">
                                            <span class="block">Pendidikan: <strong class="text-slate-800 font-bold">{{ $application->education_level }}</strong></span>
                                            <span class="block text-[10px] text-slate-500">Penempatan: <strong class="font-bold text-emerald-600">{{ $application->placement_ready ? 'Siap' : 'Tidak' }}</strong></span>
                                        </div>
                                    </td>
                                    <td class="py-4 pr-2">
                                        <div class="flex flex-wrap gap-1.5">
                                            @if($application->agd_certificate_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100/50 hover:bg-blue-100 transition-all"
                                                    href="{{ asset('storage/' . $application->agd_certificate_path) }}"
                                                    target="_blank">AGD</a>
                                            @endif
                                            @if($application->sim_c_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100/50 hover:bg-blue-100 transition-all"
                                                    href="{{ asset('storage/' . $application->sim_c_path) }}"
                                                    target="_blank">SIM C</a>
                                            @endif
                                            @if($application->sim_b1_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100/50 hover:bg-blue-100 transition-all"
                                                    href="{{ asset('storage/' . $application->sim_b1_path) }}"
                                                    target="_blank">SIM B1</a>
                                            @endif
                                            @if($application->user->profile?->cv_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100/50 hover:bg-indigo-100 transition-all"
                                                    href="{{ asset('storage/' . $application->user->profile->cv_path) }}"
                                                    target="_blank">CV</a>
                                            @endif
                                            @if($application->user->profile?->photo_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-amber-50 text-amber-700 border border-amber-100/50 hover:bg-amber-100 transition-all"
                                                    href="{{ asset('storage/' . $application->user->profile->photo_path) }}"
                                                    target="_blank">Foto</a>
                                            @endif
                                            @if(!$application->agd_certificate_path && !$application->sim_c_path && !$application->sim_b1_path && !$application->user->profile?->cv_path && !$application->user->profile?->photo_path)
                                                <span class="text-slate-400 font-medium">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button @click="activeApplicant = {{ $appData }}"
                                                    class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 rounded-xl transition-all border border-blue-100/50 shadow-sm group"
                                                    title="Lihat Detail Pelamar">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </button>
                                            
                                            @if(!empty($application->user->profile->phone))
                                                @php
                                                    $cleanPhone = preg_replace('/[^0-9]/', '', $application->user->profile->phone);
                                                    if (strpos($cleanPhone, '0') === 0) {
                                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                                    }
                                                @endphp
                                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                                                   class="inline-flex items-center justify-center p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 rounded-xl transition-all border border-emerald-100/50 shadow-sm group"
                                                   title="Hubungi via WhatsApp">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.004 2C6.48 2 2 6.48 2 12.004c0 1.91.53 3.69 1.45 5.22L2 22l4.95-1.42c1.47.81 3.16 1.27 4.95 1.27 5.52 0 10-4.48 10-10.004C21.9 6.48 17.52 2 12.004 2zm5.72 13.91c-.24.68-1.2 1.27-1.65 1.34-.45.07-.9-.05-2.88-.84-2.52-1.01-4.14-3.58-4.26-3.74-.12-.16-.96-1.28-.96-2.45 0-1.17.61-1.74.83-1.98.22-.24.47-.3.63-.3.16 0 .32 0 .46.01.15.01.35-.06.55.42.2.49.69 1.68.75 1.8.06.12.1.26.02.42-.08.17-.12.27-.24.42-.12.15-.26.33-.37.45-.12.13-.25.27-.11.51.14.24.63 1.04 1.35 1.68.93.82 1.7 1.08 1.94 1.2.24.12.38.1.52-.06.14-.16.61-.71.77-.96.16-.24.32-.2.54-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.58-.18 1.26z"/>
                                                    </svg>
                                                </a>
                                            @endif

                                            @if($application->status === 'pending')
                                                <form id="accept-form-{{ $application->id }}" action="{{ route('hrd.applications.accept', $application) }}" method="POST" class="hidden">
                                                    @csrf
                                                </form>
                                                <form id="reject-form-{{ $application->id }}" action="{{ route('hrd.applications.reject', $application) }}" method="POST" class="hidden">
                                                    @csrf
                                                </form>
                                                <button @click="$dispatch('open-confirm-modal', {
                                                            title: 'Terima Pelamar',
                                                            message: 'Apakah Anda yakin ingin menerima pelamar {{ $application->user->name }}?',
                                                            confirmText: 'Ya, Terima',
                                                            type: 'info',
                                                            actionType: 'submit',
                                                            formElement: document.getElementById('accept-form-{{ $application->id }}')
                                                        })"
                                                        class="inline-flex items-center justify-center p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 rounded-xl transition-all border border-emerald-100/50 shadow-sm group"
                                                        title="Terima Pelamar">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                    </svg>
                                                </button>
                                                <button @click="$dispatch('open-confirm-modal', {
                                                            title: 'Tolak Pelamar',
                                                            message: 'Apakah Anda yakin ingin menolak pelamar {{ $application->user->name }}?',
                                                            confirmText: 'Ya, Tolak',
                                                            type: 'danger',
                                                            actionType: 'submit',
                                                            formElement: document.getElementById('reject-form-{{ $application->id }}')
                                                        })"
                                                        class="inline-flex items-center justify-center p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 rounded-xl transition-all border border-rose-100/50 shadow-sm group"
                                                        title="Tolak Pelamar">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            <a href="{{ route('hrd.applications.pdf', $application) }}" target="_blank"
                                               class="inline-flex items-center justify-center p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 hover:text-indigo-700 rounded-xl transition-all border border-indigo-100/50 shadow-sm group"
                                               title="Cetak PDF SPK">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.615 0-1.101-.476-1.12-1.08L5.82 18m11.84 0h-11.84m12.48-5.323a1.947 1.947 0 00-2.38-1.947h-6.562a1.947 1.947 0 00-2.38 1.947m11.322 0A1.947 1.947 0 0119.5 13.5v3.11a1.947 1.947 0 01-1.84 1.947m-11.322 0A1.947 1.947 0 004.5 16.61v-3.11c0-.88.667-1.63 1.522-1.752z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-semibold border-t border-slate-100">
                                        <div class="flex flex-col items-center justify-center gap-2 py-4">
                                            <svg class="text-slate-300" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v4.5A2.25 2.25 0 002.25 13.5zm0 0V16.5a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V13.5m-18 0l-2 2m20-2l2 2"></path>
                                            </svg>
                                            <span>Belum ada pelamar prioritas yang memenuhi kriteria utama.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls for Priority -->
                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500" x-show="totalPriority > priorityPerPage">
                    <div>
                        Menampilkan 
                        <span class="text-slate-800" x-text="Math.min((priorityPage - 1) * priorityPerPage + 1, totalPriority)"></span> 
                        - 
                        <span class="text-slate-800" x-text="Math.min(priorityPage * priorityPerPage, totalPriority)"></span> 
                        dari 
                        <span class="text-slate-800" x-text="totalPriority"></span> pelamar
                    </div>
                    <div class="flex items-center gap-1">
                        <button 
                            @click="if(priorityPage > 1) { priorityPage-- }" 
                            :disabled="priorityPage === 1"
                            class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:hover:bg-white transition-all duration-200"
                            :class="priorityPage === 1 ? 'cursor-not-allowed text-slate-300' : 'text-slate-600 hover:text-slate-800'"
                        >
                            Sebelumnya
                        </button>
                        
                        <template x-for="p in Math.ceil(totalPriority / priorityPerPage)" :key="p">
                            <button 
                                @click="priorityPage = p" 
                                class="w-8 h-8 rounded-xl flex items-center justify-center border font-bold transition-all duration-200"
                                :class="priorityPage === p 
                                    ? 'bg-emerald-600 border-emerald-600 text-white shadow-sm shadow-emerald-200' 
                                    : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800'"
                                x-text="p"
                            ></button>
                        </template>

                        <button 
                            @click="if(priorityPage < Math.ceil(totalPriority / priorityPerPage)) { priorityPage++ }" 
                            :disabled="priorityPage === Math.ceil(totalPriority / priorityPerPage)"
                            class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:hover:bg-white transition-all duration-200"
                            :class="priorityPage === Math.ceil(totalPriority / priorityPerPage) ? 'cursor-not-allowed text-slate-300' : 'text-slate-600 hover:text-slate-800'"
                        >
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>

            <!-- Non-Prioritas Table Card -->
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-400 to-slate-500"></div>

                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Pelamar Non-Prioritas
                        </h4>
                        <p class="text-xs text-slate-400 mt-1">Sisa pelamar yang memiliki kriteria wajib tidak sesuai.</p>
                    </div>
                    <span class="text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 px-3 py-1 rounded-xl">
                        {{ $nonPriorityApplications->count() }} Pelamar
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs">
                        <thead class="text-slate-400 border-b border-slate-150">
                            <tr>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Nama Pelamar</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Status</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Skor SPK</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Kualifikasi Utama</th>
                                <th class="text-left pb-3 font-bold uppercase tracking-wider">Dokumen Pendukung</th>
                                <th class="text-center pb-3 font-bold uppercase tracking-wider w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-750 divide-y divide-slate-100">
                            @forelse($nonPriorityApplications as $application)
                                @php
                                    $customDocsList = [];
                                    if (!empty($application->additional_documents)) {
                                        foreach ($application->additional_documents as $key => $path) {
                                            $customDocsList[$key] = asset('storage/' . $path);
                                        }
                                    }

                                    $appData = json_encode([
                                        'id' => $application->id,
                                        'status' => $application->status,
                                        'name' => $application->user->name,
                                        'email' => $application->user->email,
                                        'phone' => $application->user->profile->phone ?? '-',
                                        'gender' => $application->gender === 'male' ? 'Pria' : 'Wanita',
                                        'age' => $application->age ?? '-',
                                        'birth_place' => $application->user->profile->birth_place ?? null,
                                        'birth_date' => $application->user->profile->birth_date ? \Carbon\Carbon::parse($application->user->profile->birth_date)->translatedFormat('d F Y') : null,
                                        'education_level' => $application->education_level,
                                        'major' => $application->major ?? '-',
                                        'experience_years' => $application->experience_years,
                                        'placement_ready' => $application->placement_ready ? 'Siap' : 'Tidak Siap',
                                        'placement_choice' => $application->placement_choice ?? '-',
                                        'address' => $application->user->profile->address ?? '-',
                                        'city' => $application->user->profile->city ?? null,
                                        'province' => $application->user->profile->province ?? null,
                                        'postal_code' => $application->user->profile->postal_code ?? null,
                                        'matching_score' => $application->matching_score,
                                        'is_priority' => $application->is_priority,
                                        'agd_path' => $application->agd_certificate_path ? asset('storage/' . $application->agd_certificate_path) : null,
                                        'sim_c_path' => $application->sim_c_path ? asset('storage/' . $application->sim_c_path) : null,
                                        'sim_b1_path' => $application->sim_b1_path ? asset('storage/' . $application->sim_b1_path) : null,
                                        'cv_path' => $application->user->profile?->cv_path ? asset('storage/' . $application->user->profile->cv_path) : null,
                                        'photo_path' => $application->user->profile?->photo_path ? asset('storage/' . $application->user->profile->photo_path) : null,
                                        'additional_documents' => $customDocsList,
                                        'experiences' => $application->user->profile?->extras['experiences'] ?? []
                                    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors" x-show="showNonPriorityRow({{ $loop->index }})">
                                    <td class="py-4 pr-2">
                                        <div class="font-bold text-slate-700 text-sm">{{ $application->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-1 font-semibold">{{ $application->gender === 'male' ? 'Pria' : 'Wanita' }}, {{ $application->age ?? '-' }} Tahun</div>
                                    </td>
                                    <td class="py-4">
                                        @if($application->status === 'accepted')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/70">
                                                Diterima
                                            </span>
                                        @elseif($application->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100/70">
                                                Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100/70">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $application->matching_score }}% Match
                                        </span>
                                    </td>
                                    <td class="py-4 pr-2">
                                        <div class="space-y-1">
                                            <span class="block">Pendidikan: <strong class="text-slate-700 font-bold">{{ $application->education_level }}</strong></span>
                                            <span class="block text-[10px] text-slate-500">Penempatan: <strong class="font-bold text-slate-600">{{ $application->placement_ready ? 'Siap' : 'Tidak' }}</strong></span>
                                        </div>
                                    </td>
                                    <td class="py-4 pr-2">
                                        <div class="flex flex-wrap gap-1.5">
                                            @if($application->agd_certificate_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition-all"
                                                    href="{{ asset('storage/' . $application->agd_certificate_path) }}"
                                                    target="_blank">AGD</a>
                                            @endif
                                            @if($application->sim_c_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition-all"
                                                    href="{{ asset('storage/' . $application->sim_c_path) }}"
                                                    target="_blank">SIM C</a>
                                            @endif
                                            @if($application->sim_b1_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition-all"
                                                    href="{{ asset('storage/' . $application->sim_b1_path) }}"
                                                    target="_blank">SIM B1</a>
                                            @endif
                                            @if($application->user->profile?->cv_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition-all"
                                                    href="{{ asset('storage/' . $application->user->profile->cv_path) }}"
                                                    target="_blank">CV</a>
                                            @endif
                                            @if($application->user->profile?->photo_path)
                                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 transition-all"
                                                    href="{{ asset('storage/' . $application->user->profile->photo_path) }}"
                                                    target="_blank">Foto</a>
                                            @endif
                                            @if(!$application->agd_certificate_path && !$application->sim_c_path && !$application->sim_b1_path && !$application->user->profile?->cv_path && !$application->user->profile?->photo_path)
                                                <span class="text-slate-400 font-medium">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button @click="activeApplicant = {{ $appData }}"
                                                    class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 rounded-xl transition-all border border-blue-100/50 shadow-sm group"
                                                    title="Lihat Detail Pelamar">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </button>
                                            
                                            @if(!empty($application->user->profile->phone))
                                                @php
                                                    $cleanPhone = preg_replace('/[^0-9]/', '', $application->user->profile->phone);
                                                    if (strpos($cleanPhone, '0') === 0) {
                                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                                    }
                                                @endphp
                                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                                                   class="inline-flex items-center justify-center p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 rounded-xl transition-all border border-emerald-100/50 shadow-sm group"
                                                   title="Hubungi via WhatsApp">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.004 2C6.48 2 2 6.48 2 12.004c0 1.91.53 3.69 1.45 5.22L2 22l4.95-1.42c1.47.81 3.16 1.27 4.95 1.27 5.52 0 10-4.48 10-10.004C21.9 6.48 17.52 2 12.004 2zm5.72 13.91c-.24.68-1.2 1.27-1.65 1.34-.45.07-.9-.05-2.88-.84-2.52-1.01-4.14-3.58-4.26-3.74-.12-.16-.96-1.28-.96-2.45 0-1.17.61-1.74.83-1.98.22-.24.47-.3.63-.3.16 0 .32 0 .46.01.15.01.35-.06.55.42.2.49.69 1.68.75 1.8.06.12.1.26.02.42-.08.17-.12.27-.24.42-.12.15-.26.33-.37.45-.12.13-.25.27-.11.51.14.24.63 1.04 1.35 1.68.93.82 1.7 1.08 1.94 1.2.24.12.38.1.52-.06.14-.16.61-.71.77-.96.16-.24.32-.2.54-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.58-.18 1.26z"/>
                                                    </svg>
                                                </a>
                                            @endif

                                            @if($application->status === 'pending')
                                                <form id="accept-form-{{ $application->id }}" action="{{ route('hrd.applications.accept', $application) }}" method="POST" class="hidden">
                                                    @csrf
                                                </form>
                                                <form id="reject-form-{{ $application->id }}" action="{{ route('hrd.applications.reject', $application) }}" method="POST" class="hidden">
                                                    @csrf
                                                </form>
                                                <button @click="$dispatch('open-confirm-modal', {
                                                            title: 'Terima Pelamar',
                                                            message: 'Apakah Anda yakin ingin menerima pelamar {{ $application->user->name }}?',
                                                            confirmText: 'Ya, Terima',
                                                            type: 'info',
                                                            actionType: 'submit',
                                                            formElement: document.getElementById('accept-form-{{ $application->id }}')
                                                        })"
                                                        class="inline-flex items-center justify-center p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 rounded-xl transition-all border border-emerald-100/50 shadow-sm group"
                                                        title="Terima Pelamar">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                                    </svg>
                                                </button>
                                                <button @click="$dispatch('open-confirm-modal', {
                                                            title: 'Tolak Pelamar',
                                                            message: 'Apakah Anda yakin ingin menolak pelamar {{ $application->user->name }}?',
                                                            confirmText: 'Ya, Tolak',
                                                            type: 'danger',
                                                            actionType: 'submit',
                                                            formElement: document.getElementById('reject-form-{{ $application->id }}')
                                                        })"
                                                        class="inline-flex items-center justify-center p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 rounded-xl transition-all border border-rose-100/50 shadow-sm group"
                                                        title="Tolak Pelamar">
                                                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            <a href="{{ route('hrd.applications.pdf', $application) }}" target="_blank"
                                               class="inline-flex items-center justify-center p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 hover:text-indigo-700 rounded-xl transition-all border border-indigo-100/50 shadow-sm group"
                                               title="Cetak PDF SPK">
                                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.615 0-1.101-.476-1.12-1.08L5.82 18m11.84 0h-11.84m12.48-5.323a1.947 1.947 0 00-2.38-1.947h-6.562a1.947 1.947 0 00-2.38 1.947m11.322 0A1.947 1.947 0 0119.5 13.5v3.11a1.947 1.947 0 01-1.84 1.947m-11.322 0A1.947 1.947 0 004.5 16.61v-3.11c0-.88.667-1.63 1.522-1.752z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 font-semibold border-t border-slate-100">
                                        <div class="flex flex-col items-center justify-center gap-2 py-4">
                                            <svg class="text-slate-300" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v4.5A2.25 2.25 0 002.25 13.5zm0 0V16.5a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V13.5m-18 0l-2 2m20-2l2 2"></path>
                                            </svg>
                                            <span>Tidak ada pelamar non-prioritas.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls for Non-Priority -->
                <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500" x-show="totalNonPriority > nonPriorityPerPage">
                    <div>
                        Menampilkan 
                        <span class="text-slate-800" x-text="Math.min((nonPriorityPage - 1) * nonPriorityPerPage + 1, totalNonPriority)"></span> 
                        - 
                        <span class="text-slate-800" x-text="Math.min(nonPriorityPage * nonPriorityPerPage, totalNonPriority)"></span> 
                        dari 
                        <span class="text-slate-800" x-text="totalNonPriority"></span> pelamar
                    </div>
                    <div class="flex items-center gap-1">
                        <button 
                            @click="if(nonPriorityPage > 1) { nonPriorityPage-- }" 
                            :disabled="nonPriorityPage === 1"
                            class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:hover:bg-white transition-all duration-200"
                            :class="nonPriorityPage === 1 ? 'cursor-not-allowed text-slate-300' : 'text-slate-600 hover:text-slate-800'"
                        >
                            Sebelumnya
                        </button>
                        
                        <template x-for="p in Math.ceil(totalNonPriority / nonPriorityPerPage)" :key="p">
                            <button 
                                @click="nonPriorityPage = p" 
                                class="w-8 h-8 rounded-xl flex items-center justify-center border font-bold transition-all duration-200"
                                :class="nonPriorityPage === p 
                                    ? 'bg-slate-600 border-slate-600 text-white shadow-sm' 
                                    : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800'"
                                x-text="p"
                            ></button>
                        </template>

                        <button 
                            @click="if(nonPriorityPage < Math.ceil(totalNonPriority / nonPriorityPerPage)) { nonPriorityPage++ }" 
                            :disabled="nonPriorityPage === Math.ceil(totalNonPriority / nonPriorityPerPage)"
                            class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:hover:bg-white transition-all duration-200"
                            :class="nonPriorityPage === Math.ceil(totalNonPriority / nonPriorityPerPage) ? 'cursor-not-allowed text-slate-300' : 'text-slate-600 hover:text-slate-800'"
                        >
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Reusable Detailed Applicant Modal (Lightbox-style) -->
        <div x-show="activeApplicant !== null" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" @click="activeApplicant = null"></div>

            <!-- Modal Content Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative w-full max-w-4xl transform rounded-3xl bg-white p-6 md:p-8 text-left shadow-2xl transition-all border border-slate-100 overflow-hidden"
                     x-show="activeApplicant !== null"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    
                    <!-- Top color stripe -->
                    <div class="absolute top-0 left-0 right-0 h-1.5"
                         :class="activeApplicant && activeApplicant.is_priority ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gradient-to-r from-slate-400 to-slate-500'"></div>

                    <!-- Header -->
                    <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-6">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span x-text="activeApplicant && activeApplicant.is_priority ? 'Pelamar Prioritas' : 'Pelamar Non-Prioritas'" 
                                      class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-lg"
                                      :class="activeApplicant && activeApplicant.is_priority ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-700 border border-slate-200'"></span>
                                
                                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100/50"
                                      x-text="activeApplicant ? activeApplicant.matching_score + '% Match' : ''"></span>

                                <template x-if="activeApplicant && activeApplicant.status === 'accepted'">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Diterima
                                    </span>
                                </template>
                                <template x-if="activeApplicant && activeApplicant.status === 'rejected'">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-100">
                                        Ditolak
                                    </span>
                                </template>
                                <template x-if="activeApplicant && activeApplicant.status === 'pending'">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-100">
                                        Pending
                                    </span>
                                </template>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 tracking-tight mt-2" x-text="activeApplicant ? activeApplicant.name : ''"></h3>
                            <p class="text-xs text-slate-500 mt-1" x-text="activeApplicant ? activeApplicant.email : ''"></p>
                        </div>
                        <button @click="activeApplicant = null" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Body Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[60vh] overflow-y-auto pr-1">
                        
                        <!-- Left Panel: Profile & Contact -->
                        <div class="space-y-6">
                            <!-- Basic Profile -->
                            <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Informasi Pribadi & Kontak</h4>
                                <div class="grid grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Jenis Kelamin</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant ? activeApplicant.gender : '-'"></strong>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Usia</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant ? activeApplicant.age + ' Tahun' : '-'"></strong>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Tempat Lahir</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.birth_place ? activeApplicant.birth_place : '-'"></strong>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Tanggal Lahir</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.birth_date ? activeApplicant.birth_date : '-'"></strong>
                                    </div>
                                    <div class="col-span-2 border-t border-slate-100 pt-3 flex items-center justify-between">
                                        <div>
                                            <span class="text-slate-400 block mb-0.5">Nomor Telepon</span>
                                            <strong class="text-slate-700 font-bold text-sm" x-text="activeApplicant ? activeApplicant.phone : '-'"></strong>
                                        </div>
                                        <template x-if="activeApplicant && activeApplicant.phone && activeApplicant.phone !== '-'">
                                            <a :href="'https://wa.me/' + (activeApplicant.phone.replace(/[^0-9]/g, '').startsWith('0') ? '62' + activeApplicant.phone.replace(/[^0-9]/g, '').substring(1) : activeApplicant.phone.replace(/[^0-9]/g, ''))"
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase rounded-xl transition-all border border-emerald-250 shadow-sm">
                                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12.004 2C6.48 2 2 6.48 2 12.004c0 1.91.53 3.69 1.45 5.22L2 22l4.95-1.42c1.47.81 3.16 1.27 4.95 1.27 5.52 0 10-4.48 10-10.004C21.9 6.48 17.52 2 12.004 2zm5.72 13.91c-.24.68-1.2 1.27-1.65 1.34-.45.07-.9-.05-2.88-.84-2.52-1.01-4.14-3.58-4.26-3.74-.12-.16-.96-1.28-.96-2.45 0-1.17.61-1.74.83-1.98.22-.24.47-.3.63-.3.16 0 .32 0 .46.01.15.01.35-.06.55.42.2.49.69 1.68.75 1.8.06.12.1.26.02.42-.08.17-.12.27-.24.42-.12.15-.26.33-.37.45-.12.13-.25.27-.11.51.14.24.63 1.04 1.35 1.68.93.82 1.7 1.08 1.94 1.2.24.12.38.1.52-.06.14-.16.61-.71.77-.96.16-.24.32-.2.54-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.58-.18 1.26z"/>
                                                </svg>
                                                Hubungi via WA
                                            </a>
                                        </template>
                                    </div>
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Alamat Email</span>
                                        <strong class="text-slate-700 font-bold text-sm block" x-text="activeApplicant ? activeApplicant.email : '-'"></strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Domicile -->
                            <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Alamat Domisili</h4>
                                <div class="text-xs space-y-2.5">
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Alamat Lengkap</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant ? activeApplicant.address : '-'"></strong>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-2.5">
                                        <div>
                                            <span class="text-slate-400 block mb-0.5">Kota / Kabupaten</span>
                                            <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.city ? activeApplicant.city : '-'"></strong>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block mb-0.5">Provinsi</span>
                                            <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.province ? activeApplicant.province : '-'"></strong>
                                        </div>
                                    </div>
                                    <div class="border-t border-slate-100 pt-2.5">
                                        <span class="text-slate-400 block mb-0.5">Kode Pos</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.postal_code ? activeApplicant.postal_code : '-'"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Qualifications & Files -->
                        <div class="space-y-6">
                            <!-- Kualifikasi SPK -->
                            <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Kualifikasi Utama</h4>
                                <div class="grid grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                                    <div class="col-span-2">
                                        <span class="text-slate-400 block mb-0.5">Pendidikan Terakhir</span>
                                        <strong class="text-slate-700 font-bold text-[13px]" x-text="activeApplicant ? activeApplicant.education_level : '-'"></strong>
                                    </div>
                                    @if(($config['major']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Jurusan Pendidikan</span>
                                        <strong class="text-slate-700 font-bold text-[13px]" x-text="activeApplicant && activeApplicant.major ? activeApplicant.major : '-'"></strong>
                                    </div>
                                    @endif
                                    @if(($config['placement_choices']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Pilihan Wilayah Penempatan</span>
                                        <strong class="text-indigo-650 font-bold text-[13px]" x-text="activeApplicant && activeApplicant.placement_choice ? activeApplicant.placement_choice : '-'"></strong>
                                    </div>
                                    @endif
                                    @if(($config['placement_ready']['status'] ?? 'core') !== 'nonaktif' || (($config['placement_ready']['status'] ?? 'core') === 'nonaktif' && ($config['placement_choices']['status'] ?? 'nonaktif') === 'nonaktif'))
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Kesiapan Penempatan Kerja</span>
                                        <strong class="font-bold text-[13px]" 
                                                :class="activeApplicant && activeApplicant.placement_ready === 'Siap' ? 'text-emerald-600' : 'text-rose-600'"
                                                x-text="activeApplicant ? activeApplicant.placement_ready : '-'"></strong>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Document Links -->
                            <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                                <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Dokumen Pendukung</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <!-- CV -->
                                    <template x-if="activeApplicant && activeApplicant.cv_path">
                                        <a :href="activeApplicant.cv_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-rose-50 text-rose-600 rounded-lg group-hover:bg-rose-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Curriculum Vitae</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat CV</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.cv_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Curriculum Vitae</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Foto -->
                                    <template x-if="activeApplicant && activeApplicant.photo_path">
                                        <a :href="activeApplicant.photo_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Foto Profil</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Foto</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.photo_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Foto Profil</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>

                                    @if(($config['agd']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <!-- AGD Certificate -->
                                    <template x-if="activeApplicant && activeApplicant.agd_path">
                                        <a :href="activeApplicant.agd_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Sertifikat AGD</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.agd_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Sertifikat AGD</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>
                                    @endif

                                    @if(($config['sim_c']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <!-- SIM C -->
                                    <template x-if="activeApplicant && activeApplicant.sim_c_path">
                                        <a :href="activeApplicant.sim_c_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM C</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.sim_c_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM C</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>
                                    @endif

                                    @if(($config['sim_b1']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <!-- SIM B1 -->
                                    <template x-if="activeApplicant && activeApplicant.sim_b1_path">
                                        <a :href="activeApplicant.sim_b1_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg group-hover:bg-teal-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM B1</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.sim_b1_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM B1</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>
                                    @endif

                                    <!-- Custom Documents loop based on requirements config -->
                                    <template x-for="(doc, idx) in customDocsConfig" :key="idx">
                                        <div class="col-span-full sm:col-span-1">
                                            <!-- If applicant uploaded the custom document -->
                                            <template x-if="activeApplicant && activeApplicant.additional_documents && activeApplicant.additional_documents[doc.key]">
                                                <a :href="activeApplicant.additional_documents[doc.key]" target="_blank"
                                                   class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-100 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </div>
                                                    <div class="text-left">
                                                        <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide" x-text="doc.label"></span>
                                                        <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                                    </div>
                                                </a>
                                            </template>
                                            <!-- If applicant DID NOT upload the custom document -->
                                            <template x-if="!activeApplicant || !activeApplicant.additional_documents || !activeApplicant.additional_documents[doc.key]">
                                                <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                                    <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </div>
                                                    <div class="text-left">
                                                        <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide" x-text="doc.label"></span>
                                                        <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Full Width Bottom Section: Work Experiences -->
                        <div class="col-span-1 md:col-span-2 border-t border-slate-100 pt-6">
                            <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Riwayat Pengalaman Kerja</h4>
                            
                            <!-- Loop experiences -->
                            <div class="space-y-4">
                                <template x-if="activeApplicant && activeApplicant.experiences && activeApplicant.experiences.length > 0">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <template x-for="(exp, index) in activeApplicant.experiences" :key="index">
                                            <div class="p-4 bg-slate-50/75 border border-slate-100 rounded-2xl relative overflow-hidden transition-all hover:bg-slate-50">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div>
                                                        <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wide" x-text="exp.position"></h5>
                                                        <p class="text-[11px] text-slate-500 font-bold mt-0.5" x-text="exp.company"></p>
                                                    </div>
                                                    <span class="shrink-0 text-[9px] font-extrabold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-100/50" 
                                                          x-text="exp.duration"></span >
                                                </div>
                                                <div class="mt-2.5 text-xs text-slate-600 leading-relaxed border-t border-slate-200/50 pt-2"
                                                     x-text="exp.description"></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!activeApplicant || !activeApplicant.experiences || activeApplicant.experiences.length === 0">
                                    <div class="text-center py-8 bg-slate-50/50 rounded-2xl border border-slate-100/50 text-slate-400 font-semibold text-xs">
                                        Belum ada riwayat pengalaman kerja yang dicantumkan.
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- Hidden Forms for Modal Actions -->
                    <form id="modal-accept-form" :action="'/hrd/applications/' + (activeApplicant ? activeApplicant.id : '') + '/accept'" method="POST" class="hidden">
                        @csrf
                    </form>
                    <form id="modal-reject-form" :action="'/hrd/applications/' + (activeApplicant ? activeApplicant.id : '') + '/reject'" method="POST" class="hidden">
                        @csrf
                    </form>

                    <!-- Footer Buttons -->
                    <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <template x-if="activeApplicant && activeApplicant.status === 'pending'">
                                <div class="flex items-center gap-2">
                                    <button @click="$dispatch('open-confirm-modal', {
                                                title: 'Terima Pelamar',
                                                message: 'Apakah Anda yakin ingin menerima pelamar ' + activeApplicant.name + '?',
                                                confirmText: 'Ya, Terima',
                                                type: 'info',
                                                actionType: 'submit',
                                                formElement: document.getElementById('modal-accept-form')
                                            })"
                                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                        </svg>
                                        Terima
                                    </button>
                                    <button @click="$dispatch('open-confirm-modal', {
                                                title: 'Tolak Pelamar',
                                                message: 'Apakah Anda yakin ingin menolak pelamar ' + activeApplicant.name + '?',
                                                confirmText: 'Ya, Tolak',
                                                type: 'danger',
                                                actionType: 'submit',
                                                formElement: document.getElementById('modal-reject-form')
                                            })"
                                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </template>
                            
                            <a :href="'/hrd/applications/' + (activeApplicant ? activeApplicant.id : '') + '/pdf'" target="_blank"
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.615 0-1.101-.476-1.12-1.08L5.82 18m11.84 0h-11.84m12.48-5.323a1.947 1.947 0 00-2.38-1.947h-6.562a1.947 1.947 0 00-2.38 1.947m11.322 0A1.947 1.947 0 0119.5 13.5v3.11a1.947 1.947 0 01-1.84 1.947m-11.322 0A1.947 1.947 0 004.5 16.61v-3.11c0-.88.667-1.63 1.522-1.752z"></path>
                                </svg>
                                Cetak PDF SPK
                            </a>
                        </div>
                        
                        <button @click="activeApplicant = null"
                                class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all border border-slate-200/50">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
