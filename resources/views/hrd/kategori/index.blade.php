@extends('layouts.dashboard')

@section('dashboard-title', 'Master Kategori Pekerjaan')

@section('dashboard-content')
<div class="space-y-6 animate-fade-in" x-data="{ 
    showAddModal: false
}">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 tracking-tight">Kategori Pekerjaan</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola daftar posisi & kategori pekerjaan utama yang digunakan pada pembuatan lowongan dan profil pengalaman pelamar.</p>
        </div>
        
        <button @click="showAddModal = true" 
                class="px-4 py-2.5 bg-[#003d7c] hover:bg-blue-900 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 border border-blue-800/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Kategori Baru</span>
        </button>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 animate-fade-in shadow-xs">
            <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-xs font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-xs animate-fade-in">
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

    {{-- Main Card --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h4 class="text-sm font-extrabold text-slate-800">Daftar Kategori Pekerjaan Registered</h4>
                <p class="text-[11px] text-slate-400 mt-0.5">Total: {{ $categories->total() }} Kategori</p>
            </div>
            
            {{-- Search Bar --}}
            <form action="{{ route('hrd.kategori.index') }}" method="GET" class="w-full sm:w-72 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..." 
                       class="w-full py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-xs bg-white text-slate-700"
                       style="padding-left: 2.25rem; padding-right: 2.25rem;">
                <span class="absolute inset-y-0 left-0 flex items-center pointer-events-none text-slate-400" style="padding-left: 0.75rem;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                @if(request('search'))
                    <a href="{{ route('hrd.kategori.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-rose-500">
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
                        <th class="px-6 py-3.5 font-bold">Nama Kategori Pekerjaan</th>
                        <th class="px-6 py-3.5 font-bold text-center">Lowongan Dibuat</th>
                        <th class="px-6 py-3.5 font-bold text-center">Status</th>
                        <th class="px-6 py-3.5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $i => $cat)
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="px-6 py-4 text-slate-400 font-bold text-xs">{{ ($categories->currentPage() - 1) * $categories->perPage() + $i + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800 text-sm">
                                {{ $cat->name }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700 text-xs">
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $postingsCount[$cat->name] ?? 0 }} Lowongan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-xs font-bold">
                                @if($cat->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('hrd.kategori.destroy', $cat->id) }}" method="POST" class="inline"
                                          x-data @submit.prevent="$dispatch('open-confirm-modal', {
                                              title: 'Hapus Kategori Pekerjaan?',
                                              message: 'Apakah Anda yakin ingin menghapus kategori {{ addslashes($cat->name) }}? Menghapus ini dapat memengaruhi pilihan opsi di profil pelamar.',
                                              confirmText: 'Ya, Hapus Kategori',
                                              type: 'danger',
                                              actionType: 'submit',
                                              formElement: $el
                                          })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200/50"
                                                title="Hapus Kategori">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">
                                Belum ada kategori pekerjaan yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-xs text-slate-500 font-semibold flex items-center gap-2">
                    <span>Halaman:</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-[#003d7c] font-black border border-blue-100">
                        {{ $categories->currentPage() }} dari {{ $categories->lastPage() }}
                    </span>
                </div>
                <div class="pagination flex items-center justify-end">
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Add --}}
    <div x-show="showAddModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fade-in"
         x-cloak
         style="display: none;">
         <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl border border-slate-100 overflow-hidden transform transition"
              @click.away="showAddModal = false">
             <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                 <h4 class="text-sm font-extrabold text-slate-800">Tambah Kategori Pekerjaan Baru</h4>
                 <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                 </button>
             </div>
             <form action="{{ route('hrd.kategori.store') }}" method="POST" class="p-6 space-y-4">
                 @csrf
                 <div>
                     <label class="text-xs font-bold text-slate-600 block mb-1.5">Nama Kategori Pekerjaan <span class="text-rose-500">*</span></label>
                     <input type="text" name="name" required placeholder="Contoh: Programmer, Driver Medis, HR Officer"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c] text-sm">
                 </div>

                 <div class="flex items-center justify-end gap-3 pt-2">
                     <button type="button" @click="showAddModal = false"
                             class="px-4 py-2.5 border border-slate-200 rounded-xl text-slate-500 font-semibold text-xs hover:bg-slate-50 transition">
                         Batal
                     </button>
                     <button type="submit"
                             class="px-5 py-2.5 bg-[#003d7c] hover:bg-blue-900 text-white font-extrabold text-xs rounded-xl shadow-xs transition-all border border-blue-900/20">
                         Simpan Kategori
                     </button>
                 </div>
             </form>
         </div>
    </div>
</div>
@endsection
