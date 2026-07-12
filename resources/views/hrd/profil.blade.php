@extends('layouts.dashboard')

@section('dashboard-title', 'Profil Saya')

@section('dashboard-content')
<div class="space-y-6 animate-fade-in">
    <!-- Success & Error Alerts -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100/80 rounded-2xl flex items-start gap-3 shadow-sm">
            <div class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg shrink-0">
                <svg class="w-4 h-4" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-emerald-800">Berhasil diperbarui</h4>
                <p class="text-xs text-emerald-600/90 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-100/80 rounded-2xl flex items-start gap-3 shadow-sm">
            <div class="p-1.5 bg-rose-100 text-rose-700 rounded-lg shrink-0">
                <svg class="w-4 h-4" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-xs font-bold text-rose-800">Terjadi beberapa kesalahan</h4>
                <ul class="list-disc list-inside text-xs text-rose-600/90 mt-1 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Profile Hero Card -->
    <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
        <!-- Decorative gradient banner top -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-700 to-indigo-700"></div>

        <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
            <!-- Profile Avatar with dynamic initials -->
            <div class="relative">
                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-blue-700 via-blue-600 to-indigo-600 p-1 flex items-center justify-center shadow-md">
                    <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                        <span class="text-2xl font-extrabold text-blue-700 tracking-wider">
                            @php
                                $words = explode(' ', Auth::user()->name);
                                $initials = '';
                                foreach ($words as $w) {
                                    $initials .= strtoupper(substr($w, 0, 1));
                                }
                                echo substr($initials, 0, 2);
                            @endphp
                        </span>
                    </div>
                </div>
                <!-- Status Badge -->
                <span class="absolute bottom-0 right-1 w-5 h-5 bg-emerald-500 border-4 border-white rounded-full flex items-center justify-center" title="Online"></span>
            </div>

            <!-- Profile Meta details -->
            <div class="text-center md:text-left flex-1 space-y-1.5">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
                    <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ Auth::user()->name }}</h3>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-700 bg-blue-50 border border-blue-150 px-2.5 py-1 rounded-lg">HRD Corporate</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">{{ Auth::user()->email }}</p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-4 gap-y-1 text-[11px] text-slate-400 font-semibold pt-1">
                    <span class="flex items-center gap-1.5">
                        <svg class="text-slate-350" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110 21a3.745 3.745 0 01-3.296-1.043A3.745 3.745 0 015.66 16.66 3.745 3.745 0 013 13.068Q3 12 3 10.932a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.745 3.745 0 0110 3c1.25 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"></path>
                        </svg>
                        Akun Terverifikasi
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="text-slate-350" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path>
                        </svg>
                        Terdaftar sejak: {{ Auth::user()->created_at?->translatedFormat('d F Y') ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms Section Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Account Details Card Form -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 to-blue-700"></div>

            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-50 text-blue-700 rounded-xl">
                    <svg class="w-5 h-5" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Informasi Profil Akun</h3>
                    <p class="text-[11px] text-slate-450 mt-0.5">Kelola data dasar admin nama lengkap dan email Anda.</p>
                </div>
            </div>

            <form action="{{ route('hrd.profil.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="update_type" value="profile">

                <div class="space-y-1.5">
                    <label for="name" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Nama Lengkap HRD <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" 
                           class="w-full bg-white border border-slate-300 hover:border-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-2xl p-3 text-slate-800 text-xs font-semibold shadow-sm transition-all outline-none"
                           value="{{ old('name', Auth::user()->name) }}" required>
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Alamat Email Resmi <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" 
                           class="w-full bg-white border border-slate-300 hover:border-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-2xl p-3 text-slate-800 text-xs font-semibold shadow-sm transition-all outline-none"
                           value="{{ old('email', Auth::user()->email) }}" required>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" 
                            class="px-5 py-3 bg-gradient-to-r from-blue-700 to-indigo-700 hover:from-blue-800 hover:to-indigo-800 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-md shadow-blue-700/10 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                        <svg style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Card Form -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-400 to-slate-500"></div>

            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-slate-50 text-slate-600 rounded-xl">
                    <svg class="w-5 h-5" style="width: 20px; height: 20px; min-width: 20px; min-height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Perbarui Kata Sandi</h3>
                    <p class="text-[11px] text-slate-455 mt-0.5">Jaga keamanan akun HRD Anda dengan memperbarui sandi secara berkala.</p>
                </div>
            </div>

            <form action="{{ route('hrd.profil.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="update_type" value="password">



                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Password Baru <span class="text-rose-500">*</span></label>
                    <input type="password" id="password" name="password" 
                           class="w-full bg-white border border-slate-300 hover:border-slate-400 focus:border-slate-600 focus:ring-4 focus:ring-slate-600/10 rounded-2xl p-3 text-slate-800 text-xs font-semibold shadow-sm transition-all outline-none"
                           placeholder="Minimal 8 karakter baru" required>
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Ulangi Password Baru <span class="text-rose-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="w-full bg-white border border-slate-300 hover:border-slate-400 focus:border-slate-600 focus:ring-4 focus:ring-slate-600/10 rounded-2xl p-3 text-slate-800 text-xs font-semibold shadow-sm transition-all outline-none"
                           placeholder="Konfirmasi password baru" required>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" 
                            class="px-5 py-3 bg-slate-800 hover:bg-slate-900 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-md transition-all hover:-translate-y-0.5 flex items-center gap-2">
                        <svg style="width: 14px; height: 14px; min-width: 14px; min-height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
