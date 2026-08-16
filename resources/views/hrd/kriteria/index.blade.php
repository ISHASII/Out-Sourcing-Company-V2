@extends('layouts.dashboard')

@section('dashboard-title', 'Pilih Kategori Pekerjaan')

@section('dashboard-content')
<div class="space-y-6 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Kriteria Seleksi per Kategori</h3>
            <p class="text-xs text-slate-500 mt-1">Pilih kategori pekerjaan di bawah ini untuk melihat dan menambahkan kriteria khusus.</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 shadow-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xs font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Categories Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <a href="{{ route('hrd.kriteria.show', $category->name) }}" class="group block bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:border-[#003d7c]/30 transition-all">
                <div class="flex items-start justify-between">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#003d7c] flex items-center justify-center font-bold mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-100 text-slate-500 group-hover:bg-[#003d7c] group-hover:text-white transition-colors">
                        {{ $category->criteria_count }} Kriteria
                    </span>
                </div>
                <h4 class="text-lg font-extrabold text-slate-800 tracking-tight group-hover:text-[#003d7c] transition-colors">{{ $category->name }}</h4>
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    Kelola Kriteria <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </p>
            </a>
        @empty
            <div class="col-span-full p-8 text-center text-slate-400 bg-slate-50 rounded-3xl border border-slate-100 border-dashed">
                <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="text-sm font-bold text-slate-600">Belum ada kategori pekerjaan aktif.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
