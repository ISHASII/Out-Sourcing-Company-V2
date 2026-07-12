@extends('layouts.app')

@section('title', 'Pendaftaran Pelamar Kerja - PT. Unggul Cipta Indah')

@section('content')
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 relative bg-cover bg-center bg-no-repeat bg-fixed flex justify-center items-start"
        style="background-image: url('{{ asset('image/LOGO UCI.jpeg') }}');">
        <!-- Dark Overlay + Blur -->
        <div class="absolute inset-0 bg-[#002855]/90 backdrop-blur-md"></div>

        <!-- Tombol Kembali (Back) -->
        <a href="{{ url('/') }}"
            class="fixed top-6 left-6 md:top-8 md:left-8 z-50 p-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-white hover:bg-white/20 hover:scale-110 transition-all duration-200 shadow-lg group"
            title="Kembali ke Beranda">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:-translate-x-1 transition-transform"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>

        <!-- Glassmorphism Container -->
        <div
            class="relative z-10 w-full max-w-5xl bg-white/10 backdrop-blur-xl p-8 md:p-12 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.4)] border border-white/20">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="bg-white p-2 rounded-xl shadow-lg border border-white/30 inline-block mb-4">
                    <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo PT. Unggul Cipta Indah"
                        class="w-14 h-14 object-contain rounded-lg">
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight drop-shadow-md">Form Pendaftaran
                    Pelamar Kerja</h2>
                <p class="mt-3 text-sm text-blue-100 max-w-2xl mx-auto">
                    Silakan lengkapi data diri Anda di bawah ini dengan sebenar-benarnya untuk bergabung bersama kami.
                </p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                @csrf
                <!-- Hidden Role Input (Default: Pelamar) -->
                <input type="hidden" name="role" value="pelamar">

                <!-- 1. INFORMASI AKUN / ACCOUNT INFORMATION -->
                <section>
                    <h3
                        class="text-xl font-bold text-white border-b border-white/30 pb-3 mb-6 drop-shadow-sm flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Akun / <span class="text-sm font-normal text-white/80">Account Information</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-white/90 drop-shadow-sm">Nama
                                Depan / <span class="text-xs text-white/70">First Name</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="first_name" name="first_name" type="text" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Nama Depan / First Name">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-white/90 drop-shadow-sm">Nama
                                Belakang / <span class="text-xs text-white/70">Last Name</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="last_name" name="last_name" type="text" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Nama Belakang / Last Name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-white/90 drop-shadow-sm">Email <span
                                    class="text-red-400">*</span></label>
                            <input id="email" name="email" type="email" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="email@contoh.com">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-white/90 drop-shadow-sm">Nomor HP /
                                <span class="text-xs text-white/70">Phone Number</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="phone" name="phone" type="tel" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-white/90 drop-shadow-sm">Password
                                <span class="text-red-400">*</span></label>
                            <input id="password" name="password" type="password" required
                                class="js-password-input mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Buat password / Create password">

                            <!-- Password Strength Meter -->
                            <div class="mt-2 hidden js-password-strength-container transition-all">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[11px] text-white/80 font-medium">Kekuatan Password:</span>
                                    <span class="text-[11px] font-bold js-password-strength-text text-white">Lemah</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-1.5 overflow-hidden border border-white/10">
                                    <div
                                        class="js-password-strength-bar h-full rounded-full bg-red-500 transition-all duration-500 w-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-white/90 drop-shadow-sm">Konfirmasi Password / <span
                                    class="text-xs text-white/70">Confirm Password</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Ulangi password / Repeat password">
                        </div>
                    </div>
                </section>

                <!-- 2. BIODATA PRIBADI / PERSONAL DATA -->
                <section>
                    <h3
                        class="text-xl font-bold text-white border-b border-white/30 pb-3 mb-6 drop-shadow-sm flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                        Biodata Pribadi / <span class="text-sm font-normal text-white/80">Personal Data</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-white/90 drop-shadow-sm mb-2">Status
                                Kewarganegaraan / <span class="text-xs text-white/70">Citizenship</span> <span
                                    class="text-red-400">*</span></label>
                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2 cursor-pointer text-white">
                                    <input type="radio" name="kewarganegaraan" value="WNI" checked
                                        class="w-4 h-4 text-[#003d7c] border-white/30 bg-white/10 focus:ring-[#003d7c]">
                                    <span>WNI <span class="text-xs text-white/70">(Indonesian Citizen)</span></span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer text-white">
                                    <input type="radio" name="kewarganegaraan" value="WNA"
                                        class="w-4 h-4 text-[#003d7c] border-white/30 bg-white/10 focus:ring-[#003d7c]">
                                    <span>WNA <span class="text-xs text-white/70">(Foreign Citizen)</span></span>
                                </label>
                            </div>
                        </div>

                        <div class="md:col-span-2 transition-all" id="container_nik">
                            <label for="nik" class="block text-sm font-medium text-white/90 drop-shadow-sm">NIK / Nomor KTP
                                / <span class="text-xs text-white/70">National ID</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="nik" name="nik" type="text" required maxlength="16"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="16 Digit NIK KTP">
                        </div>

                        <div class="md:col-span-2 hidden transition-all" id="container_paspor">
                            <label for="paspor" class="block text-sm font-medium text-white/90 drop-shadow-sm">Nomor Paspor
                                / <span class="text-xs text-white/70">Passport Number</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="paspor" name="paspor" type="text"
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Nomor Paspor / Passport Number">
                        </div>

                        <div class="md:col-span-2 hidden transition-all" id="container_negara">
                            <label for="asal_negara" class="block text-sm font-medium text-white/90 drop-shadow-sm">Asal
                                Negara / <span class="text-xs text-white/70">Country of Origin</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="asal_negara" name="asal_negara" type="text"
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Contoh / Example: Japan, Malaysia">
                        </div>
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-white/90 drop-shadow-sm">Tempat
                                Lahir / <span class="text-xs text-white/70">Place of Birth</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="tempat_lahir" name="tempat_lahir" type="text" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Kota Kelahiran / Birth City">
                        </div>
                        <div>
                            <label for="tanggal_lahir"
                                class="block text-sm font-medium text-white/90 drop-shadow-sm">Tanggal Lahir / <span
                                    class="text-xs text-white/70">Date of Birth</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="tanggal_lahir" name="tanggal_lahir" type="date" required style="color-scheme: dark;"
                                onclick="this.showPicker()"
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm cursor-pointer">
                        </div>
                        <div class="md:col-span-2">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-white/90 drop-shadow-sm">Jenis
                                Kelamin / <span class="text-xs text-white/70">Gender</span> <span
                                    class="text-red-400">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm [&>option]:text-slate-900 cursor-pointer">
                                <option value="" disabled selected>Pilih Jenis Kelamin / Select Gender</option>
                                <option value="Laki-laki">Laki-laki / Male</option>
                                <option value="Perempuan">Perempuan / Female</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-white/90 drop-shadow-sm">Alamat
                                Domisili / <span class="text-xs text-white/70">Domicile Address</span> <span
                                    class="text-red-400">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan / Full Address"></textarea>
                        </div>
                        <div>
                            <label for="search_provinsi"
                                class="block text-sm font-medium text-white/90 drop-shadow-sm">Provinsi / <span
                                    class="text-xs text-white/70">Province</span> <span
                                    class="text-red-400">*</span></label>
                            <div class="relative mt-1">
                                <input type="text" id="search_provinsi"
                                    class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                    placeholder="Ketik provinsi / Type province..." autocomplete="off">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-white/60">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <div id="list_provinsi"
                                    class="absolute z-50 w-full mt-2 bg-[#002855] border border-white/20 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden">
                                    <div class="p-3 text-sm text-white/50 text-center">Memuat Provinsi...</div>
                                </div>
                            </div>
                            <input type="hidden" name="provinsi" id="hidden_provinsi" required>
                        </div>
                        <div>
                            <label for="search_kota" class="block text-sm font-medium text-white/90 drop-shadow-sm">Kota /
                                Kabupaten / <span class="text-xs text-white/70">City</span> <span
                                    class="text-red-400">*</span></label>
                            <div class="relative mt-1">
                                <input type="text" id="search_kota" disabled
                                    class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                    placeholder="Pilih Provinsi Terlebih Dahulu" autocomplete="off">
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-white/60">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <div id="list_kota"
                                    class="absolute z-50 w-full mt-2 bg-[#002855] border border-white/20 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden">
                                </div>
                            </div>
                            <input type="hidden" name="kota" id="hidden_kota" required>
                        </div>
                        <div>
                            <label for="kode_pos" class="block text-sm font-medium text-white/90 drop-shadow-sm">Kode Pos /
                                <span class="text-xs text-white/70">Postal Code</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="kode_pos" name="kode_pos" type="text" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Contoh: 17433">
                        </div>
                    </div>
                </section>

                <!-- 3. PENDIDIKAN / EDUCATION -->
                <section>
                    <h3
                        class="text-xl font-bold text-white border-b border-white/30 pb-3 mb-6 drop-shadow-sm flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                            </path>
                        </svg>
                        Pendidikan / <span class="text-sm font-normal text-white/80">Education</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="pendidikan"
                                class="block text-sm font-medium text-white/90 drop-shadow-sm">Pendidikan Terakhir / <span
                                    class="text-xs text-white/70">Latest Education</span> <span
                                    class="text-red-400">*</span></label>
                            <select id="pendidikan" name="pendidikan" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm [&>option]:text-slate-900 cursor-pointer">
                                <option value="" disabled selected>Pilih Jenjang / Select Level</option>
                                <option value="SMA/SMK">SMA / SMK Sederajat (High School)</option>
                                <option value="D3">Diploma 3 (D3)</option>
                                <option value="D4/S1">Sarjana / Bachelor (D4/S1)</option>
                                <option value="S2">Magister / Master (S2)</option>
                            </select>
                        </div>
                        <div>
                            <label for="tahun_lulus" class="block text-sm font-medium text-white/90 drop-shadow-sm">Tahun
                                Lulus / <span class="text-xs text-white/70">Graduation Year</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="tahun_lulus" name="tahun_lulus" type="number" min="1980" max="{{ date('Y') }}"
                                required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Contoh / Example: 2023">
                        </div>
                        <div class="md:col-span-2">
                            <label for="sekolah" class="block text-sm font-medium text-white/90 drop-shadow-sm">Nama Sekolah
                                / Universitas / <span class="text-xs text-white/70">School / University Name</span> <span
                                    class="text-red-400">*</span></label>
                            <input id="sekolah" name="sekolah" type="text" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Nama Institusi / Institution Name">
                        </div>
                        <div class="md:col-span-2">
                            <label for="jurusan" class="block text-sm font-medium text-white/90 drop-shadow-sm">Jurusan /
                                <span class="text-xs text-white/70">Major</span> <span class="text-red-400">*</span></label>
                            <input id="jurusan" name="jurusan" type="text" required
                                class="mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                placeholder="Jurusan Pendidikan / Education Major">
                        </div>
                    </div>
                </section>

                <!-- 4. PENGALAMAN KERJA / WORK EXPERIENCE -->
                <section>
                    <h3
                        class="text-xl font-bold text-white border-b border-white/30 pb-3 mb-4 drop-shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Pengalaman Kerja / <span class="text-sm font-normal text-white/80">Work Experience</span>
                        </div>
                        <label class="flex items-center cursor-pointer space-x-2 text-sm font-normal text-white/90">
                            <input type="checkbox" id="has_experience" name="has_experience" value="1"
                                class="w-4 h-4 rounded border-white/30 bg-white/10 text-[#003d7c] focus:ring-[#003d7c] focus:ring-offset-0 focus:ring-2 cursor-pointer">
                            <span>Saya memiliki pengalaman kerja / <span class="text-xs text-white/70">I have work
                                    experience</span></span>
                        </label>
                    </h3>

                    <div id="experience_container" class="hidden space-y-6">
                        <!-- Template Pengalaman (Akan diclone oleh JS) -->
                        <div class="experience-item relative p-6 bg-white/5 border border-white/10 rounded-2xl">
                            <div class="absolute top-4 right-4 hidden remove-exp-btn cursor-pointer text-white/40 hover:text-red-400 transition-colors"
                                title="Hapus Pengalaman / Remove Experience">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-white/90 drop-shadow-sm">Nama Pekerjaan /
                                        <span class="text-xs text-white/70">Job Title</span> <span
                                            class="text-red-400">*</span></label>
                                    <input name="posisi[]" type="text"
                                        class="exp-input mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                        placeholder="Contoh / Example: Staff Administrasi">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white/90 drop-shadow-sm">Nama Perusahaan /
                                        <span class="text-xs text-white/70">Company Name</span> <span
                                            class="text-red-400">*</span></label>
                                    <input name="perusahaan[]" type="text"
                                        class="exp-input mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                        placeholder="Nama Tempat Bekerja / Workplace Name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white/90 drop-shadow-sm">Tanggal Mulai /
                                        <span class="text-xs text-white/70">Start Date</span> <span
                                            class="text-red-400">*</span></label>
                                    <input name="tanggal_mulai[]" type="month" onclick="this.showPicker()"
                                        class="exp-input mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-white/90 drop-shadow-sm">Tanggal Selesai /
                                        <span class="text-xs text-white/70">End Date</span> <span
                                            class="text-red-400">*</span></label>
                                    <input name="tanggal_selesai[]" type="month" onclick="this.showPicker()"
                                        class="exp-input mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm cursor-pointer">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-white/90 drop-shadow-sm">Deskripsi Pekerjaan /
                                        <span class="text-xs text-white/70">Job Description</span> <span
                                            class="text-red-400">*</span></label>
                                    <textarea name="deskripsi_pekerjaan[]" rows="3"
                                        class="exp-input mt-1 block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 sm:text-sm transition-all shadow-inner backdrop-blur-sm"
                                        placeholder="Jelaskan secara singkat tugas dan tanggung jawab Anda / Briefly explain your tasks and responsibilities"></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add_experience"
                            class="mt-4 flex items-center gap-2 text-sm font-bold text-white bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg border border-white/20 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Tambah Pengalaman / <span class="text-xs font-normal">Add Experience</span>
                        </button>
                    </div>
                </section>

                <!-- 5. UPLOAD DOKUMEN / UPLOAD DOCUMENTS -->
                <section>
                    <h3
                        class="text-xl font-bold text-white border-b border-white/30 pb-3 mb-6 drop-shadow-sm flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>
                        Upload Dokumen / <span class="text-sm font-normal text-white/80">Upload Documents</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Upload CV -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 relative">
                            <label for="file_cv" class="block text-sm font-medium text-white/90 drop-shadow-sm mb-3">Upload
                                CV <span class="text-red-400">*</span> <span class="text-xs text-white/60">(PDF, Maks/Max
                                    5MB)</span></label>
                            <input id="file_cv" name="file_cv" type="file" accept=".pdf" required
                                class="block w-full text-sm text-white/70 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-white/20 file:text-white hover:file:bg-white/30 bg-white/5 border border-white/20 rounded-xl transition-all cursor-pointer">

                            <!-- Preview CV -->
                            <div id="preview_cv_container"
                                class="hidden mt-4 flex items-center justify-between bg-[#002855]/50 p-3 rounded-xl border border-white/10 transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <svg class="w-8 h-8 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="min-w-0">
                                        <p id="preview_cv_name" class="text-sm font-medium text-white truncate"></p>
                                        <p id="preview_cv_size" class="text-xs text-white/60"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 shrink-0 ml-2">
                                    <a id="view_cv_btn" href="#" target="_blank"
                                        class="p-2 text-blue-300 hover:text-blue-100 hover:bg-white/10 rounded-lg transition-colors"
                                        title="Lihat CV / View CV">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button type="button" id="delete_cv_btn"
                                        class="p-2 text-red-400 hover:text-red-300 hover:bg-white/10 rounded-lg transition-colors"
                                        title="Hapus CV / Delete CV">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Foto -->
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-5 relative">
                            <label for="file_foto"
                                class="block text-sm font-medium text-white/90 drop-shadow-sm mb-3">Upload Pas Foto / <span
                                    class="text-xs text-white/70">Upload Photo</span> <span class="text-red-400">*</span>
                                <span class="text-xs text-white/60">(JPG/PNG, Maks/Max 2MB)</span></label>
                            <input id="file_foto" name="file_foto" type="file" accept="image/*" required
                                class="block w-full text-sm text-white/70 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-white/20 file:text-white hover:file:bg-white/30 bg-white/5 border border-white/20 rounded-xl transition-all cursor-pointer">

                            <!-- Preview Foto -->
                            <div id="preview_foto_container"
                                class="hidden mt-4 flex items-center justify-between bg-[#002855]/50 p-3 rounded-xl border border-white/10 transition-all">
                                <div class="flex items-center gap-4 min-w-0">
                                    <img id="preview_foto_img" src="" alt="Preview"
                                        class="w-12 h-12 md:w-16 md:h-16 object-cover rounded-lg border border-white/20 shadow-md shrink-0 cursor-pointer hover:opacity-80 transition-opacity"
                                        title="Klik untuk memperbesar / Click to enlarge">
                                    <div class="min-w-0">
                                        <p id="preview_foto_name" class="text-sm font-medium text-white truncate"></p>
                                        <p id="preview_foto_size" class="text-xs text-white/60"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 shrink-0 ml-2">
                                    <a id="view_foto_btn" href="#" target="_blank"
                                        class="p-2 text-blue-300 hover:text-blue-100 hover:bg-white/10 rounded-lg transition-colors"
                                        title="Lihat Foto / View Photo">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button type="button" id="delete_foto_btn"
                                        class="p-2 text-red-400 hover:text-red-300 hover:bg-white/10 rounded-lg transition-colors"
                                        title="Hapus Foto / Delete Photo">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SUBMIT BUTTON -->
                <div class="pt-8 mt-8 border-t border-white/20 text-center">
                    <button type="submit"
                        class="group relative w-full md:w-auto inline-flex justify-center items-center py-4 px-12 border border-transparent text-lg font-bold rounded-xl text-[#003d7c] bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#003d7c] focus:ring-white shadow-[0_4px_20px_0_rgba(255,255,255,0.39)] transition-all duration-300 ease-in-out transform hover:-translate-y-1">
                        Simpan / Save
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                    <p class="mt-4 text-sm font-medium text-white/90 drop-shadow-sm">Setelah menyimpan, Anda akan menerima
                        kode OTP melalui Email untuk verifikasi keamanan akun. <br> <span
                            class="text-xs text-white/70">After saving, you will receive an OTP code via Email for account
                            security verification.</span></p>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-white/80">
                    Sudah punya akun? / <span class="text-white/70">Already have an account?</span>
                    <a href="{{ route('login') }}"
                        class="font-bold text-white hover:text-blue-200 transition-colors drop-shadow-sm ml-1 border-b border-white/30 hover:border-white">Masuk
                        di sini / Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInputs = document.querySelectorAll('.js-password-input');

            passwordInputs.forEach(input => {
                const container = input.closest('div').querySelector('.js-password-strength-container');
                const bar = container?.querySelector('.js-password-strength-bar');
                const text = container?.querySelector('.js-password-strength-text');

                if (input && container && bar && text) {
                    input.addEventListener('input', function () {
                        const val = this.value;
                        if (val.length > 0) {
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

                        if (strength <= 25) {
                            bar.className = 'js-password-strength-bar h-full rounded-full bg-red-500 transition-all duration-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]';
                            text.textContent = 'Sangat Lemah';
                            text.className = 'text-[11px] font-bold js-password-strength-text text-red-300 drop-shadow-md';
                        } else if (strength <= 50) {
                            bar.className = 'js-password-strength-bar h-full rounded-full bg-orange-400 transition-all duration-500 shadow-[0_0_10px_rgba(251,146,60,0.5)]';
                            text.textContent = 'Lemah';
                            text.className = 'text-[11px] font-bold js-password-strength-text text-orange-300 drop-shadow-md';
                        } else if (strength <= 75) {
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

            // Logika Status Kewarganegaraan
            const radiosKewarganegaraan = document.querySelectorAll('input[name="kewarganegaraan"]');
            const containerNik = document.getElementById('container_nik');
            const inputNik = document.getElementById('nik');
            const containerPaspor = document.getElementById('container_paspor');
            const inputPaspor = document.getElementById('paspor');
            const containerNegara = document.getElementById('container_negara');
            const inputNegara = document.getElementById('asal_negara');

            let isWna = false;

            radiosKewarganegaraan.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'WNI') {
                        isWna = false;
                        containerNik.classList.remove('hidden');
                        inputNik.setAttribute('required', 'required');

                        containerPaspor.classList.add('hidden');
                        inputPaspor.removeAttribute('required');

                        if (containerNegara) containerNegara.classList.add('hidden');
                        if (inputNegara) inputNegara.removeAttribute('required');

                        // Reset province & city fields to dropdown mode
                        const searchProvinsi = document.getElementById('search_provinsi');
                        const searchKota = document.getElementById('search_kota');
                        const hiddenProvinsi = document.getElementById('hidden_provinsi');

                        if (searchProvinsi) {
                            searchProvinsi.placeholder = "Ketik provinsi / Type province...";
                            if (searchProvinsi.nextElementSibling) searchProvinsi.nextElementSibling.classList.remove('hidden');
                        }
                        if (searchKota) {
                            searchKota.disabled = !hiddenProvinsi.value;
                            searchKota.placeholder = hiddenProvinsi.value ? "Ketik kota / Type city..." : "Pilih Provinsi Terlebih Dahulu";
                            if (searchKota.nextElementSibling) searchKota.nextElementSibling.classList.remove('hidden');
                        }
                    } else {
                        isWna = true;
                        containerNik.classList.add('hidden');
                        inputNik.removeAttribute('required');

                        containerPaspor.classList.remove('hidden');
                        inputPaspor.setAttribute('required', 'required');

                        if (containerNegara) containerNegara.classList.remove('hidden');
                        if (inputNegara) inputNegara.setAttribute('required', 'required');

                        // Set province & city fields to free text mode
                        const searchProvinsi = document.getElementById('search_provinsi');
                        const searchKota = document.getElementById('search_kota');
                        const listProvinsi = document.getElementById('list_provinsi');
                        const listKota = document.getElementById('list_kota');

                        if (searchProvinsi) {
                            searchProvinsi.placeholder = "Masukkan Provinsi / Enter Province";
                            if (searchProvinsi.nextElementSibling) searchProvinsi.nextElementSibling.classList.add('hidden');
                            if (listProvinsi) listProvinsi.classList.add('hidden');
                        }
                        if (searchKota) {
                            searchKota.disabled = false;
                            searchKota.placeholder = "Masukkan Kota/Kabupaten / Enter City";
                            if (searchKota.nextElementSibling) searchKota.nextElementSibling.classList.add('hidden');
                            if (listKota) listKota.classList.add('hidden');
                        }
                    }
                });
            });

            // Logika Pengalaman Kerja Dinamis
            const checkboxExp = document.getElementById('has_experience');
            const containerExp = document.getElementById('experience_container');
            const btnAddExp = document.getElementById('add_experience');

            // Toggle Container
            if (checkboxExp && containerExp) {
                checkboxExp.addEventListener('change', function () {
                    if (this.checked) {
                        containerExp.classList.remove('hidden');
                        toggleRequired(containerExp, true);
                    } else {
                        containerExp.classList.add('hidden');
                        toggleRequired(containerExp, false);
                    }
                });
            }

            // Fungsi toggle required
            function toggleRequired(container, isRequired) {
                const inputs = container.querySelectorAll('.exp-input');
                inputs.forEach(input => {
                    if (isRequired) {
                        input.setAttribute('required', 'required');
                    } else {
                        input.removeAttribute('required');
                    }
                });
            }

            // Tambah Pengalaman Baru
            if (btnAddExp && containerExp) {
                btnAddExp.addEventListener('click', function () {
                    const expItems = containerExp.querySelectorAll('.experience-item');
                    const firstExp = expItems[0];
                    const clone = firstExp.cloneNode(true);

                    // Kosongkan value
                    const inputs = clone.querySelectorAll('.exp-input');
                    inputs.forEach(input => {
                        input.value = '';
                    });

                    // Tampilkan tombol hapus
                    const removeBtn = clone.querySelector('.remove-exp-btn');
                    removeBtn.classList.remove('hidden');

                    // Event hapus
                    removeBtn.addEventListener('click', function () {
                        clone.remove();
                    });

                    // Sisipkan sebelum tombol tambah
                    containerExp.insertBefore(clone, btnAddExp);
                });
            }

            // Logika API Provinsi dan Kota (Searchable Custom Dropdown)
            const searchProvinsi = document.getElementById('search_provinsi');
            const listProvinsi = document.getElementById('list_provinsi');
            const hiddenProvinsi = document.getElementById('hidden_provinsi');

            const searchKota = document.getElementById('search_kota');
            const listKota = document.getElementById('list_kota');
            const hiddenKota = document.getElementById('hidden_kota');

            let dataProvinsi = [];
            let dataKota = [];

            if (searchProvinsi && searchKota) {
                // Setup outside click to close dropdowns
                document.addEventListener('click', function (e) {
                    if (!searchProvinsi.contains(e.target) && !listProvinsi.contains(e.target)) {
                        listProvinsi.classList.add('hidden');
                    }
                    if (!searchKota.contains(e.target) && !listKota.contains(e.target)) {
                        listKota.classList.add('hidden');
                    }
                });

                // Render function
                function renderList(listElement, data, onSelectCallback) {
                    listElement.innerHTML = '';
                    if (data.length === 0) {
                        listElement.innerHTML = '<div class="p-3 text-sm text-white/50 text-center">Tidak ada hasil</div>';
                        return;
                    }
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-2 text-sm text-white hover:bg-white/10 cursor-pointer transition-colors';
                        div.textContent = item.name;
                        div.addEventListener('click', () => onSelectCallback(item));
                        listElement.appendChild(div);
                    });
                }

                // Load Provinsi
                fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                    .then(response => response.json())
                    .then(provinces => {
                        dataProvinsi = provinces;
                        searchProvinsi.placeholder = "Ketik untuk mencari provinsi...";
                    })
                    .catch(err => {
                        console.error('Error loading provinces:', err);
                        searchProvinsi.placeholder = "Gagal memuat provinsi";
                    });

                // Provinsi Input / Search
                searchProvinsi.addEventListener('focus', function () {
                    if (isWna) return;
                    if (dataProvinsi.length > 0) {
                        renderList(listProvinsi, dataProvinsi, selectProvinsiHandler);
                        listProvinsi.classList.remove('hidden');
                    }
                });

                searchProvinsi.addEventListener('input', function () {
                    if (isWna) {
                        hiddenProvinsi.value = this.value;
                        return;
                    }
                    const keyword = this.value.toLowerCase();
                    const filtered = dataProvinsi.filter(p => p.name.toLowerCase().includes(keyword));
                    renderList(listProvinsi, filtered, selectProvinsiHandler);
                    listProvinsi.classList.remove('hidden');
                    // Reset hidden and kota when typing changes the selected value
                    hiddenProvinsi.value = '';
                    searchKota.disabled = true;
                    searchKota.value = '';
                    hiddenKota.value = '';
                });

                function selectProvinsiHandler(provItem) {
                    searchProvinsi.value = provItem.name;
                    hiddenProvinsi.value = provItem.name;
                    listProvinsi.classList.add('hidden');

                    // Fetch Kota
                    searchKota.disabled = true;
                    searchKota.value = '';
                    searchKota.placeholder = "Memuat Kota/Kabupaten...";
                    hiddenKota.value = '';

                    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provItem.id}.json`)
                        .then(response => response.json())
                        .then(regencies => {
                            dataKota = regencies;
                            searchKota.disabled = false;
                            searchKota.placeholder = "Ketik kota / Type city...";
                        })
                        .catch(err => {
                            console.error('Error loading regencies:', err);
                            searchKota.placeholder = "Gagal memuat kota";
                        });
                }

                // Kota Input / Search
                searchKota.addEventListener('focus', function () {
                    if (isWna) return;
                    if (dataKota.length > 0) {
                        renderList(listKota, dataKota, selectKotaHandler);
                        listKota.classList.remove('hidden');
                    }
                });

                searchKota.addEventListener('input', function () {
                    if (isWna) {
                        hiddenKota.value = this.value;
                        return;
                    }
                    const keyword = this.value.toLowerCase();
                    const filtered = dataKota.filter(k => k.name.toLowerCase().includes(keyword));
                    renderList(listKota, filtered, selectKotaHandler);
                    listKota.classList.remove('hidden');
                    hiddenKota.value = '';
                });

                function selectKotaHandler(kotaItem) {
                    searchKota.value = kotaItem.name;
                    hiddenKota.value = kotaItem.name;
                    listKota.classList.add('hidden');
                }
            }

            // Logika Preview Upload Dokumen
            const fileCv = document.getElementById('file_cv');
            const fileFoto = document.getElementById('file_foto');

            function formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
            }

            if (fileCv) {
                let cvBlobUrl = null;
                fileCv.addEventListener('change', function () {
                    const container = document.getElementById('preview_cv_container');
                    const nameEl = document.getElementById('preview_cv_name');
                    const sizeEl = document.getElementById('preview_cv_size');
                    const viewBtn = document.getElementById('view_cv_btn');

                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        nameEl.textContent = file.name;
                        sizeEl.textContent = formatBytes(file.size);

                        if (cvBlobUrl) URL.revokeObjectURL(cvBlobUrl);
                        cvBlobUrl = URL.createObjectURL(file);
                        viewBtn.href = cvBlobUrl;

                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                });

                document.getElementById('delete_cv_btn').addEventListener('click', function () {
                    fileCv.value = '';
                    document.getElementById('preview_cv_container').classList.add('hidden');
                    if (cvBlobUrl) URL.revokeObjectURL(cvBlobUrl);
                });
            }

            if (fileFoto) {
                let fotoBlobUrl = null;
                fileFoto.addEventListener('change', function () {
                    const container = document.getElementById('preview_foto_container');
                    const imgEl = document.getElementById('preview_foto_img');
                    const nameEl = document.getElementById('preview_foto_name');
                    const sizeEl = document.getElementById('preview_foto_size');
                    const viewBtn = document.getElementById('view_foto_btn');

                    if (this.files && this.files[0]) {
                        const file = this.files[0];

                        if (file.type.startsWith('image/')) {
                            if (fotoBlobUrl) URL.revokeObjectURL(fotoBlobUrl);
                            fotoBlobUrl = URL.createObjectURL(file);

                            imgEl.src = fotoBlobUrl;
                            viewBtn.href = fotoBlobUrl;

                            nameEl.textContent = file.name;
                            sizeEl.textContent = formatBytes(file.size);
                            container.classList.remove('hidden');
                        } else {
                            container.classList.add('hidden');
                        }
                    } else {
                        container.classList.add('hidden');
                    }
                });

                document.getElementById('preview_foto_img').addEventListener('click', function () {
                    if (fotoBlobUrl) window.open(fotoBlobUrl, '_blank');
                });

                document.getElementById('delete_foto_btn').addEventListener('click', function () {
                    fileFoto.value = '';
                    document.getElementById('preview_foto_container').classList.add('hidden');
                    if (fotoBlobUrl) URL.revokeObjectURL(fotoBlobUrl);
                });
            }
        });
    </script>
@endsection
