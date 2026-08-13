@extends('layouts.dashboard')

@section('dashboard-title', 'Data Mitra')

@section('dashboard-content')
<div class="space-y-8 animate-fade-in">

    {{-- Page Header --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-[#002855] to-[#004b93] text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="relative z-10 space-y-2">
            <span class="bg-blue-500/25 text-blue-200 text-[10px] font-extrabold px-3.5 py-1 rounded-lg uppercase tracking-widest border border-blue-400/20">Data Mitra (Read-Only)</span>
            <h1 class="text-3xl font-extrabold tracking-tight">Mitra Strategis & Partner</h1>
            <p class="text-blue-100/80 max-w-xl text-xs leading-relaxed">Daftar mitra dan partner terpercaya PT. Unggul Cipta Indah.</p>
        </div>
    </div>

    {{-- Partners Grid --}}
    @if($partners->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-16 text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-blue-50 rounded-3xl flex items-center justify-center">
            <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Data Mitra</h3>
        <p class="text-sm text-slate-400 mb-6">HRD belum menambahkan data mitra strategis.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($partners as $mitra)
        <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">
            {{-- Logo Display --}}
            <div class="relative h-40 bg-gradient-to-br from-slate-50 to-blue-50/30 flex items-center justify-center p-6 overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(0,61,124,0.03),transparent_70%)]"></div>
                <img src="{{ asset('storage/' . $mitra->logo_path) }}"
                     alt="{{ $mitra->name }}"
                     class="max-h-24 max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500 relative z-10">
            </div>

            {{-- Info & Actions --}}
            <div class="p-4 flex-grow flex flex-col justify-between gap-3">
                <div>
                    <p class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug">{{ $mitra->name }}</p>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Ditambahkan {{ $mitra->created_at->locale('id')->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($partners->hasPages())
    <div class="flex justify-center">
        {{ $partners->links() }}
    </div>
    @endif
    @endif

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
