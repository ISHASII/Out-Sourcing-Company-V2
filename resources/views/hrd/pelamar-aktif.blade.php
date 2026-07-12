@extends('layouts.dashboard')

@section('dashboard-title', 'Data Pelamar')

@section('dashboard-content')
<div class="space-y-6 animate-fade-in" x-data="{ activeApplicant: null }">
     
    <!-- Header Summary & Search Filter Grid -->
    <div class="bg-gradient-to-r from-[#002855] to-[#004b93] text-white p-6 md:p-8 rounded-3xl border border-blue-900/10 shadow-lg space-y-6 relative overflow-hidden">
        <!-- Decorative subtle pattern/glow -->
        <div class="absolute inset-0 bg-white/5 backdrop-blur-xs"></div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative z-10">
            <div>
                <span class="bg-blue-500/25 text-blue-200 text-[10px] font-extrabold px-3 py-1 rounded-lg uppercase tracking-widest border border-blue-400/20">Direktori</span>
                <h3 class="text-2xl font-bold tracking-tight mt-2 text-white">Daftar Pelamar Terdaftar</h3>
                <p class="text-xs text-blue-100/80 mt-1 leading-relaxed">Kelola dan tinjau data semua pelamar yang terdaftar di sistem portal lowongan kerja PT. Unggul Cipta Indah.</p>
            </div>
            <div class="flex items-center gap-2 self-start md:self-auto">
                <span class="text-xs font-bold text-blue-100 bg-white/10 border border-white/20 px-3 py-1.5 rounded-xl">
                    Total: {{ $pelamarList->total() }} Pelamar
                </span>
            </div>
        </div>

        <!-- Search and Live Filters Form (Server-Side) -->
        <form action="{{ route('hrd.pelamar-aktif') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-white/10 relative z-10">
            <!-- Search bar -->
            <div class="relative sm:col-span-2">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-200/70">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, email, kota, provinsi..." 
                       class="w-full pl-10 pr-24 py-2.5 bg-white/10 border border-white/20 focus:border-white focus:bg-white/20 focus:ring-1 focus:ring-white/25 rounded-2xl text-xs font-medium text-white placeholder-blue-200/60 transition-all outline-none">
                <button type="submit" class="absolute right-2 top-1.5 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-xl text-[10px] font-bold transition-all">
                    Cari
                </button>
            </div>

            <!-- Filter Education -->
            <div class="flex gap-2">
                <select name="education" 
                        onchange="this.form.submit()"
                        class="flex-grow px-4 py-2.5 bg-white/10 border border-white/20 focus:border-white focus:bg-white/20 rounded-2xl text-xs font-medium text-white transition-all outline-none appearance-none cursor-pointer"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2523ffffff%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
                    <option value="all" class="text-slate-800">Semua Pendidikan</option>
                    @foreach($educations as $edu)
                        <option value="{{ $edu }}" {{ request('education') === $edu ? 'selected' : '' }} class="text-slate-800">{{ $edu }}</option>
                    @endforeach
                </select>
                
                @if(request()->has('search') || request()->has('education'))
                    <a href="{{ route('hrd.pelamar-aktif') }}" class="px-3 py-2.5 bg-white/15 hover:bg-white/25 text-white rounded-2xl text-xs font-bold transition-all border border-white/20 flex items-center justify-center" title="Reset Filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Applicants Table Container -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-slate-400 text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Pelamar</th>
                        <th class="px-6 py-4">Domisili & Kontak</th>
                        <th class="px-6 py-4">Pendidikan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-650 font-medium">
                    @forelse($pelamarList as $pelamar)
                        @php
                            $appData = json_encode([
                                'name' => $pelamar->name,
                                'email' => $pelamar->email,
                                'phone' => $pelamar->profile?->phone ?? '-',
                                'gender' => $pelamar->profile?->gender === 'male' ? 'Pria' : 'Wanita',
                                'age' => $pelamar->profile?->birth_date ? \Carbon\Carbon::parse($pelamar->profile->birth_date)->age : '-',
                                'birth_place' => $pelamar->profile?->birth_place ?? null,
                                'birth_date' => $pelamar->profile?->birth_date ? \Carbon\Carbon::parse($pelamar->profile->birth_date)->translatedFormat('d F Y') : null,
                                'education_level' => $pelamar->profile?->education_level ?? '-',
                                'experience_years' => $pelamar->profile?->experience_years ?? 0,
                                'address' => $pelamar->profile?->address ?? '-',
                                'city' => $pelamar->profile?->city ?? null,
                                'province' => $pelamar->profile?->province ?? null,
                                'postal_code' => $pelamar->profile?->postal_code ?? null,
                                'cv_path' => $pelamar->profile?->cv_path ? asset('storage/' . $pelamar->profile->cv_path) : null,
                                'photo_path' => $pelamar->profile?->photo_path ? asset('storage/' . $pelamar->profile->photo_path) : null,
                                'experiences' => $pelamar->profile?->extras['experiences'] ?? [],
                                'applications' => $pelamar->applications->map(fn($app) => [
                                    'job_title' => $app->posting->title ?? 'Posisi Tidak Tersedia',
                                    'category' => $app->posting->category ?? '-',
                                    'matching_score' => $app->matching_score,
                                    'is_priority' => $app->is_priority,
                                    'applied_at' => $app->created_at->translatedFormat('d F Y')
                                ])
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            <!-- Nama Pelamar -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-sm">{{ $pelamar->name }}</div>
                                <div class="text-[10px] text-slate-400 mt-1 font-semibold">
                                    {{ $pelamar->profile?->gender === 'male' ? 'Pria' : 'Wanita' }}
                                    @if($pelamar->profile?->birth_date)
                                        , {{ \Carbon\Carbon::parse($pelamar->profile->birth_date)->age }} Tahun
                                    @endif
                                </div>
                            </td>

                            <!-- Domisili & Kontak -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700">{{ $pelamar->profile?->city ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">{{ $pelamar->profile?->phone ?? $pelamar->email }}</div>
                            </td>

                            <!-- Pendidikan -->
                            <td class="px-6 py-4 font-bold text-slate-700">
                                {{ $pelamar->profile?->education_level ?? '-' }}
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="activeApplicant = {{ $appData }}"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 rounded-xl transition-all border border-blue-100/50 shadow-sm group"
                                            title="Lihat Detail Profil">
                                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </button>
                                    
                                    @if(!empty($pelamar->profile?->phone))
                                        @php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $pelamar->profile->phone);
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-semibold border-t border-slate-100">
                                <div class="flex flex-col items-center justify-center gap-2 py-4">
                                    <svg class="text-slate-300" style="width: 32px; height: 32px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v4.5A2.25 2.25 0 002.25 13.5zm0 0V16.5a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V13.5m-18 0l-2 2m20-2l2 2"></path>
                                    </svg>
                                    <span>Belum ada data pelamar terdaftar dalam database.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination controls (Server-side) -->
        @if($pelamarList->hasPages())
            <div class="px-6 py-4 bg-slate-50/75 border-t border-slate-100 flex items-center justify-between">
                <div class="text-xs text-slate-500 font-semibold">
                    Menampilkan 
                    <span class="text-slate-800">{{ $pelamarList->firstItem() }}</span> 
                    - 
                    <span class="text-slate-800">{{ $pelamarList->lastItem() }}</span> 
                    dari 
                    <span class="text-slate-800">{{ $pelamarList->total() }}</span> pelamar
                </div>
                <div class="flex items-center gap-1">
                    {{-- Previous Page Link --}}
                    @if($pelamarList->onFirstPage())
                        <span class="px-3 py-2 text-slate-300 bg-white border border-slate-200 rounded-xl text-xs font-bold cursor-not-allowed">Sebelumnya</span>
                    @else
                        <a href="{{ $pelamarList->previousPageUrl() }}" class="px-3 py-2 text-[#003d7c] bg-white hover:bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold transition-all duration-200">Sebelumnya</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if($pelamarList->hasMorePages())
                        <a href="{{ $pelamarList->nextPageUrl() }}" class="px-3 py-2 text-[#003d7c] bg-white hover:bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold transition-all duration-200">Selanjutnya</a>
                    @else
                        <span class="px-3 py-2 text-slate-300 bg-white border border-slate-200 rounded-xl text-xs font-bold cursor-not-allowed">Selanjutnya</span>
                    @endif
                </div>
            </div>
        @endif
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
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>

                <!-- Header -->
                <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 tracking-tight mt-2" x-text="activeApplicant ? activeApplicant.name : ''"></h3>
                        <p class="text-xs text-slate-500 mt-1" x-text="activeApplicant ? activeApplicant.email : ''"></p>
                    </div>
                    <button @click="activeApplicant = null" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-50 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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
                        <!-- Kualifikasi Utama -->
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Pendidikan & Pengalaman</h4>
                            <div class="grid grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                                <div class="col-span-2">
                                    <span class="text-slate-400 block mb-0.5">Pendidikan Terakhir</span>
                                    <strong class="text-slate-700 font-bold text-[13px]" x-text="activeApplicant ? activeApplicant.education_level : '-'"></strong>
                                </div>
                                <div class="col-span-2 border-t border-slate-100 pt-3">
                                    <span class="text-slate-400 block mb-0.5">Lama Pengalaman Kerja</span>
                                    <strong class="text-slate-700 font-bold text-[13px]" x-text="activeApplicant ? activeApplicant.experience_years + ' Tahun' : '-'"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Document Links -->
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Dokumen Utama</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- CV -->
                                <template x-if="activeApplicant && activeApplicant.cv_path">
                                    <a :href="activeApplicant.cv_path" target="_blank"
                                       class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                        <div class="p-2 bg-rose-50 text-rose-600 rounded-lg group-hover:bg-rose-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
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
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
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
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
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
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Foto Profil</span>
                                            <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                        </div>
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
                                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl relative overflow-hidden transition-all hover:bg-slate-50">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wide" x-text="exp.position"></h5>
                                                    <p class="text-[11px] text-slate-500 font-bold mt-0.5" x-text="exp.company"></p>
                                                </div>
                                                <span class="shrink-0 text-[9px] font-extrabold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-100/50" 
                                                      x-text="exp.duration"></span>
                                            </div>
                                            <div class="mt-2.5 text-xs text-slate-650 leading-relaxed border-t border-slate-200/50 pt-2"
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

                    <!-- Job Application History -->
                    <div class="col-span-1 md:col-span-2 border-t border-slate-100 pt-6">
                        <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Riwayat Lamaran Pekerjaan</h4>
                        <div class="space-y-3">
                            <template x-if="activeApplicant && activeApplicant.applications && activeApplicant.applications.length > 0">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="(app, index) in activeApplicant.applications" :key="index">
                                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <h5 class="text-xs font-extrabold text-slate-800 uppercase" x-text="app.job_title"></h5>
                                                    <p class="text-[10px] text-slate-500 font-semibold mt-0.5" x-text="app.category"></p>
                                                </div>
                                                <span class="shrink-0 text-[9px] font-extrabold px-2.5 py-0.5 rounded-lg border"
                                                      :class="app.is_priority 
                                                          ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                                                          : 'bg-slate-100 text-slate-600 border-slate-200'"
                                                      x-text="app.is_priority ? 'Prioritas (' + app.matching_score + '%)' : 'Non-Prioritas (' + app.matching_score + '%)'"></span>
                                            </div>
                                            <div class="mt-2 text-[10px] text-slate-400 font-semibold">
                                                Diajukan pada: <span class="text-slate-650" x-text="app.applied_at"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!activeApplicant || !activeApplicant.applications || activeApplicant.applications.length === 0">
                                <div class="text-center py-6 bg-slate-50/50 rounded-2xl border border-slate-100/50 text-slate-400 font-semibold text-xs">
                                    Belum mengajukan lamaran pekerjaan apa pun.
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
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
