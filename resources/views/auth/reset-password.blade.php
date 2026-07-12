@extends('layouts.app')

@section('title', 'Reset Password - PT. Unggul Cipta Indah')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('image/LOGO UCI.jpeg') }}');">
    <div class="absolute inset-0 bg-[#002855]/80 backdrop-blur-sm"></div>

    <div class="max-w-md w-full space-y-8 bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] z-10 border border-white/20 text-center">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-400/40 text-emerald-100 px-4 py-3 rounded-xl text-sm font-medium backdrop-blur-sm">
                <div class="flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/20 border border-red-400/40 text-red-100 px-4 py-3 rounded-xl text-sm font-medium backdrop-blur-sm">
                <div class="flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-500/20 border border-red-400/40 text-red-100 px-4 py-3 rounded-xl text-sm font-medium backdrop-blur-sm">
                <div class="flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <div class="mx-auto w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </div>
        <h2 class="mt-2 text-3xl font-extrabold text-white tracking-tight drop-shadow-md">Buat Password Baru</h2>
        <p class="mt-2 text-sm text-blue-100">
            Pastikan password baru Anda kuat dan mudah diingat.
        </p>

        <form class="mt-8 space-y-5 text-left" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ session('reset_email') }}">

            <div>
                <label for="password" class="block text-sm font-medium text-white/90 drop-shadow-sm">Password Baru</label>
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <input id="password" name="password" type="password" required class="js-password-input appearance-none block w-full pl-10 px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm" placeholder="Masukkan password baru">
                </div>
                <!-- Password Strength Meter -->
                <div class="mt-2 hidden js-password-strength-container transition-all">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[11px] text-white/80 font-medium tracking-wide">Kekuatan Password:</span>
                        <span class="text-[11px] font-bold js-password-strength-text text-white drop-shadow-md">Lemah</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-1.5 overflow-hidden border border-white/10">
                        <div class="js-password-strength-bar h-full rounded-full bg-red-500 transition-all duration-500 w-0 shadow-[0_0_10px_rgba(0,0,0,0.5)]"></div>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-white/90 drop-shadow-sm">Konfirmasi Password Baru</label>
                <div class="mt-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full pl-10 px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm" placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-[#003d7c] bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#003d7c] focus:ring-white shadow-[0_4px_14px_0_rgba(255,255,255,0.39)] transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInputs = document.querySelectorAll('.js-password-input');
        
        passwordInputs.forEach(input => {
            const container = input.closest('div').parentElement.querySelector('.js-password-strength-container');
            const bar = container?.querySelector('.js-password-strength-bar');
            const text = container?.querySelector('.js-password-strength-text');
            
            if(input && container && bar && text) {
                input.addEventListener('input', function() {
                    const val = this.value;
                    if(val.length > 0) {
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }

                    let strength = 0;
                    if (val.length >= 8) strength += 25;
                    if (val.match(/[a-z]+/)) strength += 25;
                    if (val.match(/[A-Z]+/)) strength += 25;
                    if (val.match(/[0-9]+/) || val.match(/[\W]+/)) strength += 25;

                    bar.style.width = strength + '%';

                    if(strength <= 25) {
                        bar.className = 'js-password-strength-bar h-full rounded-full bg-red-500 transition-all duration-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]';
                        text.textContent = 'Sangat Lemah';
                        text.className = 'text-[11px] font-bold js-password-strength-text text-red-300 drop-shadow-md';
                    } else if(strength <= 50) {
                        bar.className = 'js-password-strength-bar h-full rounded-full bg-orange-400 transition-all duration-500 shadow-[0_0_10px_rgba(251,146,60,0.5)]';
                        text.textContent = 'Lemah';
                        text.className = 'text-[11px] font-bold js-password-strength-text text-orange-300 drop-shadow-md';
                    } else if(strength <= 75) {
                        bar.className = 'js-password-strength-bar h-full rounded-full bg-yellow-400 transition-all duration-500 shadow-[0_0_10px_rgba(250,204,21,0.5)]';
                        text.textContent = 'Cukup Kuat';
                        text.className = 'text-[11px] font-bold js-password-strength-text text-yellow-300 drop-shadow-md';
                    } else {
                        bar.className = 'js-password-strength-bar h-full rounded-full bg-green-400 transition-all duration-500 shadow-[0_0_10px_rgba(74,222,128,0.5)]';
                        text.textContent = 'Sangat Kuat';
                        text.className = 'text-[11px] font-bold js-password-strength-text text-green-300 drop-shadow-md';
                    }
                });
            }
        });
    });
</script>
@endsection
