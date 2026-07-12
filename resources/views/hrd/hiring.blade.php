@extends('layouts.dashboard')

@section('dashboard-title', 'HIRING Management')

@section('dashboard-content')
    <div class="space-y-6 animate-fade-in">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Input Requirement SDM & Posting Lowongan</h3>
                    <p class="text-sm text-slate-500">Kelola lowongan dan lihat prioritas pelamar.</p>
                </div>
                <a href="{{ route('hrd.hiring.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#003d7c] text-white text-sm font-semibold hover:bg-[#003164] transition">
                    <span>Tambah Lowongan</span>
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 px-4 py-3 rounded-xl bg-rose-50 text-rose-700 text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6">
                @forelse($postings as $posting)
                    <div class="border border-slate-200/80 bg-white hover:border-[#003d7c]/30 hover:shadow-lg p-6 rounded-[24px] transition-all duration-300 relative overflow-hidden group shadow-sm flex flex-col gap-4">
                        
                        <!-- Left Border Accent on Hover -->
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-[#003d7c] to-[#005fb8] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Card Header (Title & Actions) -->
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                            <div class="flex items-center gap-4">
                                <!-- Initial Monogram Logo -->
                                <div class="w-14 h-14 rounded-2xl {{ $posting->is_active ? 'bg-gradient-to-tr from-[#003d7c] to-[#005fb8]' : 'bg-slate-300' }} text-white flex items-center justify-center font-black text-lg shadow-sm shrink-0 uppercase tracking-widest transition-colors duration-300">
                                    <span>{{ substr($posting->title, 0, 2) }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2.5">
                                        <h4 class="text-lg font-bold {{ $posting->is_active ? 'text-slate-800' : 'text-slate-400' }} leading-snug transition-colors">{{ $posting->title }}</h4>
                                        <!-- Active/Inactive Status Badge -->
                                        @if($posting->is_active)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black text-slate-400 bg-slate-100 border border-slate-200 px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 inline-block"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                    <!-- Category and Deadline inline -->
                                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500 font-semibold">
                                        <span>Kategori: <strong class="text-slate-700 font-bold">{{ $posting->category }}</strong></span>
                                        @if($posting->active_until)
                                            <span class="text-slate-300">•</span>
                                            <span class="flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Batas: <strong class="text-slate-700 font-bold ml-1">{{ $posting->active_until->format('d M Y') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap items-center gap-2.5">
                                <a href="{{ route('hrd.hiring.show', $posting) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#003d7c] to-[#005fb8] text-white text-xs font-bold shadow-md hover:shadow-lg hover:brightness-105 transition-all">
                                    <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Lihat Pelamar
                                </a>

                                {{-- Toggle Active / Inactive Button --}}
                                <form action="{{ route('hrd.hiring.toggle', $posting) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($posting->is_active)
                                        {{-- Currently ACTIVE → button to DEACTIVATE --}}
                                        <button type="submit"
                                            title="Nonaktifkan loker ini (tidak tampil ke pelamar)"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold hover:bg-amber-100 hover:border-amber-300 transition-all">
                                            {{-- Toggle ON icon --}}
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            Nonaktifkan
                                        </button>
                                    @else
                                        {{-- Currently INACTIVE → button to ACTIVATE --}}
                                        <button type="submit"
                                            title="Aktifkan loker ini (tampil ke pelamar)"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold hover:bg-emerald-100 hover:border-emerald-300 transition-all">
                                            {{-- Toggle OFF icon --}}
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Aktifkan
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('hrd.hiring.edit', $posting) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold hover:bg-blue-100 hover:border-blue-200 transition-all">
                                    <svg class="w-4 h-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                                <form action="{{ route('hrd.hiring.destroy', $posting) }}" method="POST"
                                    onsubmit="return confirm('Hapus lowongan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-50 border border-rose-100 text-rose-700 text-xs font-bold hover:bg-rose-100 hover:border-rose-200 transition-all">
                                        <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Qualifications SPK Grid -->
                        <div class="grid lg:grid-cols-2 gap-6 mt-2">
                            <!-- Core Factors Block -->
                            <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kriteria Utama (Core Factors)</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#003d7c]/10 text-[#003d7c]">SPK Wajib</span>
                                </div>
                                
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <!-- Gender -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">Gender</p>
                                            <p class="text-xs font-bold text-slate-700 mt-0.5">
                                                @if($posting->core_gender === 'both') Pria & Wanita @elseif($posting->core_gender === 'male') Pria @else Wanita @endif
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Usia -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">Batasan Usia</p>
                                            <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $posting->core_min_age }} - {{ $posting->core_max_age }} Tahun</p>
                                        </div>
                                    </div>

                                    <!-- Pendidikan -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">Min. Pendidikan</p>
                                            <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $posting->core_min_education }}</p>
                                        </div>
                                    </div>

                                    <!-- AGD -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">Sertifikat AGD</p>
                                            <p class="text-xs font-bold mt-0.5 {{ $posting->core_requires_agd ? 'text-rose-600' : 'text-slate-500' }}">
                                                {{ $posting->core_requires_agd ? 'Wajib' : 'Tidak Wajib' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- SIM C -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">SIM C</p>
                                            <p class="text-xs font-bold mt-0.5 {{ $posting->core_requires_sim_c ? 'text-rose-600' : 'text-slate-500' }}">
                                                {{ $posting->core_requires_sim_c ? 'Wajib' : 'Tidak Wajib' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- SIM B1 -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">SIM B1</p>
                                            <p class="text-xs font-bold mt-0.5 {{ $posting->core_requires_sim_b1 ? 'text-rose-600' : 'text-slate-500' }}">
                                                {{ $posting->core_requires_sim_b1 ? 'Wajib' : 'Tidak Wajib' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Secondary Factors Block -->
                            <div class="bg-slate-50/50 rounded-2xl border border-slate-100 p-5 space-y-4">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-200/60">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kriteria Tambahan (Secondary Factors)</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#005fb8]/10 text-[#005fb8]">Bobot SPK</span>
                                </div>
                                
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <!-- Pengalaman -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">Min. Pengalaman</p>
                                            <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $posting->second_min_experience }} Tahun</p>
                                        </div>
                                    </div>

                                    <!-- Penempatan -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">Siap Ditempatkan</p>
                                            <p class="text-xs font-bold mt-0.5 {{ $posting->second_requires_placement_ready ? 'text-[#003d7c]' : 'text-slate-500' }}">
                                                {{ $posting->second_requires_placement_ready ? 'Wajib (Kriteria SPK)' : 'Tidak Wajib' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Lokasi Penempatan -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/50">
                                            <svg class="w-4 h-4 shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-slate-400">Lokasi Penempatan</p>
                                            <p class="text-xs font-bold text-slate-700 mt-0.5">
                                                {{ $posting->second_requires_placement_ready ? 'Sesuai Kebutuhan UCI' : ($posting->location_city ?? '-') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Shift Kerja -->
                                    @if($posting->shift_type)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/50">
                                                <svg class="w-4 h-4 shrink-0 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-semibold text-slate-400">Shift Kerja</p>
                                                <p class="text-xs font-bold text-slate-700 mt-0.5">{{ $posting->shift_type === 'shift' ? 'Shift' : 'Non Shift' }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Estimasi Gaji -->
                                    @if(!$posting->salary_hidden && $posting->salary_min && $posting->salary_max)
                                        <div class="flex items-center gap-3 col-span-2">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100/50">
                                                <svg class="w-4 h-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-semibold text-slate-400">Estimasi Rentang Gaji</p>
                                                <p class="text-xs font-bold text-emerald-700 mt-0.5">Rp {{ number_format($posting->salary_min, 0, ',', '.') }} - Rp {{ number_format($posting->salary_max, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="border-2 border-dashed border-slate-200 rounded-[20px] p-12 text-center space-y-4">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto border border-slate-100 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4m16 4H4a2 2 0 01-2-2V7a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-800">Belum Ada Lowongan</h4>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto leading-relaxed">Klik "Tambah Lowongan" di kanan atas untuk membuat posting dan kualifikasi baru.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
