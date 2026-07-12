@extends('layouts.app')

@section('title', 'Verifikasi Akun - PT. Unggul Cipta Indah')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('image/LOGO UCI.jpeg') }}');">
    <div class="absolute inset-0 bg-[#002855]/80 backdrop-blur-sm"></div>

    {{-- Back Button --}}
    <a href="{{ route('register') }}" class="absolute top-6 left-6 md:top-8 md:left-8 z-20 p-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-white hover:bg-white/20 hover:scale-110 transition-all duration-200 shadow-lg group" title="Kembali ke Pendaftaran">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="max-w-md w-full space-y-6 bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.3)] z-10 border border-white/20 text-center" x-data="otpVerification()">

        {{-- Success/Error Alerts --}}
        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-400/40 text-emerald-100 px-4 py-3 rounded-xl text-sm font-medium backdrop-blur-sm animate-pulse">
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

        {{-- Icon --}}
        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-white/20 to-white/5 rounded-2xl flex items-center justify-center border border-white/20 shadow-lg">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>

        {{-- Title --}}
        <div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight drop-shadow-md">Verifikasi Akun</h2>
            <p class="mt-3 text-sm text-blue-100 leading-relaxed">
                Kode OTP telah dikirimkan ke email
                <span class="font-bold text-white block mt-1">{{ session('otp_email', '***') }}</span>
            </p>
        </div>

        {{-- OTP Form --}}
        <form action="{{ route('register.verifyOtp') }}" method="POST" class="space-y-6" @submit="onSubmit($event)">
            @csrf
            <input type="hidden" name="otp" x-model="otpValue">

            <div>
                <label class="block text-sm font-medium text-white/90 drop-shadow-sm mb-3">Masukkan Kode 6 Digit</label>
                <div class="flex justify-center gap-2 sm:gap-3">
                    <template x-for="(digit, index) in digits" :key="index">
                        <input
                            type="text"
                            maxlength="1"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            class="w-12 h-14 sm:w-14 sm:h-16 text-center text-xl sm:text-2xl font-bold bg-white/10 border-2 border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all shadow-inner backdrop-blur-sm placeholder-white/20 caret-transparent"
                            :class="{ 'border-emerald-400/60 bg-emerald-500/10': digit !== '' }"
                            x-model="digits[index]"
                            @input="handleInput($event, index)"
                            @keydown="handleKeydown($event, index)"
                            @paste="handlePaste($event)"
                            @focus="$event.target.select()"
                            :autofocus="index === 0"
                            x-ref="'otp_' + index"
                        >
                    </template>
                </div>
            </div>

            {{-- Timer --}}
            <div class="text-center">
                <div x-show="timeLeft > 0" class="inline-flex items-center gap-2 bg-white/5 px-4 py-2 rounded-full border border-white/10">
                    <svg class="w-4 h-4 text-blue-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs text-white/80">Kode berlaku <span class="font-bold text-white" x-text="formatTime(timeLeft)"></span></span>
                </div>
                <div x-show="timeLeft <= 0" class="text-xs text-red-300 font-medium">
                    Kode telah kadaluarsa. Silakan kirim ulang.
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-[#003d7c] bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#003d7c] focus:ring-white shadow-[0_4px_14px_0_rgba(255,255,255,0.39)] transition-all duration-200 ease-in-out transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                :disabled="otpValue.length < 6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Verifikasi & Buat Akun
            </button>
        </form>

        {{-- Resend --}}
        <div class="border-t border-white/10 pt-5">
            <p class="text-xs text-white/60 mb-3">Belum menerima kode?</p>
            <form action="{{ route('register.resendOtp') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="text-sm font-bold text-white hover:text-blue-200 transition-colors border-b border-white/30 hover:border-white disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:text-white disabled:hover:border-white/30"
                    :disabled="resendCooldown > 0"
                    x-text="resendCooldown > 0 ? 'Kirim ulang dalam ' + resendCooldown + 's' : 'Kirim Ulang Kode OTP'">
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function otpVerification() {
    return {
        digits: ['', '', '', '', '', ''],
        timeLeft: 600, // 10 minutes
        resendCooldown: 60,
        timer: null,
        cooldownTimer: null,

        get otpValue() {
            return this.digits.join('');
        },

        init() {
            // Start countdown timer
            this.timer = setInterval(() => {
                if (this.timeLeft > 0) this.timeLeft--;
            }, 1000);

            // Start resend cooldown
            this.cooldownTimer = setInterval(() => {
                if (this.resendCooldown > 0) this.resendCooldown--;
                else clearInterval(this.cooldownTimer);
            }, 1000);

            // Focus first input
            this.$nextTick(() => {
                const firstInput = this.$el.querySelector('input[maxlength="1"]');
                if (firstInput) firstInput.focus();
            });
        },

        formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return m + ':' + (s < 10 ? '0' : '') + s;
        },

        handleInput(event, index) {
            const val = event.target.value;
            // Only allow numbers
            if (!/^\d$/.test(val)) {
                this.digits[index] = '';
                return;
            }
            this.digits[index] = val;
            // Auto-focus next
            if (val && index < 5) {
                const inputs = this.$el.querySelectorAll('input[maxlength="1"]');
                inputs[index + 1]?.focus();
            }
        },

        handleKeydown(event, index) {
            if (event.key === 'Backspace') {
                if (this.digits[index] === '' && index > 0) {
                    const inputs = this.$el.querySelectorAll('input[maxlength="1"]');
                    inputs[index - 1]?.focus();
                    this.digits[index - 1] = '';
                } else {
                    this.digits[index] = '';
                }
            } else if (event.key === 'ArrowLeft' && index > 0) {
                const inputs = this.$el.querySelectorAll('input[maxlength="1"]');
                inputs[index - 1]?.focus();
            } else if (event.key === 'ArrowRight' && index < 5) {
                const inputs = this.$el.querySelectorAll('input[maxlength="1"]');
                inputs[index + 1]?.focus();
            }
        },

        handlePaste(event) {
            event.preventDefault();
            const paste = (event.clipboardData || window.clipboardData).getData('text').trim();
            const digits = paste.replace(/\D/g, '').split('').slice(0, 6);
            digits.forEach((d, i) => {
                this.digits[i] = d;
            });
            // Focus last filled or submit
            const inputs = this.$el.querySelectorAll('input[maxlength="1"]');
            const focusIdx = Math.min(digits.length, 5);
            inputs[focusIdx]?.focus();
        },

        onSubmit(event) {
            if (this.otpValue.length < 6) {
                event.preventDefault();
            }
        },

        destroy() {
            clearInterval(this.timer);
            clearInterval(this.cooldownTimer);
        }
    }
}
</script>
@endsection
