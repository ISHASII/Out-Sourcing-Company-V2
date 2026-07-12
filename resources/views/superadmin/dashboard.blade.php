@extends('layouts.dashboard')

@section('dashboard-title', 'Panel Superadmin')

@section('dashboard-content')
<div class="space-y-8 animate-fade-in">

            {{-- Flash messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-sm font-semibold shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-extrabold text-[#003d7c]">Manajemen Akun Hrd</h3>
                    <p class="text-sm text-slate-400 mt-1">Kelola akun HRD/Admin dan status hak akses mereka.</p>
                </div>
                <a href="{{ route('superadmin.admins.create') }}"
                   class="flex items-center gap-2 bg-[#003d7c] text-white text-sm font-bold px-5 py-2.5 rounded-2xl hover:bg-[#002d5c] transition-all shadow-lg shadow-blue-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Hrd Baru
                </a>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-[#003d7c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Hrd</p>
                        <p class="text-3xl font-extrabold text-[#003d7c]">{{ $totalAdmin }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Hrd Aktif</p>
                        <p class="text-3xl font-extrabold text-emerald-600">{{ $activeAdmin }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Dinonaktifkan</p>
                        <p class="text-3xl font-extrabold text-rose-500">{{ $inactiveAdmin }}</p>
                    </div>
                </div>
            </div>

            {{-- Admin Table --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h4 class="text-sm font-bold text-slate-700">Daftar Akun HRD</h4>
                </div>

                @if($admins->isEmpty())
                <div class="py-16 text-center">
                    <svg class="w-14 h-14 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-slate-400 text-sm font-semibold">Belum ada akun Hrd yang dibuat.</p>
                    <a href="{{ route('superadmin.admins.create') }}" class="mt-4 inline-block text-[#003d7c] font-bold text-sm hover:underline">Buat Admin Pertama →</a>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50/70 text-slate-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 text-left font-bold">#</th>
                                <th class="px-6 py-3.5 text-left font-bold">Nama</th>
                                <th class="px-6 py-3.5 text-left font-bold">Email</th>
                                <th class="px-6 py-3.5 text-left font-bold">Role</th>
                                <th class="px-6 py-3.5 text-left font-bold">Status</th>
                                <th class="px-6 py-3.5 text-left font-bold">Dibuat</th>
                                <th class="px-6 py-3.5 text-center font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($admins as $i => $admin)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4 text-slate-400 font-semibold">{{ $i + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-extrabold shrink-0
                                            {{ $admin->role === 'superadmin' ? 'bg-gradient-to-br from-purple-600 to-purple-400' : 'bg-gradient-to-br from-[#003d7c] to-blue-400' }}">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $admin->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $admin->email }}</td>
                                <td class="px-6 py-4">
                                    @if($admin->role === 'superadmin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-bold border border-purple-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        Superadmin
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-[#003d7c] text-xs font-bold border border-blue-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        HRD
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($admin->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        Nonaktif
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-xs">{{ $admin->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('superadmin.admins.toggle', $admin) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    title="{{ $admin->is_active ? 'Nonaktifkan akun' : 'Aktifkan akun' }}"
                                                    class="{{ $admin->is_active ? 'bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border-rose-200 hover:border-rose-600' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white border-emerald-200 hover:border-emerald-600' }} p-2 rounded-xl border text-xs font-bold transition-all flex items-center gap-1.5">
                                                @if($admin->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                <span class="hidden sm:inline">Nonaktifkan</span>
                                                @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span class="hidden sm:inline">Aktifkan</span>
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Edit --}}
                                        <a href="{{ route('superadmin.admins.edit', $admin) }}"
                                           title="Edit admin"
                                           class="p-2 rounded-xl bg-blue-50 text-[#003d7c] hover:bg-[#003d7c] hover:text-white border border-blue-100 hover:border-[#003d7c] transition-all flex items-center gap-1.5 text-xs font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('superadmin.admins.destroy', $admin) }}" method="POST" class="inline"
                                              x-data @submit.prevent="$dispatch('open-confirm-modal', {
                                                  title: 'Hapus akun hrd?',
                                                  message: 'Akun {{ addslashes($admin->name) }} akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
                                                  confirmText: 'Ya, Hapus',
                                                  type: 'danger',
                                                  actionType: 'submit',
                                                  formElement: $el
                                              })">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    title="Hapus admin"
                                                    class="p-2 rounded-xl bg-slate-50 text-slate-500 hover:bg-rose-600 hover:text-white border border-slate-200 hover:border-rose-600 transition-all flex items-center gap-1.5 text-xs font-bold">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

</div>
@endsection
