@extends('layouts.dashboard')

@section('dashboard-title', 'HIRING Management')

@section('dashboard-content')
    <div class="space-y-6 animate-fade-in">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Daftar Lowongan Pekerjaan</h3>
                    <p class="text-sm text-slate-500">Lihat daftar lowongan dan proses pelamar secara read-only.</p>
                </div>
            </div>

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
                                <a href="{{ route('pimpinan.hiring.show', $posting) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#003d7c] to-[#005fb8] text-white text-xs font-bold shadow-md hover:shadow-lg hover:brightness-105 transition-all">
                                    <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Lihat Pelamar
                                </a>
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
                                </div>
                            </div>
                        </div>

                        <!-- Footer Details -->
                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4 mt-2">
                            <div class="flex items-center gap-4 text-xs text-slate-500 font-medium">
                                <span class="flex items-center gap-1.5" title="Total Kuota">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Kuota: <strong class="text-slate-700">{{ $posting->quota }} Orang</strong>
                                </span>
                                <span class="flex items-center gap-1.5" title="SPK Status">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    SPK: <strong class="{{ $posting->spk_status === 'completed' ? 'text-emerald-600' : 'text-slate-700' }}">{{ $posting->spk_status === 'completed' ? 'Selesai' : 'Tertunda' }}</strong>
                                </span>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="py-16 text-center border-2 border-dashed border-slate-200 rounded-[24px]">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <h4 class="text-base font-bold text-slate-600">Belum Ada Lowongan</h4>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Container -->
            <div class="mt-8 flex justify-center">
                {{ $postings->links() }}
            </div>
        </div>
    </div>

    <style>
        /* Custom Pagination Active State Styling */
        nav[role="navigation"] span[aria-current="page"] > span,
        nav[role="navigation"] span[aria-current="page"] {
            background-color: #003d7c !important;
            color: white !important;
            border-color: #003d7c !important;
            font-weight: bold;
        }
    </style>
@endsection
