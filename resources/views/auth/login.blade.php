@extends('layouts.app')

@section('title', 'Masuk - PT. Unggul Cipta Indah')

@section('content')
<style>
    /* Fix browser autofill white background & keep icon visible */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0px 1000px rgba(0, 40, 85, 0.85) inset !important;
        -webkit-text-fill-color: #ffffff !important;
        caret-color: #ffffff;
        border-color: rgba(255,255,255,0.35) !important;
        transition: background-color 99999s ease-in-out 0s;
    }
    /* Icon always visible — slate-700 on empty/light, white on dark bg */
    .input-icon {
        color: rgba(255, 255, 255, 0.75);
        transition: color 0.2s;
        flex-shrink: 0;
    }
    .input-wrapper:focus-within .input-icon {
        color: rgba(255, 255, 255, 1);
    }
</style>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('image/LOGO UCI.jpeg') }}');">
    <!-- Dark Overlay + Blur untuk efek Glassmorphism -->
    <div class="absolute inset-0 bg-[#002855]/80 backdrop-blur-sm"></div>

    <!-- Tombol Kembali (Back) -->
    <a href="{{ url('/') }}" class="absolute top-6 left-6 md:top-8 md:left-8 z-20 p-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-white hover:bg-white/20 hover:scale-110 transition-all duration-200 shadow-lg group" title="Kembali ke Beranda">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <!-- Glassmorphism Card -->
    <div class="max-w-md w-full space-y-8 bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] z-10 border border-white/20">
        <!-- Logo & Header -->
        <div class="text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="bg-white p-2 rounded-xl shadow-lg border border-white/30 inline-block">
                    <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo PT. Unggul Cipta Indah" class="w-12 h-12 object-contain rounded-lg">
                </div>
            </a>
            <h2 class="mt-2 text-3xl font-extrabold text-white tracking-tight drop-shadow-md">Selamat Datang</h2>
            <p class="mt-2 text-sm text-blue-100">
                Silakan masuk ke akun Anda untuk melanjutkan
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-white/90 drop-shadow-sm">Email Address</label>
                    <div class="mt-1 relative input-wrapper">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                            <svg class="h-5 w-5 input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required class="appearance-none block w-full pl-10 px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm @error('email') border-red-500 @enderror" placeholder="Masukkan email Anda">
                    </div>
                    @error('email')
                        <span class="text-xs text-red-300 mt-1 block drop-shadow-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white/90 drop-shadow-sm">Password</label>
                    <div class="mt-1 relative input-wrapper">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                            <svg class="h-5 w-5 input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full pl-10 px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm" placeholder="Masukkan password Anda">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 bg-white/10 border-white/30 text-white focus:ring-white/50 rounded cursor-pointer transition-colors">
                    <label for="remember-me" class="ml-2 block text-sm text-white/80 cursor-pointer">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-semibold text-white hover:text-blue-200 transition-colors drop-shadow-sm">Lupa password?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-[#003d7c] bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#003d7c] focus:ring-white shadow-[0_4px_14px_0_rgba(255,255,255,0.39)] transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-white/80">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-white hover:text-blue-200 transition-colors drop-shadow-sm ml-1 border-b border-white/30 hover:border-white">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
