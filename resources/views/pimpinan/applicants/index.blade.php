@extends('layouts.dashboard')

@section('dashboard-title', 'Daftar Pelamar Diterima')

@section('dashboard-content')
<div class="space-y-8 animate-fade-in">

    {{-- Page Header --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-[#002855] to-[#004b93] text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="relative z-10 space-y-2">
            <span class="bg-blue-500/25 text-blue-200 text-[10px] font-extrabold px-3.5 py-1 rounded-lg uppercase tracking-widest border border-blue-400/20">Data Pelamar (Read-Only)</span>
            <h1 class="text-3xl font-extrabold tracking-tight">Kandidat Diterima Kerja</h1>
            <p class="text-blue-100/80 max-w-xl text-xs leading-relaxed">Daftar seluruh pelamar yang telah disetujui setelah tahap wawancara beserta penempatan kerjanya.</p>
        </div>
    </div>

    {{-- Applicants Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <h4 class="text-sm font-bold text-slate-700">Riwayat Penempatan Kandidat</h4>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Status: Diterima
                </span>
            </div>
            
            <form method="GET" action="{{ route('pimpinan.applicants') }}" class="flex items-center gap-2">
                <select name="mitra_id" class="text-sm border border-slate-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-blue-500 bg-slate-50">
                    <option value="">-- Semua Mitra Kerja --</option>
                    @foreach($mitras as $mitra)
                        <option value="{{ $mitra->id }}" @selected(request('mitra_id') == $mitra->id)>{{ $mitra->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-xl text-sm font-semibold transition-colors">
                    Filter
                </button>
            </form>
        </div>

        @if($acceptedApplicants->isEmpty())
        <div class="py-16 text-center">
            <svg class="w-14 h-14 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-slate-400 text-sm font-semibold">Belum ada kandidat yang tercatat diterima kerja.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/70 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 text-left font-bold">#</th>
                        <th class="px-6 py-3.5 text-left font-bold">Nama Pelamar</th>
                        <th class="px-6 py-3.5 text-left font-bold">Lowongan (Posisi)</th>
                        <th class="px-6 py-3.5 text-left font-bold">Mitra</th>
                        <th class="px-6 py-3.5 text-left font-bold">Tanggal Diterima</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($acceptedApplicants as $i => $app)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-slate-400 font-semibold">{{ $acceptedApplicants->firstItem() + $i }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#003d7c] to-blue-400 flex items-center justify-center text-white text-xs font-extrabold shrink-0">
                                    {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-800 block">{{ $app->user->name }}</span>
                                    <span class="text-xs text-slate-500">{{ $app->user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-700 block">{{ $app->posting->title }}</span>
                            <span class="text-xs text-slate-400">{{ $app->posting->category }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700 text-sm block">{{ $app->posting->mitra_name }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-medium text-xs">
                            {{ $app->updated_at->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        {{-- Pagination --}}
        @if($acceptedApplicants->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $acceptedApplicants->links() }}
        </div>
        @endif
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

