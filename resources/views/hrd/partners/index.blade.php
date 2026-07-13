@extends('layouts.dashboard')

@section('dashboard-title', 'Data Mitra')

@section('dashboard-content')
<div class="space-y-8 animate-fade-in">

    {{-- Page Header --}}
    <div class="relative overflow-hidden bg-gradient-to-r from-[#002855] to-[#004b93] text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="relative z-10 space-y-2">
            <span class="bg-blue-500/25 text-blue-200 text-[10px] font-extrabold px-3.5 py-1 rounded-lg uppercase tracking-widest border border-blue-400/20">Manajemen Mitra</span>
            <h1 class="text-3xl font-extrabold tracking-tight">Mitra Strategis & Partner</h1>
            <p class="text-blue-100/80 max-w-xl text-xs leading-relaxed">Kelola daftar mitra dan partner terpercaya PT. Unggul Cipta Indah. Logo dan nama mitra akan ditampilkan secara otomatis di halaman utama website.</p>
        </div>
        <div class="relative z-10">
            <button id="btn-open-add-modal"
                onclick="document.getElementById('modal-add-mitra').classList.remove('hidden')"
                class="inline-flex items-center gap-2.5 px-6 py-3 bg-white text-[#003d7c] font-bold text-sm rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Mitra Baru
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-5 py-4 rounded-2xl shadow-sm" id="flash-success">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
        <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold px-5 py-4 rounded-2xl shadow-sm">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Partners Grid --}}
    @if($partners->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-16 text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-blue-50 rounded-3xl flex items-center justify-center">
            <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-700 mb-2">Belum Ada Data Mitra</h3>
        <p class="text-sm text-slate-400 mb-6">Mulai tambahkan mitra strategis PT. UCI agar tampil di halaman utama website.</p>
        <button onclick="document.getElementById('modal-add-mitra').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#003d7c] text-white text-sm font-bold rounded-xl hover:bg-[#002855] transition-colors shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Mitra Pertama
        </button>
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
                <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                    {{-- Edit Button --}}
                    <button
                        onclick="openEditModal({{ $mitra->id }}, '{{ addslashes($mitra->name) }}', '{{ asset('storage/' . $mitra->logo_path) }}')"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-[#003d7c] bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </button>
                    {{-- Delete Button --}}
                    <button
                        onclick="openDeleteModal({{ $mitra->id }}, '{{ addslashes($mitra->name) }}')"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
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

{{-- ============================== --}}
{{-- MODAL: TAMBAH MITRA            --}}
{{-- ============================== --}}
<div id="modal-add-mitra" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-add-mitra').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-[#002855] to-[#004b93] px-6 py-5 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-white tracking-tight">Tambah Mitra Baru</h3>
                <p class="text-blue-200/80 text-xs mt-0.5">Isi nama dan upload logo mitra strategis PT. UCI</p>
            </div>
            <button onclick="document.getElementById('modal-add-mitra').classList.add('hidden')" class="p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('hrd.partners.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Name Field --}}
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Mitra <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT. Global Persada"
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/30 focus:border-[#003d7c] transition-all" required>
            </div>

            {{-- Logo Upload --}}
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Logo Mitra <span class="text-red-500">*</span></label>
                <div id="add-drop-zone"
                     class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center cursor-pointer hover:border-[#003d7c] hover:bg-blue-50/50 transition-all duration-200 group"
                     onclick="document.getElementById('add-logo-input').click()">
                    <div id="add-preview-container" class="hidden flex-col items-center gap-2">
                        <img id="add-preview-img" src="" alt="Preview" class="max-h-24 max-w-full object-contain rounded-xl">
                        <p id="add-preview-name" class="text-xs text-slate-500 font-medium"></p>
                    </div>
                    <div id="add-upload-placeholder" class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                            <svg class="w-6 h-6 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Klik atau seret logo ke sini</p>
                        <p class="text-xs text-slate-400">JPG, PNG, SVG, WebP — Maks. 2MB</p>
                    </div>
                    <input id="add-logo-input" type="file" name="logo" accept="image/jpeg,image/png,image/svg+xml,image/webp" class="hidden" onchange="previewLogo(this, 'add')">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-add-mitra').classList.add('hidden')"
                    class="flex-1 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-5 py-3 rounded-2xl bg-[#003d7c] text-white text-sm font-bold hover:bg-[#002855] transition-colors shadow-md shadow-blue-900/20">
                    Simpan Mitra
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================== --}}
{{-- MODAL: EDIT MITRA              --}}
{{-- ============================== --}}
<div id="modal-edit-mitra" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-edit-mitra').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-[#002855] to-[#004b93] px-6 py-5 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-white tracking-tight">Edit Data Mitra</h3>
                <p class="text-blue-200/80 text-xs mt-0.5">Perbarui nama atau upload logo baru</p>
            </div>
            <button onclick="document.getElementById('modal-edit-mitra').classList.add('hidden')" class="p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="edit-mitra-form" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Name Field --}}
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Nama Mitra <span class="text-red-500">*</span></label>
                <input type="text" id="edit-mitra-name" name="name" placeholder="Nama mitra..."
                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/30 focus:border-[#003d7c] transition-all" required>
            </div>

            {{-- Logo Upload (optional) --}}
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider">Logo Baru <span class="text-slate-400 font-normal normal-case">(opsional, kosongkan jika tidak diganti)</span></label>
                <div id="edit-drop-zone"
                     class="relative border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center cursor-pointer hover:border-[#003d7c] hover:bg-blue-50/50 transition-all duration-200 group"
                     onclick="document.getElementById('edit-logo-input').click()">
                    <div id="edit-preview-container" class="flex flex-col items-center gap-2">
                        <img id="edit-preview-img" src="" alt="Preview" class="max-h-24 max-w-full object-contain rounded-xl">
                        <p id="edit-preview-name" class="text-xs text-slate-500 font-medium">Logo saat ini</p>
                    </div>
                    <input id="edit-logo-input" type="file" name="logo" accept="image/jpeg,image/png,image/svg+xml,image/webp" class="hidden" onchange="previewLogo(this, 'edit')">
                </div>
                <p class="text-[11px] text-slate-400">Klik zona di atas untuk mengganti logo. JPG, PNG, SVG, WebP — Maks. 2MB</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-edit-mitra').classList.add('hidden')"
                    class="flex-1 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-5 py-3 rounded-2xl bg-[#003d7c] text-white text-sm font-bold hover:bg-[#002855] transition-colors shadow-md shadow-blue-900/20">
                    Perbarui Mitra
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================== --}}
{{-- MODAL: DELETE CONFIRMATION     --}}
{{-- ============================== --}}
<div id="modal-delete-mitra" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-delete-mitra').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
        <div class="p-8 text-center space-y-5">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-3xl flex items-center justify-center">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Hapus Mitra?</h3>
                <p class="text-sm text-slate-500 mt-2">Mitra <strong id="delete-mitra-name" class="text-slate-700"></strong> akan dihapus secara permanen beserta logonya. Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <form id="delete-mitra-form" action="" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="document.getElementById('modal-delete-mitra').classList.add('hidden')"
                    class="flex-1 px-5 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-5 py-3 rounded-2xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors shadow-md shadow-red-900/20">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // ====== Preview Logo Image ======
    function previewLogo(input, prefix) {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            if (prefix === 'add') {
                document.getElementById('add-preview-img').src = e.target.result;
                document.getElementById('add-preview-name').textContent = file.name;
                document.getElementById('add-preview-container').classList.remove('hidden');
                document.getElementById('add-preview-container').classList.add('flex');
                document.getElementById('add-upload-placeholder').classList.add('hidden');
            } else {
                document.getElementById('edit-preview-img').src = e.target.result;
                document.getElementById('edit-preview-name').textContent = 'Logo baru: ' + file.name;
            }
        };
        reader.readAsDataURL(file);
    }

    // ====== Drag and Drop ======
    ['add-drop-zone', 'edit-drop-zone'].forEach(function(zoneId) {
        const zone = document.getElementById(zoneId);
        if (!zone) return;
        const prefix = zoneId.startsWith('add') ? 'add' : 'edit';
        const inputId = prefix + '-logo-input';

        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            zone.classList.add('border-[#003d7c]', 'bg-blue-50/50');
        });
        zone.addEventListener('dragleave', function() {
            zone.classList.remove('border-[#003d7c]', 'bg-blue-50/50');
        });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            zone.classList.remove('border-[#003d7c]', 'bg-blue-50/50');
            const dt = e.dataTransfer;
            if (dt.files.length) {
                const input = document.getElementById(inputId);
                input.files = dt.files;
                previewLogo(input, prefix);
            }
        });
    });

    // ====== Open Edit Modal ======
    function openEditModal(id, name, logoUrl) {
        document.getElementById('edit-mitra-name').value = name;
        document.getElementById('edit-mitra-form').action = '/hrd/partners/' + id;
        document.getElementById('edit-preview-img').src = logoUrl;
        document.getElementById('edit-preview-name').textContent = 'Logo saat ini — klik untuk mengganti';
        document.getElementById('modal-edit-mitra').classList.remove('hidden');
    }

    // ====== Open Delete Modal ======
    function openDeleteModal(id, name) {
        document.getElementById('delete-mitra-name').textContent = name;
        document.getElementById('delete-mitra-form').action = '/hrd/partners/' + id;
        document.getElementById('modal-delete-mitra').classList.remove('hidden');
    }

    // ====== Auto-open Add modal if validation errors (for add form) ======
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('modal-add-mitra').classList.remove('hidden');
    });
    @endif
</script>
@endsection
