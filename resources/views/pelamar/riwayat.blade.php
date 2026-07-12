@extends('layouts.dashboard')

@section('dashboard-title', 'Lamaran Saya')

@section('dashboard-content')
    <div class="space-y-6 animate-fade-in"
         x-data="{
             page: 1,
             perPage: 10,
             totalItems: {{ $applications->count() }},
             showRow(index) {
                 const start = (this.page - 1) * this.perPage;
                 const end = start + this.perPage;
                 return index >= start && index < end;
             }
         }">
        
        <!-- Premium Hero Header -->
        <div class="relative bg-gradient-to-r from-[#003d7c] to-[#005fb8] rounded-3xl p-8 overflow-hidden shadow-lg border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(255,255,255,0.08),transparent_50%)]"></div>
            <div class="relative z-10 max-w-3xl">
                <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black text-blue-100 bg-white/10 backdrop-blur-md uppercase tracking-widest">Portal Pelamar</span>
                <h3 class="text-2xl md:text-3xl font-extrabold text-white mt-4 leading-tight">Lamaran Saya</h3>
                <p class="text-xs md:text-sm text-blue-100/90 mt-2 leading-relaxed">Pantau proses evaluasi berkas lamaran Anda secara real-time dan transparan.</p>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full border-[10px] border-white/5 opacity-20 pointer-events-none"></div>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-md shadow-slate-100/60 relative overflow-hidden">
            <!-- Decorative gradient banner top -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#003d7c] to-[#005fb8]"></div>

            <div class="flex items-center justify-between pb-6 mb-8 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan Lamaran</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Seluruh berkas Anda dievaluasi menggunakan Sistem SPK Prioritas.</p>
                </div>
                <span class="text-xs font-black text-[#003d7c] bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100/50">
                    {{ $applications->count() }} Lamaran
                </span>
            </div>

            <div class="space-y-6">
                @forelse($applications as $application)
                    <div x-show="showRow({{ $loop->index }})"
                         class="border border-slate-200 hover:border-[#003d7c]/30 hover:shadow-md rounded-2xl p-6 transition-all duration-300 relative overflow-hidden group bg-slate-50/20">
                        <!-- Card Left Accent stripe on hover -->
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#003d7c] to-[#005fb8] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            
                            <!-- Left: Job Details & Info -->
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <!-- Monogram Initial -->
                                <div class="w-12 h-12 rounded-xl bg-[#005fb8] text-white flex items-center justify-center font-black text-base shadow-sm shrink-0 uppercase tracking-wider">
                                    <span>{{ substr($application->posting->title, 0, 2) }}</span>
                                </div>
                                <div class="min-w-0 flex-1 space-y-1.5">
                                    <h4 class="text-base font-bold text-slate-800 leading-snug truncate group-hover:text-[#003d7c] transition-colors">
                                        {{ $application->posting->title }}
                                    </h4>
                                    
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                        <span class="text-xs font-extrabold text-[#003d7c] uppercase tracking-wider">{{ $application->posting->category }}</span>
                                        <span class="text-slate-350 text-xs hidden sm:inline">•</span>
                                        <!-- Location City -->
                                        <span class="inline-flex items-center text-xs text-slate-500 font-bold bg-slate-100/70 border border-slate-200/40 px-2.5 py-0.5 rounded-lg">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $application->posting->location_city ?? 'Seluruh Area (Bebas)' }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 pt-1.5 text-xs text-slate-500 font-bold">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Terdaftar: {{ $application->created_at->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span>Usia Pelamar: {{ $application->age ?? '-' }} Tahun</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Immersive Custom Timeline Status Flow (Extremely Premium) -->
                            <div class="lg:w-80 shrink-0 bg-slate-50 border border-slate-200/60 rounded-xl p-4 flex flex-col justify-center space-y-3.5 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Status Lamaran</span>
                                </div>

                                <!-- Horizontal Flow -->
                                <div class="relative flex items-center justify-between pt-1">
                                    <!-- Flow Line -->
                                    <div class="absolute left-4 right-4 top-2 h-0.5 bg-slate-200 -z-0">
                                        <!-- Active Progress -->
                                        @if($application->status === 'pending')
                                            <div class="w-1/2 h-full bg-[#005fb8]"></div>
                                        @else
                                            <div class="w-full h-full bg-emerald-500"></div>
                                        @endif
                                    </div>

                                    <!-- Step 1: Terkirim -->
                                    <div class="relative z-10 flex flex-col items-center space-y-1">
                                        <div class="w-4.5 h-4.5 rounded-full bg-emerald-500 text-white border-2 border-white flex items-center justify-center shadow-sm" style="width: 18px; height: 18px;">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 10px; height: 10px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <span class="text-[9px] font-black text-slate-700 uppercase tracking-wider">Terkirim</span>
                                    </div>

                                    <!-- Step 2: Evaluasi -->
                                    <div class="relative z-10 flex flex-col items-center space-y-1">
                                        @if($application->status === 'pending')
                                            <div class="w-4.5 h-4.5 rounded-full bg-[#005fb8] text-white border-2 border-white flex items-center justify-center shadow-md animate-pulse" style="width: 18px; height: 18px;">
                                                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                            </div>
                                            <span class="text-[9px] font-black text-[#005fb8] uppercase tracking-wider">Evaluasi</span>
                                        @else
                                            <div class="w-4.5 h-4.5 rounded-full bg-emerald-500 text-white border-2 border-white flex items-center justify-center shadow-sm" style="width: 18px; height: 18px;">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 10px; height: 10px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-[9px] font-black text-slate-750 uppercase tracking-wider">Evaluasi</span>
                                        @endif
                                    </div>

                                    <!-- Step 3: Hasil -->
                                    <div class="relative z-10 flex flex-col items-center space-y-1">
                                        @if($application->status === 'accepted')
                                            <div class="w-4.5 h-4.5 rounded-full bg-emerald-500 text-white border-2 border-white flex items-center justify-center shadow-sm" style="width: 18px; height: 18px;">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 10px; height: 10px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-wider">Diterima</span>
                                        @elseif($application->status === 'rejected')
                                            <div class="w-4.5 h-4.5 rounded-full bg-rose-500 text-white border-2 border-white flex items-center justify-center shadow-sm" style="width: 18px; height: 18px;">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 10px; height: 10px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                            <span class="text-[9px] font-black text-rose-600 uppercase tracking-wider">Ditolak</span>
                                        @else
                                            <div class="w-4.5 h-4.5 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center shadow-sm" style="width: 18px; height: 18px;">
                                            </div>
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Hasil</span>
                                        @endif
                                    </div>
                                </div>

                                @if($application->status === 'pending' && $application->posting->is_active && (!$application->posting->active_until || $application->posting->active_until->gte(now()->startOfDay())))
                                    <div class="pt-2 border-t border-slate-200/60 mt-1">
                                        <a href="{{ route('pelamar.lowongan.apply', $application->posting) }}" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#003d7c]/90 to-[#005fb8]/90 hover:from-[#003d7c] hover:to-[#005fb8] text-white font-black text-[10px] uppercase rounded-xl transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Perbarui Lamaran
                                        </a>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="border-2 border-dashed border-slate-200 rounded-3xl p-12 text-center shadow-inner space-y-4 max-w-lg mx-auto bg-slate-50/50">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto border border-slate-200/60 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-800">Belum Ada Riwayat Lamaran</h4>
                            <p class="text-xs text-slate-550 max-w-xs mx-auto leading-relaxed mt-1">Anda belum mengirimkan lamaran pekerjaan untuk posisi apa pun. Mulai cari karier impian Anda sekarang!</p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('pelamar.lowongan') }}" class="inline-flex items-center gap-1.5 px-6 py-3 rounded-2xl bg-gradient-to-r from-[#003d7c] to-[#005fb8] text-white text-xs font-black uppercase shadow-md shadow-blue-900/10 hover:shadow-lg hover:brightness-105 active:scale-95 transition-all">
                                Cari Lowongan Pekerjaan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Controls (10 items per page) -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500" x-show="totalItems > perPage">
                <div>
                    Menampilkan 
                    <span class="text-slate-800" x-text="Math.min((page - 1) * perPage + 1, totalItems)"></span> 
                    - 
                    <span class="text-slate-800" x-text="Math.min(page * perPage, totalItems)"></span> 
                    dari 
                    <span class="text-slate-800" x-text="totalItems"></span> lamaran
                </div>
                <div class="flex items-center gap-1">
                    <button 
                        @click="if(page > 1) { page-- }" 
                        :disabled="page === 1"
                        class="px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:hover:bg-white transition-all duration-200"
                        :class="page === 1 ? 'cursor-not-allowed text-slate-350' : 'text-slate-600 hover:text-slate-800'"
                    >
                        Sebelumnya
                    </button>
                    
                    <template x-for="p in Math.ceil(totalItems / perPage)" :key="p">
                        <button 
                            @click="page = p" 
                            class="w-8 h-8 rounded-xl flex items-center justify-center border font-bold transition-all duration-200"
                            :class="page === p 
                                ? 'bg-[#003d7c] border-[#003d7c] text-white shadow-md shadow-blue-900/15' 
                                : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800'"
                            x-text="p"
                        ></button>
                    </template>

                    <button 
                        @click="if(page < Math.ceil(totalItems / perPage)) { page++ }" 
                        :disabled="page === Math.ceil(totalItems / perPage)"
                        class="px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:hover:bg-white transition-all duration-200"
                        :class="page === Math.ceil(totalItems / perPage) ? 'cursor-not-allowed text-slate-350' : 'text-slate-600 hover:text-slate-800'"
                    >
                        Selanjutnya
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection
