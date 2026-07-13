@extends('layouts.dashboard')

@section('dashboard-title', 'Edit Lowongan')

@section('dashboard-content')
    <div class="space-y-6 animate-fade-in" x-data="{
        category: '{{ old('category', $posting->category) }}',
        allCriteria: {{ json_encode($allCriteria) }},
        activeCriteria: [],
        totalCF: 0,
        totalSF: 0,
        totalWeight: 0,
        salaryHidden: {{ old('salary_hidden', $posting->salary_hidden) ? 'true' : 'false' }},
        selectedNonactiveKey: '',

        updateCategory() {
            if (!this.category) {
                this.activeCriteria = [];
                return;
            }
            
            // Partition criteria into selected category and other unique criteria
            const categoryList = this.allCriteria.filter(c => c.category === this.category);
            const otherList = [];
            this.allCriteria.forEach(c => {
                if (c.category === this.category) return;
                if (categoryList.some(catItem => catItem.key === c.key)) return;
                if (otherList.some(otherItem => otherItem.key === c.key)) return;
                otherList.push(c);
            });
            const list = [...categoryList, ...otherList];

            const savedCriteria = {{ json_encode($posting->requirements_config['criteria'] ?? []) }};
            const isPostingCategory = this.category === '{{ $posting->category }}';

            this.activeCriteria = list.map(c => {
                const isCatItem = categoryList.some(catItem => catItem.key === c.key);
                let status = isCatItem ? c.default_status : 'nonaktif';
                let weight = isCatItem ? c.default_weight : 0;
                let val = '';
                
                if (c.key === 'gender') val = 'both';
                else if (c.key === 'age') {
                    let minAge = 25;
                    let maxAge = 35;
                    if (c.config && c.config.min_default) {
                        minAge = c.config.min_default;
                        maxAge = c.config.max_default;
                    }
                    val = { min: minAge, max: maxAge };
                }
                else if (c.key === 'education') val = 'SMA/SMK';
                else if (c.key === 'experience') val = 0;
                else if (c.key === 'placement_ready') val = { type: 'anywhere', city: '' };
                else if (c.key === 'major') val = '';
                else if (c.key === 'placement_choices') val = '';

                // Load saved values if editing the same category
                if (isPostingCategory && savedCriteria.length > 0) {
                    const saved = savedCriteria.find(sc => sc.key === c.key);
                    if (saved) {
                        status = saved.status || 'nonaktif';
                        weight = saved.weight !== undefined ? saved.weight : (isCatItem ? c.default_weight : 0);
                        if (saved.value !== null && saved.value !== undefined) {
                            if (c.key === 'age' && typeof saved.value === 'object') {
                                val.min = saved.value.min !== undefined ? saved.value.min : val.min;
                                val.max = saved.value.max !== undefined ? saved.value.max : val.max;
                            } else if (c.key === 'placement_ready' && typeof saved.value === 'object') {
                                val.type = saved.value.type !== undefined ? saved.value.type : val.type;
                                val.city = saved.value.city !== undefined ? saved.value.city : val.city;
                            } else {
                                val = saved.value;
                            }
                        }
                    }
                }

                return {
                    id: c.id,
                    key: c.key,
                    label: c.label,
                    type: c.type,
                    config: c.config,
                    status: status,
                    weight: weight,
                    value: val
                };
            });

            // Also load any dynamic on-the-fly criteria that are not in the master list but exist in saved criteria
            if (isPostingCategory && savedCriteria.length > 0) {
                savedCriteria.forEach(sc => {
                    const existsInMaster = list.some(c => c.key === sc.key);
                    if (!existsInMaster) {
                        this.activeCriteria.push({
                            id: null,
                            key: sc.key,
                            label: sc.label || sc.key.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()),
                            type: sc.type || 'file',
                            config: {},
                            status: sc.status || 'nonaktif',
                            weight: sc.weight || 0,
                            value: sc.value || ''
                        });
                    }
                });
            }

            this.calculateTotals();
        },

        calculateTotals() {
            let cf = 0;
            let sf = 0;
            this.activeCriteria.forEach(c => {
                if (c.status === 'core') {
                    cf += parseInt(c.weight || 0);
                } else if (c.status === 'secondary') {
                    sf += parseInt(c.weight || 0);
                }
            });
            this.totalCF = cf;
            this.totalSF = sf;
            this.totalWeight = cf + sf;
        },

        isWeightValid() {
            if (this.totalWeight !== 100) return false;
            // Mix Case: Wajib & Tambahan both exist
            if (this.totalCF > 0 && this.totalSF > 0) {
                return this.totalCF <= 60 && this.totalSF <= 40;
            }
            // Pure Core Factor Case (all Wajib)
            if (this.totalCF === 100 && this.totalSF === 0) return true;
            // Pure Secondary Factor Case (all Tambahan)
            if (this.totalCF === 0 && this.totalSF === 100) return true;
            return false;
        },

        selectedNonactiveKey: ''
    }"
    x-init="
        $watch('category', val => updateCategory());
        if (category) updateCategory();
    ">
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <!-- Decorative gradient banner top -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-550 to-orange-550"></div>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Edit Lowongan Pekerjaan</h3>
                    <p class="text-xs text-slate-500 mt-1">Sesuaikan kriteria persyaratan kustom dan pembobotan kualifikasi SPK.</p>
                </div>
                <a href="{{ route('hrd.hiring') }}"
                    class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-100">
                    Kembali
                </a>
            </div>

            <form action="{{ route('hrd.hiring.update', $posting) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                {{-- JSON Active Criteria representation submitted to server --}}
                <input type="hidden" name="active_criteria_data" :value="JSON.stringify(activeCriteria)">

                <!-- Section 1: Informasi Dasar -->
                <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-200/60 space-y-4">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200/55 pb-2">Informasi Dasar Lowongan</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-600">Judul Lowongan</label>
                            <input type="text" name="title" value="{{ old('title', $posting->title) }}"
                                class="w-full mt-2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition-all" required>
                            @error('title')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-600">Kategori Pekerjaan</label>
                            <select name="category" id="category-select" x-model="category"
                                class="w-full mt-2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 text-sm transition-all bg-white" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" @selected(old('category', $posting->category) === $cat)>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-600">Deskripsi Lowongan (Opsional)</label>
                        <textarea name="description" rows="3"
                            class="w-full mt-2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 text-sm transition-all">{{ old('description', $posting->description) }}</textarea>
                    </div>
                </div>

                <!-- Section 2: Kriteria Persyaratan Dinamis dari DB -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6" x-show="category" x-cloak x-transition>
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#003d7c] flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-800">Persyaratan Kualifikasi Jabatan</h4>
                            <p class="text-xs text-slate-500">Tentukan kriteria kualifikasi yang Anda inginkan. Kriteria yang dinonaktifkan dapat ditambahkan kembali lewat tombol "+ Tambah Kriteria" di bawah.</p>
                        </div>
                    </div>

                    <div class="space-y-4 divide-y divide-slate-100">
                        <template x-for="(c, idx) in activeCriteria" :key="c.key">
                            <div class="pt-4 flex flex-col md:flex-row md:items-start justify-between gap-4" x-show="c.status !== 'nonaktif'">
                                {{-- Kiri: Label & Status --}}
                                <div class="w-full md:w-5/12 space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" :class="c.status === 'core' ? 'bg-blue-600' : (c.status === 'secondary' ? 'bg-amber-500' : 'bg-slate-300')"></span>
                                        <span class="text-xs font-extrabold text-slate-800" x-text="c.label"></span>
                                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-500" x-text="c.type === 'file' ? 'File' : (c.type === 'checkbox' ? 'Checkbox' : (c.type === 'text' ? 'Teks' : 'Angka'))"></span>
                                    </div>
                                    <div class="flex items-center gap-0.5 bg-slate-100 p-0.5 rounded-lg text-[10px] w-fit">
                                        <button type="button" @click="c.status = 'core'; calculateTotals()" :class="c.status === 'core' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2.5 py-1 rounded-md transition-all">Wajib</button>
                                        <button type="button" @click="c.status = 'secondary'; calculateTotals()" :class="c.status === 'secondary' ? 'bg-[#003d7c] text-white font-bold shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="px-2.5 py-1 rounded-md transition-all">Tambahan</button>
                                        <button type="button" @click="c.status = 'nonaktif'; c.weight = 0; calculateTotals()" class="px-2 py-1 text-rose-600 hover:bg-rose-50 rounded-md transition-all ml-1" title="Nonaktifkan (Hapus)">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Tengah: Input Value --}}
                                <div class="w-full md:w-5/12" x-show="c.status !== 'nonaktif'">
                                    {{-- 1. Gender --}}
                                    <template x-if="c.key === 'gender'">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Syarat Gender</label>
                                            <select name="req_gender_value" x-model="c.value" :disabled="c.status === 'nonaktif'" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-blue-500 font-semibold text-slate-700">
                                                <option value="male">Pria saja</option>
                                                <option value="female">Wanita saja</option>
                                                <option value="both">Pria dan Wanita</option>
                                            </select>
                                        </div>
                                    </template>

                                    {{-- 2. Age --}}
                                    <template x-if="c.key === 'age'">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="text-[10px] font-bold text-slate-400 block mb-1">Usia Min</label>
                                                <input type="number" name="req_age_min" x-model="c.value.min" :disabled="c.status === 'nonaktif'" min="18" max="65" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 font-bold text-slate-800">
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold text-slate-400 block mb-1">Usia Max</label>
                                                <input type="number" name="req_age_max" x-model="c.value.max" :disabled="c.status === 'nonaktif'" min="18" max="65" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 font-bold text-slate-800">
                                            </div>
                                        </div>
                                    </template>

                                    {{-- 3. Education --}}
                                    <template x-if="c.key === 'education'">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Min Pendidikan</label>
                                            <select name="req_education_value" x-model="c.value" :disabled="c.status === 'nonaktif'" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-blue-500 font-semibold text-slate-700">
                                                @foreach($educationLevels as $level)
                                                    <option value="{{ $level }}">{{ $level }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </template>

                                    {{-- 4. Experience --}}
                                    <template x-if="c.key === 'experience'">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Pengalaman Min (Tahun)</label>
                                            <input type="number" name="req_experience_value" x-model="c.value" :disabled="c.status === 'nonaktif'" min="0" max="30" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 font-bold text-slate-800">
                                        </div>
                                    </template>

                                    {{-- 5. Placement Ready --}}
                                    <template x-if="c.key === 'placement_ready'">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Tipe Kesiapan Penempatan</label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <button type="button" @click="c.value.type = 'anywhere'" :class="c.value.type === 'anywhere' ? 'border-[#003d7c] bg-blue-50/50 text-[#003d7c]' : 'border-slate-200 text-slate-500'" class="px-2 py-1.5 rounded-lg border text-[10px] font-bold transition-all text-center">
                                                    Siap Di mana Saja
                                                </button>
                                                <button type="button" @click="c.value.type = 'specific'" :class="c.value.type === 'specific' ? 'border-[#003d7c] bg-blue-50/50 text-[#003d7c]' : 'border-slate-200 text-slate-500'" class="px-2 py-1.5 rounded-lg border text-[10px] font-bold transition-all text-center">
                                                    Spesifik Lokasi
                                                </button>
                                            </div>
                                            <input type="hidden" name="req_placement_type" :value="c.value.type" :disabled="c.status === 'nonaktif'">

                                            <div x-show="c.value.type === 'specific'" class="grid grid-cols-2 gap-2 pt-1">
                                                <div>
                                                    <select id="location-province" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] focus:outline-none bg-white">
                                                        <option value="">Pilih Provinsi</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <select id="location-city" name="location_city" class="w-full px-2 py-1.5 rounded-lg border border-slate-200 text-[10px] focus:outline-none bg-white" :required="c.value.type === 'specific'" :disabled="c.status === 'nonaktif'">
                                                        <option value="">Pilih Kota</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- 6. Major --}}
                                    <template x-if="c.key === 'major'">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Jurusan yang Diperbolehkan</label>
                                            <input type="text" name="req_major_value" x-model="c.value" :disabled="c.status === 'nonaktif'" placeholder="Pisahkan koma, misal: Keperawatan, Umum" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 text-slate-800">
                                        </div>
                                    </template>

                                    {{-- 7. Placement Choices --}}
                                    <template x-if="c.key === 'placement_choices'">
                                        <div>
                                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Pilihan Kota Penempatan</label>
                                            <input type="text" name="req_placement_choices_value" x-model="c.value" :disabled="c.status === 'nonaktif'" placeholder="Contoh: Tangerang, Jakarta Barat" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-500 text-slate-800">
                                        </div>
                                    </template>

                                    {{-- 8. File Uploads / Custom fields --}}
                                    <template x-if="!['gender','age','education','experience','placement_ready','major','placement_choices'].includes(c.key)">
                                        <div class="text-[10px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span x-text="'Persyaratan pelamar bertipe ' + (c.type === 'file' ? 'Upload Berkas' : (c.type === 'checkbox' ? 'Checkbox' : (c.type === 'text' ? 'Teks Bebas' : 'Input Angka'))) + '.'"></span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Kanan: Input Bobot --}}
                                <div class="w-full md:w-2/12" x-show="c.status !== 'nonaktif'">
                                    <label class="text-[10px] font-bold text-slate-400 block mb-1">Bobot (%)</label>
                                    <input type="number" x-model="c.weight" @input="calculateTotals()" :disabled="c.status === 'nonaktif'" min="1" max="100" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:border-[#003d7c] focus:ring-1 focus:ring-[#003d7c]">
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Actions block for Add Criteria --}}
                    <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50 p-4 rounded-2xl" x-data="{ showAddDropdown: false }">
                        {{-- Button to show dropdown --}}
                        <div x-show="!showAddDropdown && activeCriteria.some(c => c.status === 'nonaktif')" class="w-full flex justify-start">
                            <button type="button" @click="showAddDropdown = true" class="px-4 py-2 bg-[#003d7c] hover:bg-[#002f60] text-white text-xs font-bold rounded-xl transition-all shadow-xs">
                                + Tambah Kriteria
                            </button>
                        </div>

                        {{-- Dropdown and Confirm Button --}}
                        <div x-show="showAddDropdown && activeCriteria.some(c => c.status === 'nonaktif')" class="flex flex-wrap items-center gap-3 w-full animate-fade-in" x-cloak>
                            <span class="text-xs font-bold text-slate-700">Tambah Persyaratan:</span>
                            <select x-model="selectedNonactiveKey" class="px-3 py-2 rounded-xl border border-slate-200 text-xs bg-white focus:outline-none focus:border-[#003d7c] text-slate-700 font-semibold min-w-[250px]">
                                <option value="">-- Pilih Kriteria --</option>
                                <template x-for="c in activeCriteria.filter(x => x.status === 'nonaktif')" :key="c.key">
                                    <option :value="c.key" x-text="c.label + ' (' + (c.type === 'file' ? 'Upload Berkas' : (c.type === 'text' ? 'Input Teks' : (c.type === 'checkbox' ? 'Checkbox' : (c.type === 'number' ? 'Input Angka' : (c.type === 'select' ? 'Pilihan' : (c.type === 'range' ? 'Rentang' : c.type)))))) + ')'"></option>
                                </template>
                            </select>
                            <button type="button" @click="if (selectedNonactiveKey) {
                                let found = activeCriteria.find(x => x.key === selectedNonactiveKey);
                                if (found) {
                                    found.status = found.config && found.config.default_status ? found.config.default_status : 'secondary';
                                    found.weight = found.config && found.config.default_weight !== undefined ? found.config.default_weight : 10;
                                }
                                selectedNonactiveKey = '';
                                showAddDropdown = false;
                                calculateTotals();
                            }" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition">
                                Tambahkan
                            </button>
                            <button type="button" @click="showAddDropdown = false; selectedNonactiveKey = ''" class="px-4 py-2 bg-slate-250 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
                                Batal
                            </button>
                        </div>
                    </div>

                    {{-- Weight Allocation Summary Panel --}}
                    <div class="mt-8 border-t border-slate-150 pt-6 space-y-4">
                        <h5 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Alokasi Persentase Bobot SPK</h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100/60">
                                <span class="text-[10px] font-bold text-blue-800 uppercase block mb-1">Core Factor (Wajib)</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-blue-900" x-text="totalCF + '%'"></span>
                                    <span class="text-xs text-slate-550" x-show="totalSF > 0">/ Maks 60%</span>
                                    <span class="text-xs text-slate-550" x-show="totalSF === 0">/ Wajib 100%</span>
                                </div>
                                <span class="text-[10px] text-blue-700 block mt-1 flex items-center gap-1" x-show="totalCF > 60 && totalSF > 0"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg> Total Core Factor melebihi batas 60%!</span>
                            </div>

                            <div class="bg-amber-50/50 p-4 rounded-2xl border border-amber-100/60">
                                <span class="text-[10px] font-bold text-amber-800 uppercase block mb-1">Secondary Factor (Tambahan)</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-amber-900" x-text="totalSF + '%'"></span>
                                    <span class="text-xs text-slate-550" x-show="totalCF > 0">/ Maks 40%</span>
                                    <span class="text-xs text-slate-550" x-show="totalCF === 0">/ Wajib 100%</span>
                                </div>
                                <span class="text-[10px] text-amber-700 block mt-1 flex items-center gap-1" x-show="totalSF > 40 && totalCF > 0"><svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg> Total Secondary Factor melebihi batas 40%!</span>
                            </div>

                            <div class="p-4 rounded-2xl border transition" :class="isWeightValid() ? 'bg-emerald-50/50 border-emerald-100/60' : 'bg-rose-50/50 border-rose-100/60'">
                                <span class="text-[10px] font-bold uppercase block mb-1" :class="isWeightValid() ? 'text-emerald-800' : 'text-rose-800'">Total Alokasi Bobot</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black" :class="isWeightValid() ? 'text-emerald-900' : 'text-rose-955'" x-text="totalWeight + '%'"></span>
                                    <span class="text-xs text-slate-550">/ Wajib 100%</span>
                                </div>
                                <span class="text-[10px] font-semibold block mt-1 flex items-center gap-1" :class="isWeightValid() ? 'text-emerald-700' : 'text-rose-750'"><template x-if="isWeightValid()"><svg class="w-3.5 h-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template><template x-if="!isWeightValid()"><svg class="w-3.5 h-3.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg></template><span x-text="isWeightValid() ? 'Alokasi bobot sesuai' : 'Jumlah total bobot harus pas 100%'"></span></span>
                            </div>
                        </div>

                        {{-- Alert Violations --}}
                        <div x-show="!isWeightValid()" class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs font-semibold animate-fade-in flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            <div>Konfigurasi bobot tidak valid. Jumlah total bobot kriteria aktif harus tepat 100%. <br>
                            <span class="block mt-1 font-normal text-[11px] text-rose-600">
                                * Jika memiliki kriteria Wajib DAN Tambahan: Core Factor maks 60%, Secondary Factor maks 40%. <br>
                                * Jika diset Wajib semua: Core Factor harus 100%. <br>
                                * Jika diset Tambahan semua: Secondary Factor harus 100%.
                            </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Informasi Pendukung (Lokasi, Shift, Gaji) -->
                <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-200/60 space-y-5">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200/55 pb-2">Informasi Operasional & Finansial</h4>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Batas Waktu -->
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-600">Lowongan Aktif Sampai</label>
                                <input type="date" name="active_until" value="{{ old('active_until', $posting->active_until ? $posting->active_until->format('Y-m-d') : '') }}"
                                    class="w-full mt-2 px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500 bg-white" required>
                            </div>
                        </div>

                        <!-- Shift & Finansial -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Jenis Shift <span class="text-rose-500 font-bold" x-show="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category)">*</span></label>
                                    <select name="shift_type" class="w-full mt-2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500 transition-all bg-white" :required="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category)">
                                        <option value="" @selected(old('shift_type', $posting->shift_type) === null || old('shift_type', $posting->shift_type) === '')>-- Pilih Jenis Shift --</option>
                                        <option value="none" @selected(old('shift_type', $posting->shift_type) === 'none')>Tidak ada</option>
                                        <option value="shift" @selected(old('shift_type', $posting->shift_type) === 'shift')>Menggunakan Shift</option>
                                        <option value="non_shift" @selected(old('shift_type', $posting->shift_type) === 'non_shift')>Non-Shift (Reguler)</option>
                                    </select>
                                    @error('shift_type')
                                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Visibilitas Gaji</label>
                                    <label class="mt-3.5 flex items-center gap-2 text-xs text-slate-600 cursor-pointer select-none">
                                        <input type="checkbox" name="salary_hidden" value="1" class="rounded border-slate-300"
                                            x-model="salaryHidden">
                                        Sembunyikan Rentang Gaji
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 transition-all" :class="salaryHidden ? 'opacity-40 pointer-events-none' : ''">
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Gaji Minimum (Rupiah) <span class="text-rose-500 font-bold" x-show="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden">*</span></label>
                                    <input type="number" name="salary_min" min="0" value="{{ old('salary_min', $posting->salary_min) }}"
                                        :disabled="salaryHidden"
                                        :required="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden"
                                        class="w-full mt-2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm placeholder-slate-350 bg-white"
                                        placeholder="Contoh: 4000000">
                                    @error('salary_min')
                                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-600">Gaji Maksimum (Rupiah) <span class="text-rose-500 font-bold" x-show="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden">*</span></label>
                                    <input type="number" name="salary_max" min="0" value="{{ old('salary_max', $posting->salary_max) }}"
                                        :disabled="salaryHidden"
                                        :required="['Driver Ambulance', 'Asisten Keperawatan', 'Cleaning Service', 'Runner'].includes(category) && !salaryHidden"
                                        class="w-full mt-2 px-3 py-2.5 rounded-xl border border-slate-200 text-sm placeholder-slate-350 bg-white"
                                        placeholder="Contoh: 6000000">
                                    @error('salary_max')
                                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="is_active" value="1">
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" 
                        :disabled="!isWeightValid()"
                        class="px-8 py-3 rounded-2xl bg-[#003d7c] disabled:bg-slate-300 disabled:cursor-not-allowed text-white text-sm font-bold shadow-md hover:shadow-lg disabled:shadow-none hover:brightness-105 active:scale-95 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to populate province & city
            function initLocationDropdowns() {
                const locationProvince = document.getElementById('location-province');
                const locationCity = document.getElementById('location-city');

                if (locationProvince && locationCity && locationProvince.children.length <= 1) {
                    fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
                        .then((response) => response.json())
                        .then((provinces) => {
                            provinces.forEach((province) => {
                                const option = document.createElement('option');
                                option.value = province.id;
                                option.textContent = province.name;
                                locationProvince.appendChild(option);
                            });
                        });

                    locationProvince.addEventListener('change', function () {
                        const provinceId = this.value;
                        locationCity.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                        if (!provinceId) return;

                        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                            .then((response) => response.json())
                            .then((cities) => {
                                cities.forEach((city) => {
                                    const option = document.createElement('option');
                                    option.value = city.name;
                                    option.textContent = city.name;
                                    locationCity.appendChild(option);
                                });
                            });
                    });
                }
            }

            // Polling in case Alpine has re-rendered the DOM
            setInterval(initLocationDropdowns, 1000);
        });
    </script>
@endsection
