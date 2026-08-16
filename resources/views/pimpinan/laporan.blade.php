@extends('layouts.dashboard')

@section('dashboard-title', 'Laporan Admin')

@section('dashboard-content')
<div class="space-y-8 animate-fade-in">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-[#003d7c]">Laporan Admin</h3>
            <p class="text-sm text-slate-400 mt-1">Daftar laporan hasil Profile Matching yang dikirimkan oleh HRD.</p>
        </div>
    </div>

    {{-- Laporan Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h4 class="text-sm font-bold text-slate-700">Riwayat Laporan</h4>
            
            <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
                <label for="admin_id" class="text-xs font-bold text-slate-500 uppercase tracking-wider">Filter Admin:</label>
                <select name="admin_id" id="admin_id" onchange="this.form.submit()" class="text-sm border-slate-200 rounded-lg px-3 py-1.5 focus:ring-[#003d7c] focus:border-[#003d7c]">
                    <option value="">Semua Admin (HRD)</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($reports->isEmpty())
        <div class="py-16 text-center">
            <svg class="w-14 h-14 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-slate-400 text-sm font-semibold">Belum ada laporan yang dikirimkan.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/70 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 text-left font-bold">#</th>
                        <th class="px-6 py-3.5 text-left font-bold">Lowongan</th>
                        <th class="px-6 py-3.5 text-left font-bold">Judul Laporan</th>
                        <th class="px-6 py-3.5 text-left font-bold">Tanggal Dikirim</th>
                        <th class="px-6 py-3.5 text-center font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($reports as $i => $report)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-slate-400 font-semibold">{{ $reports->firstItem() + $i }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $report->jobPosting->title ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium">
                            {{ $report->report_title }}
                        </td>
                        <td class="px-6 py-4 text-slate-400 text-xs">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ asset('storage/' . $report->pdf_path) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-[#003d7c] rounded-lg text-xs font-bold hover:bg-[#003d7c] hover:text-white transition-colors border border-blue-100 hover:border-[#003d7c]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $reports->links() }}
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
