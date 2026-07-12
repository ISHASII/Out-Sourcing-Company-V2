@extends('layouts.dashboard')

@section('dashboard-title', 'Pengaturan Profil')

@section('dashboard-content')
@php
    $profile = Auth::user()->profile;
    $citizenship = $profile->extras['citizenship'] ?? 'WNI';
    $hasExp = ($profile->experience_years ?? 0) > 0 || !empty($profile->extras['experiences'] ?? []) ? 'IYA' : 'TIDAK';
    $experiences = $profile->extras['experiences'] ?? [];
@endphp
<div class="space-y-6 animate-fade-in" x-data="{ 
    kewarganegaraan: '{{ $citizenship }}',
    punyaPengalaman: '{{ $hasExp }}',
    pengalamanList: [
        @if(count($experiences) > 0)
            @foreach($experiences as $exp)
                { 
                    id: {{ $loop->iteration }}, 
                    company: '{{ addslashes($exp['company'] ?? '') }}', 
                    position: '{{ addslashes($exp['position'] ?? '') }}', 
                    start_date: '{{ $exp['start_date'] ?? '' }}', 
                    end_date: '{{ $exp['end_date'] ?? '' }}', 
                    description: '{{ addslashes(str_replace(["\r", "\n"], ' ', $exp['description'] ?? '')) }}' 
                },
            @endforeach
        @else
            { id: Date.now(), company: '', position: '', start_date: '', end_date: '', description: '' }
        @endif
    ]
}">

    <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-bl-full -z-10 opacity-50"></div>
        
        <div class="mb-8">
            <h3 class="text-2xl font-extrabold text-[#003d7c]">Data Pribadi Pelamar / Applicant Personal Data</h3>
            <p class="text-sm text-slate-500 mt-1">Lengkapi dan perbarui data diri Anda sesuai dengan identitas resmi. / Complete and update your personal data according to your official identity.</p>
        </div>

        <form action="{{ route('pelamar.profil.update') }}" method="POST" id="form-profil-pelamar" enctype="multipart/form-data" class="space-y-8" @submit.prevent="$dispatch('open-confirm-modal', {
            title: 'Simpan Perubahan Profil?',
            message: 'Apakah Anda yakin ingin memperbarui data profil Anda? Pastikan semua informasi sudah benar dan sesuai identitas.',
            confirmText: 'Ya, Simpan',
            type: 'info',
            actionType: 'submit',
            formElement: document.getElementById('form-profil-pelamar')
        })">
            @csrf
            
            <!-- Section 1: Nama & Kontak -->
            <div class="space-y-4">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">Informasi Dasar / Basic Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Depan / First Name *</label>
                        <input type="text" name="nama_depan" value="{{ explode(' ', Auth::user()->name)[0] ?? '' }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Belakang / Last Name</label>
                        <input type="text" name="nama_belakang" value="{{ explode(' ', Auth::user()->name)[1] ?? '' }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email *</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full bg-slate-100 border border-slate-200 text-slate-500 text-sm rounded-xl focus:ring-transparent focus:border-slate-200 block p-3 cursor-not-allowed" readonly title="Email tidak dapat diubah">
                        <p class="text-[10px] text-slate-400 mt-1">Email digunakan sebagai identitas login utama. / Email is used as the main login identity.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor HP / WhatsApp Number *</label>
                        <input type="text" name="no_hp" value="{{ $profile->phone ?? '' }}" placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>
                    </div>
                </div>
            </div>

            <!-- Section 2: Kelahiran & Domisili -->
            <div class="space-y-4 pt-4">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">Kelahiran & Domisili / Birth & Domicile</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tempat Lahir / Place of Birth *</label>
                        <input type="text" name="tempat_lahir" value="{{ $profile->birth_place ?? '' }}" placeholder="Kota Kelahiran" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Lahir / Date of Birth *</label>
                        <!-- Menggunakan onClick="this.showPicker()" agar langsung keluar kalender saat diklik di manapun pada input -->
                        <input type="date" name="tanggal_lahir" value="{{ $profile?->birth_date?->format('Y-m-d') ?? '' }}" onclick="this.showPicker()" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors cursor-pointer" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jenis Kelamin / Gender *</label>
                        <select name="jenis_kelamin" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>
                            <option value="">Pilih Jenis Kelamin... / Select Gender...</option>
                            <option value="Pria" {{ ($profile->gender ?? '') === 'male' ? 'selected' : '' }}>Pria (Male)</option>
                            <option value="Wanita" {{ ($profile->gender ?? '') === 'female' ? 'selected' : '' }}>Wanita (Female)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pendidikan Terakhir / Last Education *</label>
                        <select name="pendidikan" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>
                            <option value="">Pilih Pendidikan... / Select Education...</option>
                            <option value="SMA/SMK" {{ ($profile->education_level ?? '') === 'SMA/SMK' ? 'selected' : '' }}>SMA / SMK Sederajat</option>
                            <option value="D3" {{ ($profile->education_level ?? '') === 'D3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                            <option value="S1" {{ ($profile->education_level ?? '') === 'S1' ? 'selected' : '' }}>Strata 1 (S1) / D4</option>
                            <option value="S2" {{ ($profile->education_level ?? '') === 'S2' ? 'selected' : '' }}>Strata 2 (S2)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jurusan Pendidikan / Major</label>
                        <input type="text" name="jurusan" value="{{ $profile->major ?? '' }}" placeholder="Contoh: Keperawatan, Teknik, dll." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Domisili Lengkap / Complete Domicile Address *</label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat tempat tinggal Anda saat ini..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>{{ $profile->address ?? '' }}</textarea>
                </div>
            </div>

            <!-- Section 3: Kewarganegaraan & Identitas -->
            <div class="space-y-4 pt-4 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">Status Kewarganegaraan / Citizenship Status</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kewarganegaraan (Nationality) *</label>
                        <select x-model="kewarganegaraan" name="status_kewarganegaraan" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>
                            <option value="WNI" {{ $citizenship === 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                            <option value="WNA" {{ $citizenship === 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing / Foreigner)</option>
                        </select>
                    </div>
                    
                    <!-- Dinamis: NIK atau Passport -->
                    <div x-show="kewarganegaraan === 'WNI'" x-transition>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor NIK / National ID Number *</label>
                        <input type="text" name="nik" value="{{ $profile->extras['nik'] ?? '' }}" placeholder="16 Digit NIK KTP" maxlength="16" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors">
                    </div>

                    <div x-show="kewarganegaraan === 'WNA'" x-transition style="display: none;">
                        <label class="block text-sm font-bold text-slate-700 mb-2">No. Passport *</label>
                        <input type="text" name="passport" value="{{ $profile->extras['paspor'] ?? '' }}" placeholder="Nomor Passport" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors">
                    </div>
                </div>

                <!-- Tambahan Form jika WNA -->
                <div x-show="kewarganegaraan === 'WNA'" x-transition style="display: none;" class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-slate-200/60 mt-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Asal Negara / Country *</label>
                        <input type="text" name="negara_asal" value="{{ $profile->extras['asal_negara'] ?? '' }}" placeholder="Origin Country" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Provinsi / Province *</label>
                        <input type="text" name="provinsi_wna" value="{{ $profile->province ?? '' }}" placeholder="Province / State" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kota / City *</label>
                        <input type="text" name="kota_wna" value="{{ $profile->city ?? '' }}" placeholder="City" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors">
                    </div>
                </div>
            </div>

            <!-- Section 4: Pengalaman Kerja -->
            <div class="space-y-4 pt-4">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">Pengalaman Kerja / Work Experience</h4>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Apakah Anda Memiliki Pengalaman Kerja? / Do you have work experience? *</label>
                    <select x-model="punyaPengalaman" name="punya_pengalaman" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" required>
                        <option value="TIDAK">TIDAK (Belum Berpengalaman / No work experience)</option>
                        <option value="IYA">Saya memiliki pengalaman kerja / I have work experience</option>
                    </select>
                </div>

                <!-- Dinamis: Detail Pengalaman Kerja Multiple -->
                <div x-show="punyaPengalaman === 'IYA'" x-transition style="display: none;" class="space-y-6 pt-4 border-t border-slate-200/60 mt-4">
                    
                    <template x-for="(item, index) in pengalamanList" :key="item.id">
                        <div class="bg-slate-50/70 border border-slate-200 rounded-2xl p-6 relative group">
                            <!-- Hapus Tombol (Hanya muncul jika lebih dari 1) -->
                            <button type="button" @click="pengalamanList.splice(index, 1)" x-show="pengalamanList.length > 1" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 transition-colors" title="Hapus Pengalaman">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            
                            <h5 class="text-[#003d7c] font-extrabold mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-[#003d7c] w-6 h-6 rounded-full flex items-center justify-center text-xs" x-text="index + 1"></span>
                                Pengalaman Kerja
                            </h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Pekerjaan / Job Title *</label>
                                    <input type="text" :name="'pengalaman['+index+'][posisi_pekerjaan]'" x-model="item.position" placeholder="Contoh / Example: Staff Administrasi" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" :required="punyaPengalaman === 'IYA'">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Perusahaan / Company Name *</label>
                                    <input type="text" :name="'pengalaman['+index+'][nama_perusahaan]'" x-model="item.company" placeholder="Nama Tempat Bekerja / Workplace Name" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" :required="punyaPengalaman === 'IYA'">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai / Start Date *</label>
                                    <input type="month" :name="'pengalaman['+index+'][tanggal_mulai]'" x-model="item.start_date" onclick="this.showPicker()" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors cursor-pointer" :required="punyaPengalaman === 'IYA'">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Selesai / End Date *</label>
                                    <input type="month" :name="'pengalaman['+index+'][tanggal_selesai]'" x-model="item.end_date" onclick="this.showPicker()" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors cursor-pointer" :required="punyaPengalaman === 'IYA'">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Pekerjaan / Job Description *</label>
                                <textarea :name="'pengalaman['+index+'][deskripsi_pekerjaan]'" x-model="item.description" rows="3" placeholder="Gambarkan secara singkat tugas dan tanggung jawab Anda..." class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-[#003d7c] focus:border-[#003d7c] block p-3 transition-colors" :required="punyaPengalaman === 'IYA'"></textarea>
                            </div>
                        </div>
                    </template>

                    <!-- Tombol Tambah Pengalaman -->
                    <button type="button" @click="pengalamanList.push({ id: Date.now(), company: '', position: '', start_date: '', end_date: '', description: '' })" class="w-full py-4 border-2 border-dashed border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 rounded-2xl font-bold transition-all flex items-center justify-center gap-2 group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Pengalaman Kerja Lainnya
                    </button>
                    
                </div>
            </div>

            <!-- Section 5: Berkas Pendukung (CV & Foto Profil) -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-2">Berkas Pendukung / Supporting Documents</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Foto Profil -->
                    <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100/80 flex flex-col justify-between">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Foto Profil / Profile Photo</label>
                            <p class="text-xs text-slate-400 mb-4">Format gambar (JPG, JPEG, PNG). Maksimal 2MB. / Image format (JPG, JPEG, PNG). Max 2MB.</p>
                            
                            <!-- Input File -->
                            <div class="relative flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 hover:border-blue-400 bg-white hover:bg-blue-50/10 rounded-2xl cursor-pointer transition-all group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-xs text-slate-500 font-medium group-hover:text-blue-600 transition-colors">Pilih Foto Baru / Choose New Photo</p>
                                    </div>
                                    <input type="file" name="file_foto" accept="image/*" class="hidden" onchange="previewImage(this)">
                                </label>
                            </div>
                        </div>

                        <!-- Preview Foto Profil -->
                        <div class="mt-6 flex items-center gap-4 bg-white p-4 rounded-xl border border-slate-100">
                            <div class="relative shrink-0" id="photo-preview-container">
                                @if($profile && $profile->photo_path)
                                    <a id="photo-link-preview" href="{{ asset('storage/' . $profile->photo_path) }}" target="_blank" title="Klik untuk memperbesar / Click to enlarge" class="block group relative overflow-hidden rounded-lg">
                                        <img id="img-preview" src="{{ asset('storage/' . $profile->photo_path) }}" class="w-16 h-16 object-cover rounded-lg border border-slate-200 transition-transform group-hover:scale-110 duration-200">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity rounded-lg">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path></svg>
                                        </div>
                                    </a>
                                @else
                                    <div id="photo-placeholder" class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center border border-slate-200">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <a id="photo-link-preview" href="" target="_blank" title="Klik untuk memperbesar / Click to enlarge" class="hidden block group relative overflow-hidden rounded-lg">
                                        <img id="img-preview" src="" class="w-16 h-16 object-cover rounded-lg border border-slate-200 transition-transform group-hover:scale-110 duration-200">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity rounded-lg">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path></svg>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h5 class="text-xs font-bold text-slate-700">Foto Saat Ini / Current Photo</h5>
                                <p id="photo-filename" class="text-[10px] text-slate-400 mt-1 truncate max-w-[150px]">
                                    @if($profile && $profile->photo_path)
                                        Foto Profil Terunggah
                                    @else
                                        Belum mengunggah foto / No photo uploaded
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Curriculum Vitae (CV) -->
                    <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100/80 flex flex-col justify-between">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Curriculum Vitae / CV</label>
                            <p class="text-xs text-slate-400 mb-4">Format berkas PDF saja. Maksimal 5MB. / PDF file format only. Max 5MB.</p>
                            
                            <!-- Input File -->
                            <div class="relative flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 hover:border-blue-400 bg-white hover:bg-blue-50/10 rounded-2xl cursor-pointer transition-all group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-xs text-slate-500 font-medium group-hover:text-blue-600 transition-colors">Pilih CV Baru (PDF) / Choose New CV</p>
                                    </div>
                                    <input type="file" name="file_cv" accept="application/pdf" class="hidden" onchange="previewCVName(this)">
                                </label>
                            </div>
                        </div>

                        <!-- Preview CV -->
                        <div class="mt-6 flex items-center gap-4 bg-white p-4 rounded-xl border border-slate-100">
                            <div class="shrink-0">
                                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center border border-red-100">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h5 class="text-xs font-bold text-slate-700">CV Saat Ini / Current CV</h5>
                                <div class="flex items-center justify-between gap-2 mt-1">
                                    <p id="cv-filename" class="text-[10px] text-slate-400 truncate max-w-[150px]">
                                        @if($profile && $profile->cv_path)
                                            CV Terunggah
                                        @else
                                            Belum mengunggah CV / No CV uploaded
                                        @endif
                                    </p>
                                    <a id="cv-link-preview" href="{{ $profile && $profile->cv_path ? asset('storage/' . $profile->cv_path) : '#' }}" target="_blank" class="{{ $profile && $profile->cv_path ? '' : 'hidden' }} px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-600 font-extrabold text-[10px] rounded-lg transition-all border border-red-200/50 flex items-center gap-1 shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat CV
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 flex justify-end gap-3 border-t border-slate-200">
                <button type="reset" class="px-6 py-3 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-8 py-3 rounded-xl font-bold text-white bg-[#003d7c] hover:bg-blue-800 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>

            <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('img-preview');
                        const placeholder = document.getElementById('photo-placeholder');
                        const link = document.getElementById('photo-link-preview');
                        const filename = document.getElementById('photo-filename');
                        
                        if (preview) {
                            preview.src = e.target.result;
                        }
                        if (placeholder) {
                            placeholder.classList.add('hidden');
                        }
                        if (link) {
                            link.href = e.target.result;
                            link.classList.remove('hidden');
                        }
                        if (filename) {
                            filename.textContent = input.files[0].name + ' (Baru dipilih)';
                            filename.classList.remove('text-slate-400');
                            filename.classList.add('text-blue-600', 'font-semibold');
                        }
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function previewCVName(input) {
                if (input.files && input.files[0]) {
                    const filename = document.getElementById('cv-filename');
                    const link = document.getElementById('cv-link-preview');
                    if (filename) {
                        filename.textContent = input.files[0].name + ' (Baru dipilih)';
                        filename.classList.remove('text-slate-400');
                        filename.classList.add('text-blue-600', 'font-semibold');
                    }
                    if (link) {
                        const url = URL.createObjectURL(input.files[0]);
                        link.href = url;
                        link.classList.remove('hidden');
                    }
                }
            }
            </script>

        </form>
    </div>
</div>
@endsection
