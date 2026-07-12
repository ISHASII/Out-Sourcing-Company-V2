@extends('layouts.dashboard')

@section('dashboard-title', 'Tambah Akun')

@section('dashboard-content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">

    {{-- Back button + Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('superadmin.dashboard') }}"
           class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-[#003d7c] transition-all shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h3 class="text-xl font-extrabold text-[#003d7c]">Tambah Akun Baru</h3>
            <p class="text-xs text-slate-400 mt-0.5">Buat akun HRD atau Superadmin untuk sistem PT. UCI.</p>
        </div>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
        </svg>
        <div>
            <p class="text-xs font-bold text-rose-700 mb-1">Terdapat kesalahan pada input:</p>
            <ul class="list-disc list-inside text-xs text-rose-600 space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h4 class="text-sm font-bold text-slate-700">Informasi Akun</h4>
        </div>
        <form action="{{ route('superadmin.admins.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       placeholder="Contoh: Budi Santoso, S.Psi"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#003d7c] focus:ring-2 focus:ring-[#003d7c]/10 transition-all @error('name') border-rose-400 @enderror"
                       required autocomplete="off">
                @error('name')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       placeholder="admin@uci.co.id"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#003d7c] focus:ring-2 focus:ring-[#003d7c]/10 transition-all @error('email') border-rose-400 @enderror"
                       required autocomplete="off">
                @error('email')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Role Selector --}}
            <div x-data="{ selectedRole: '{{ old('role', 'hrd') }}' }">
                <label class="text-xs font-bold text-slate-600 block mb-2">
                    Role Akun <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">

                    {{-- HRD Option --}}
                    <button type="button"
                            @click="selectedRole = 'hrd'"
                            :style="selectedRole === 'hrd'
                                ? 'border-color:#003d7c; background:#003d7c0d; box-shadow:0 0 0 2px #003d7c33;'
                                : 'border-color:#e2e8f0; background:transparent;'"
                            class="flex items-start gap-3 p-4 rounded-xl border text-left transition-all">
                        <div :style="selectedRole === 'hrd'
                                     ? 'background:#003d7c; color:#fff;'
                                     : 'background:#f1f5f9; color:#64748b;'"
                             class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold transition-all"
                               :style="selectedRole === 'hrd' ? 'color:#003d7c;' : 'color:#334155;'">HRD</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Kelola lowongan &amp; rekrutmen</p>
                        </div>
                    </button>

                    {{-- Superadmin Option --}}
                    <button type="button"
                            @click="selectedRole = 'superadmin'"
                            :style="selectedRole === 'superadmin'
                                ? 'border-color:#003d7c; background:#003d7c0d; box-shadow:0 0 0 2px #003d7c33;'
                                : 'border-color:#e2e8f0; background:transparent;'"
                            class="flex items-start gap-3 p-4 rounded-xl border text-left transition-all">
                        <div :style="selectedRole === 'superadmin'
                                     ? 'background:#003d7c; color:#fff;'
                                     : 'background:#f1f5f9; color:#64748b;'"
                             class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold transition-all"
                               :style="selectedRole === 'superadmin' ? 'color:#003d7c;' : 'color:#334155;'">Superadmin</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Kelola akun &amp; hak akses</p>
                        </div>
                    </button>

                </div>
                <input type="hidden" name="role" :value="selectedRole">
                @error('role')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>


            {{-- Password --}}
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">
                    Password <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="password" id="password"
                       placeholder="Minimal 8 karakter"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#003d7c] focus:ring-2 focus:ring-[#003d7c]/10 transition-all @error('password') border-rose-400 @enderror"
                       required>
                @error('password')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Confirm --}}
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1.5">
                    Konfirmasi Password <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       placeholder="Ulangi password"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#003d7c] focus:ring-2 focus:ring-[#003d7c]/10 transition-all"
                       required>
            </div>

            {{-- Status Toggle --}}
            <div class="flex items-center justify-between p-4 bg-slate-50/70 rounded-xl border border-slate-100" x-data="{ active: true }">
                <div>
                    <p class="text-xs font-bold text-slate-700">Status Akun</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Akun yang nonaktif tidak dapat login ke sistem.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold" :class="active ? 'text-emerald-600' : 'text-slate-400'" x-text="active ? 'Aktif' : 'Nonaktif'"></span>
                    <button type="button" @click="active = !active"
                            :class="active ? 'bg-emerald-500' : 'bg-slate-300'"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                        <span :class="active ? 'translate-x-6' : 'translate-x-1'"
                              class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform"></span>
                    </button>
                    <input type="hidden" name="is_active" :value="active ? '1' : '0'">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('superadmin.dashboard') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#003d7c] text-white text-sm font-bold rounded-xl hover:bg-[#002d5c] transition-all shadow-lg shadow-blue-900/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
