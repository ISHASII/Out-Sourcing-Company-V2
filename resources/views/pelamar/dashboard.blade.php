@extends('layouts.dashboard')

@section('dashboard-title', 'Dashboard User')

@section('dashboard-content')
<div class="space-y-6 animate-fade-in">

    <!-- Hero / Welcome Banner -->
    <div class="bg-gradient-to-r from-[#003d7c] to-[#005fb8] rounded-3xl p-8 relative overflow-hidden shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-400 opacity-10 rounded-full blur-2xl translate-y-1/3 -translate-x-1/4"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="text-white">
                <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-bold tracking-wider uppercase mb-3 backdrop-blur-sm border border-white/30">Pelamar Portal</span>
                <h2 class="text-3xl font-extrabold mb-2">Halo, {{ Auth::user()->name }}!</h2>
                <p class="text-blue-100 max-w-xl text-sm leading-relaxed">Selamat datang di Portal Rekrutmen PT. Unggul Cipta Indah. Jelajahi peluang karir terbaru dan tingkatkan wawasanmu melalui artikel dan sesi berbagi seputar dunia kerja.</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex gap-4">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center min-w-[100px]">
                    <h4 class="text-2xl font-black text-white">{{ $activeApplicationsCount }}</h4>
                    <p class="text-[10px] text-blue-100 uppercase tracking-widest mt-1">Lamaran Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Lowongan Pekerjaan (Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-extrabold text-[#003d7c]">Lowongan Pekerjaan Tersedia</h3>
                <a href="{{ route('pelamar.lowongan') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">Lihat Semua &rarr;</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($postings as $posting)
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all group relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -z-10 group-hover:bg-blue-100 transition-colors"></div>
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100">
                                    <span class="font-black text-[#003d7c] text-sm uppercase">{{ substr($posting->category ?? 'IT', 0, 3) }}</span>
                                </div>
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wider">{{ $posting->shift_type ?? 'Full-Time' }}</span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-base group-hover:text-[#003d7c] transition-colors line-clamp-1">{{ $posting->title }}</h4>
                            <p class="text-xs text-slate-500 mt-1 mb-4">PT. Unggul Cipta Indah • {{ $posting->location_city ?? 'Jakarta' }}</p>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                            <span class="text-xs font-semibold text-slate-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $posting->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('pelamar.lowongan.apply', $posting) }}" class="text-xs font-bold text-white bg-[#003d7c] hover:bg-blue-800 px-4 py-2 rounded-lg transition-colors">Lamar</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 p-8 text-center bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm font-bold text-slate-400">Belum ada lowongan pekerjaan tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Sharing Sessions / Info -->
        <div class="space-y-6">
            {{-- <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-extrabold text-[#003d7c]">Dunia Pekerjaan</h3>
            </div>
            
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <!-- Article 1 -->
                <a href="#" class="flex gap-4 p-4 hover:bg-slate-50 border-b border-slate-100 transition-colors group">
                    <div class="w-20 h-20 bg-slate-200 rounded-xl overflow-hidden shrink-0 relative">
                        <img src="https://images.unsplash.com/photo-1552581234-26160f608093?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Meeting" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Tips Karir</span>
                        <h4 class="font-bold text-slate-800 text-sm mt-1 leading-tight group-hover:text-[#003d7c]">Cara Sukses Menghadapi Interview HRD</h4>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">Pelajari teknik komunikasi yang efektif dan persiapan mental yang dibutuhkan.</p>
                    </div>
                </a>
                
                <!-- Article 2 -->
                <a href="#" class="flex gap-4 p-4 hover:bg-slate-50 transition-colors group">
                    <div class="w-20 h-20 bg-slate-200 rounded-xl overflow-hidden shrink-0 relative">
                        <img src="https://images.unsplash.com/photo-1517048676732-d65bc937f952?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Working" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">Sharing Session</span>
                        <h4 class="font-bold text-slate-800 text-sm mt-1 leading-tight group-hover:text-[#003d7c]">Membangun Relasi di Lingkungan Kerja</h4>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">Pentingnya networking dan cara beradaptasi dengan budaya perusahaan baru.</p>
                    </div>
                </a>
            </div> --}}
            
            <!-- Quick Profile Completion Hint -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 p-5 rounded-2xl flex items-start gap-4">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 shrink-0 mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-amber-900">Lengkapi Profil Anda</h4>
                    <p class="text-xs text-amber-700 mt-1 mb-3">Pastikan data diri, dokumen, dan pengalaman kerja Anda sudah lengkap sebelum melamar.</p>
                    <a href="{{ route('pelamar.profil') }}" class="text-xs font-bold text-amber-900 bg-amber-200 hover:bg-amber-300 px-3 py-1.5 rounded-lg transition-colors inline-block">Update Profil</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
