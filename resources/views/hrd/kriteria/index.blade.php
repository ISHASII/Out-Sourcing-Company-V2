@extends('layouts.dashboard')

@section('dashboard-title', 'Master Data Kriteria')

@section('dashboard-content')
<div class="space-y-6 animate-fade-in" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    editId: null, 
    editLabel: '', 
    editStatus: 'secondary', 
    editWeight: 0,
    openEdit(id, label, status, weight) {
        this.editId = id;
        this.editLabel = label;
        this.editStatus = status;
        this.editWeight = weight;
        this.showEditModal = true;
    }
}">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Kriteria Seleksi SPK</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola daftar kriteria kualifikasi bawaan dan tambahan untuk semua kategori pekerjaan.</p>
        </div>
        
        <button @click="showAddModal = true" 
                class="px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-extrabold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 border border-yellow-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Tambahkan Kriteria Baru</span>
        </button>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 animate-fade-in shadow-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xs font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in">
            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            <div>
                <p class="text-xs font-bold text-rose-700 mb-1">Gagal menyimpan data:</p>
                <ul class="list-disc list-inside text-xs text-rose-600 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h4 class="text-sm font-extrabold text-slate-800">Daftar Seluruh Pengaturan Kriteria</h4>
                <p class="text-[11px] text-slate-400 mt-0.5">Total: {{ $criteria->total() }} Kriteria</p>
            </div>
            
            {{-- Search Bar --}}
            <form action="{{ route('hrd.kriteria.index') }}" method="GET" class="w-full sm:w-72 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kriteria atau kategori..." 
                       class="w-full py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-xs bg-white text-slate-700"
                       style="padding-left: 2.25rem; padding-right: 2.25rem;">
                <span class="absolute inset-y-0 left-0 flex items-center pointer-events-none text-slate-400" style="padding-left: 0.75rem;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                @if(request('search'))
                    <a href="{{ route('hrd.kriteria.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-rose-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/70 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5 font-bold">#</th>
                        <th class="px-6 py-3.5 font-bold">Kategori Pekerjaan</th>
                        <th class="px-6 py-3.5 font-bold">Nama Kriteria (Label)</th>
                        <th class="px-6 py-3.5 font-bold">Key (Slug)</th>
                        <th class="px-6 py-3.5 font-bold">Tipe Input</th>
                        <th class="px-6 py-3.5 font-bold">Status Default</th>
                        <th class="px-6 py-3.5 font-bold">Bobot Default</th>
                        <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($criteria as $i => $c)
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="px-6 py-4 text-slate-400 font-bold text-xs">{{ ($criteria->currentPage() - 1) * $criteria->perPage() + $i + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800 text-xs tracking-tight">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg">
                                    {{ $c->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-850 font-extrabold">{{ $c->label }}</td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $c->key }}</td>
                            <td class="px-6 py-4 text-slate-500 capitalize text-xs">
                                <span class="bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100">{{ $c->type }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold">
                                @if($c->default_status === 'core')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                        Wajib (Core)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                                        Tambahan (Secondary)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700 text-xs">{{ $c->default_weight }}%</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Edit --}}
                                    <button @click="openEdit({{ $c->id }}, '{{ addslashes($c->label) }}', '{{ $c->default_status }}', {{ $c->default_weight }})"
                                            class="p-2 rounded-xl bg-slate-50 text-slate-500 hover:text-[#003d7c] hover:bg-blue-50 transition border border-slate-200/50"
                                            title="Ubah Kriteria">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    {{-- Delete --}}
                                    <form action="{{ route('hrd.kriteria.destroy', $c) }}" method="POST" class="inline"
                                          x-data @submit.prevent="$dispatch('open-confirm-modal', {
                                              title: 'Hapus kriteria seleksi?',
                                              message: 'Kriteria {{ addslashes($c->label) }} akan dihapus secara permanen dari kategori {{ $c->category }}. Tindakan ini tidak dapat dibatalkan.',
                                              confirmText: 'Ya, Hapus',
                                              type: 'danger',
                                              actionType: 'submit',
                                              formElement: $el
                                          })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-xl bg-slate-50 text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200/50"
                                                title="Hapus Kriteria">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">
                                Belum ada kriteria yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($criteria->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-xs text-slate-500 font-semibold flex items-center gap-2">
                    <span>Halaman Aktif:</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-[#003d7c] font-black border border-blue-100">
                        {{ $criteria->currentPage() }} dari {{ $criteria->lastPage() }}
                    </span>
                </div>
                <div class="pagination flex items-center justify-end">
                    {{ $criteria->links() }}
                </div>
            </div>

            <style>
                /* Style active page links correctly to highlight them in blue */
                .pagination [aria-current="page"] > span,
                .pagination [aria-current="page"] > a,
                .pagination [aria-current="page"] > span:hover {
                    background-color: #003d7c !important;
                    color: white !important;
                    border-color: #003d7c !important;
                    font-weight: 800 !important;
                    box-shadow: 0 1px 3px 0 rgba(0, 61, 124, 0.2) !important;
                }
                .pagination nav a, .pagination nav span {
                    border-radius: 0.75rem !important;
                    font-size: 11px !important;
                    font-weight: 700 !important;
                    transition: all 0.2s ease-in-out !important;
                }
                .pagination nav a:hover {
                    background-color: #f8fafc !important;
                    border-color: #cbd5e1 !important;
                }
            </style>
        @endif
    </div>

    {{-- Modal Add --}}
    <div x-show="showAddModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in"
         x-cloak>
         <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 overflow-hidden transform transition"
              @click.away="showAddModal = false">
             <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                 <h4 class="text-sm font-extrabold text-slate-800">Tambahkan Kriteria Baru</h4>
                 <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                 </button>
             </div>
             <form action="{{ route('hrd.kriteria.store') }}" method="POST" class="p-6 space-y-4">
                 @csrf

                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Nama Kriteria <span class="text-rose-500">*</span></label>
                     <input type="text" name="label" required placeholder="Contoh: Kartu Keluarga, SKCK"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm">
                 </div>

                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Tipe Input Form <span class="text-rose-500">*</span></label>
                     <select name="type" required
                             class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm bg-white">
                         <option value="file" selected>Input Dokumen (Upload File)</option>
                         <option value="text">Input Teks (Typing)</option>
                         <option value="checkbox">Input Checkbox (Yes/No)</option>
                         <option value="number">Input Angka (Number)</option>
                     </select>
                 </div>

                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Status Default <span class="text-rose-500">*</span></label>
                     <select name="default_status" required
                             class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm bg-white">
                         <option value="secondary" selected>Tambahan (Secondary Factor)</option>
                         <option value="core">Wajib (Core Factor)</option>
                     </select>
                 </div>

                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Bobot Default (%) <span class="text-rose-500">*</span></label>
                     <input type="number" name="default_weight" min="0" max="100" value="10" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm">
                 </div>

                 <div class="flex items-center justify-end gap-3 pt-2">
                     <button type="button" @click="showAddModal = false"
                             class="px-4 py-2.5 border border-slate-200 rounded-xl text-slate-500 font-semibold text-xs hover:bg-slate-50 transition">
                         Batal
                     </button>
                     <button type="submit"
                             class="px-5 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-extrabold text-xs rounded-xl shadow-xs transition-all border border-yellow-500/20">
                         Tambahkan
                     </button>
                 </div>
             </form>
         </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="showEditModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in"
         x-cloak>
         <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 overflow-hidden transform transition"
              @click.away="showEditModal = false">
             <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                 <h4 class="text-sm font-extrabold text-slate-800">Ubah Kriteria</h4>
                 <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                 </button>
             </div>
             <form :action="'{{ route('hrd.kriteria.index') }}/' + editId" method="POST" class="p-6 space-y-4">
                 @csrf
                 @method('PUT')

                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Nama Kriteria (Label) <span class="text-rose-500">*</span></label>
                     <input type="text" name="label" x-model="editLabel" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm">
                 </div>

                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Status Default <span class="text-rose-500">*</span></label>
                     <select name="default_status" x-model="editStatus" required
                             class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm bg-white">
                         <option value="core">Wajib (Core Factor)</option>
                         <option value="secondary">Tambahan (Secondary Factor)</option>
                     </select>
                 </div>

                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Bobot Default (%) <span class="text-rose-500">*</span></label>
                     <input type="number" name="default_weight" x-model="editWeight" min="0" max="100" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm">
                 </div>

                 <div class="flex items-center justify-end gap-3 pt-2">
                     <button type="button" @click="showEditModal = false"
                             class="px-4 py-2.5 border border-slate-200 rounded-xl text-slate-500 font-semibold text-xs hover:bg-slate-50 transition">
                         Batal
                     </button>
                     <button type="submit"
                             class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all border border-blue-600/20">
                         Simpan Perubahan
                     </button>
                 </div>
             </form>
         </div>
    </div>
</div>
@endsection
