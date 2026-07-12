@extends('layouts.dashboard')

@section('dashboard-title', 'Cari Lowongan')

@section('dashboard-content')
    @php
        $mappedPostings = $postings->map(function($p) use ($appliedJobIds) {
            $config = $p->requirements_config ?? [];
            
            $gender = $config['gender'] ?? ['status' => 'core', 'value' => $p->core_gender];
            $age = $config['age'] ?? ['status' => 'core', 'min' => $p->core_min_age, 'max' => $p->core_max_age];
            $education = $config['education'] ?? ['status' => 'core', 'value' => $p->core_min_education];
            $agd = $config['agd'] ?? ['status' => $p->core_requires_agd ? 'core' : 'secondary'];
            $simc = $config['sim_c'] ?? ['status' => $p->core_requires_sim_c ? 'core' : 'secondary'];
            $simb1 = $config['sim_b1'] ?? ['status' => $p->core_requires_sim_b1 ? 'core' : 'secondary'];
            $experience = $config['experience'] ?? ['status' => 'secondary', 'value' => $p->second_min_experience];
            $placement = $config['placement_ready'] ?? ['status' => 'core'];

            return [
                'id' => $p->id,
                'title' => $p->title,
                'category' => $p->category,
                'location_city' => $p->location_city ?? 'Seluruh Area (Bebas)',
                'shift_type' => $p->shift_type,
                'active_until' => $p->active_until ? $p->active_until->format('d M Y') : null,
                'salary_min' => $p->salary_min,
                'salary_max' => $p->salary_max,
                'salary_hidden' => (bool)$p->salary_hidden,
                'apply_url' => route('pelamar.lowongan.apply', $p),
                'has_applied' => in_array($p->id, $appliedJobIds),
                'requirements' => [
                    'gender' => [
                        'value' => $gender['value'] ?? 'male',
                        'status' => $gender['status'] ?? 'core'
                    ],
                    'age' => [
                        'min' => $age['min'] ?? 18,
                        'max' => $age['max'] ?? 65,
                        'status' => $age['status'] ?? 'core'
                    ],
                    'education' => [
                        'value' => $education['value'] ?? 'SMA/SMK',
                        'status' => $education['status'] ?? 'core'
                    ],
                    'agd' => ['status' => $agd['status'] ?? 'nonaktif'],
                    'sim_c' => ['status' => $simc['status'] ?? 'nonaktif'],
                    'sim_b1' => ['status' => $simb1['status'] ?? 'nonaktif'],
                    'experience' => [
                        'value' => $experience['value'] ?? 0,
                        'status' => $experience['status'] ?? 'secondary'
                    ],
                    'placement' => ['status' => $placement['status'] ?? 'core']
                ]
            ];
        });
    @endphp

    <!-- Main Alpine Container -->
    <div x-data="jobBoard" class="space-y-6 pb-12 animate-fade-in">

        <!-- Status Sessions Alert -->
        @if(session('success'))
            <div class="px-5 py-3.5 rounded-2xl bg-emerald-50 text-emerald-700 text-sm font-semibold border border-emerald-100 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="px-5 py-3.5 rounded-2xl bg-rose-50 text-rose-700 text-sm font-semibold border border-rose-100 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Premium Hero Header -->
        <div class="relative bg-gradient-to-r from-[#003d7c] to-[#005fb8] rounded-3xl p-8 overflow-hidden shadow-lg border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(255,255,255,0.08),transparent_50%)]"></div>
            <div class="relative z-10 max-w-3xl">
                <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black text-blue-100 bg-white/10 backdrop-blur-md uppercase tracking-widest">Temukan Karir Impian</span>
                <h3 class="text-2xl md:text-3xl font-extrabold text-white mt-4 leading-tight">Lowongan Pekerjaan Tersedia</h3>
                <p class="text-xs md:text-sm text-blue-100/90 mt-2 leading-relaxed">Cari dan lamar lowongan pekerjaan yang sesuai dengan keahlian serta kriteria SPK Anda secara real-time.</p>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full border-[10px] border-white/5 opacity-20 pointer-events-none"></div>
        </div>

        <!-- Dynamic Search and Filters -->
        <div class="bg-white border border-slate-100 p-5 md:p-6 rounded-3xl shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row gap-3.5">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" x-model="search" placeholder="Cari posisi pekerjaan (contoh: Driver, Staff)..." class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/10 focus:border-[#003d7c] transition-all text-sm text-slate-700 bg-slate-50/50">
                </div>
                <!-- City Filter -->
                <div class="w-full md:w-64 relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </span>
                    <select x-model="selectedCity" class="w-full pl-11 pr-8 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#003d7c]/10 focus:border-[#003d7c] transition-all text-sm text-slate-700 bg-slate-50/50 appearance-none">
                        <option value="">Semua Lokasi</option>
                        <template x-for="city in uniqueCities" :key="city">
                            <option :value="city" x-text="city"></option>
                        </template>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" style="width: 16px; height: 16px;"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Pilih Kategori:</span>
                <button @click="selectedCategory = ''" :class="selectedCategory === '' ? 'bg-[#003d7c] text-white shadow-md shadow-blue-900/15' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" class="px-4 py-2 rounded-full text-xs font-bold transition-all border border-slate-200/50">
                    Semua
                </button>
                <template x-for="cat in uniqueCategories" :key="cat">
                    <button @click="selectedCategory = cat" :class="selectedCategory === cat ? 'bg-[#003d7c] text-white shadow-md shadow-blue-900/15' : 'bg-slate-50 hover:bg-slate-100 text-slate-600'" class="px-4 py-2 rounded-full text-xs font-bold transition-all border border-slate-200/50" x-text="cat">
                    </button>
                </template>
            </div>
        </div>

        <!-- Empty State (When no postings at all) -->
        <template x-if="postings.length === 0">
            <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center shadow-sm max-w-xl mx-auto space-y-4">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto border border-slate-100 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4m16 4H4a2 2 0 01-2-2V7a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h4 class="text-base font-bold text-slate-800">Belum Ada Lowongan Aktif</h4>
                    <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto leading-relaxed">PT UCI belum mengunggah lowongan kerja baru untuk saat ini. Silakan periksa kembali di lain waktu.</p>
                </div>
            </div>
        </template>

        <!-- Main Job Board Grid (Full-width responsive grid) -->
        <template x-if="postings.length > 0">
            <div class="space-y-6">
                
                <!-- Search Results Count -->
                <div class="flex items-center justify-between px-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lowongan Terkait</span>
                    <span class="text-xs font-black text-blue-700 bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100" x-text="filteredPostings.length + ' posisi'"></span>
                </div>

                <!-- No Results Match Search -->
                <template x-if="filteredPostings.length === 0">
                    <div class="bg-white border border-slate-100 rounded-3xl p-12 text-center space-y-4 max-w-md mx-auto shadow-sm">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto text-slate-400 border border-slate-100 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold text-slate-700">Pencarian Tidak Ditemukan</h5>
                            <p class="text-xs text-slate-400 mt-1 max-w-[280px] mx-auto leading-relaxed">Coba sesuaikan kata kunci atau bersihkan filter untuk melihat semua lowongan kerja.</p>
                        </div>
                    </div>
                </template>

                <!-- Cards List (Horizontal Wide Cards, Vertically Stacked) -->
                <div class="flex flex-col gap-4">
                    <template x-for="posting in filteredPostings" :key="posting.id">
                        <div @click="window.location.href = posting.apply_url" 
                             :class="posting.has_applied ? 'border-emerald-200/80 bg-emerald-50/10 hover:border-emerald-300/80' : 'border-slate-200 bg-white hover:border-[#003d7c]/40'"
                             class="border hover:shadow-lg hover:-translate-y-0.5 p-6 md:py-5 md:px-8 rounded-[24px] cursor-pointer transition-all duration-300 relative overflow-hidden group shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                            
                            <!-- Left Border Accent on Hover -->
                            <div :class="posting.has_applied ? 'bg-gradient-to-b from-emerald-500 to-teal-600' : 'bg-gradient-to-b from-[#003d7c] to-[#005fb8]'"
                                 class="absolute left-0 top-0 bottom-0 w-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Left Section: Logo & Details -->
                            <div class="flex items-center gap-5 flex-1 min-w-0">
                                <!-- Initial Monogram Logo -->
                                <div :class="posting.has_applied ? 'bg-emerald-600' : 'bg-[#005fb8]'"
                                     class="w-14 h-14 rounded-2xl text-white flex items-center justify-center font-black text-lg shadow-sm shrink-0 uppercase tracking-widest">
                                    <span x-text="posting.title.substring(0, 2)"></span>
                                </div>
                                <div class="min-w-0 flex-1 space-y-2.5">
                                    <!-- Title, Category, and Location Badge in wrapping flex row -->
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5">
                                        <h4 class="text-lg font-bold text-slate-800 leading-snug group-hover:text-[#003d7c] transition-colors truncate" x-text="posting.title"></h4>
                                        <span class="text-xs text-slate-400 font-semibold">•</span>
                                        <p class="text-xs text-slate-500 font-semibold truncate" x-text="posting.category"></p>
                                        <span class="text-xs text-slate-400 font-semibold hidden sm:inline">•</span>
                                        <!-- Location Badge inline -->
                                        <span class="inline-flex items-center text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200/50 px-3 py-1 rounded-lg">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span x-text="posting.location_city"></span>
                                        </span>
                                    </div>
 
                                    <!-- Requirements Pills (Gender, Min. Education, Age, Shift) in second row -->
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <span class="inline-flex items-center text-[11px] font-bold px-3 py-1 rounded-lg bg-slate-50 border border-slate-200/50 text-slate-600">
                                            <span x-text="posting.requirements.gender.value === 'both' ? 'Pria & Wanita' : (posting.requirements.gender.value === 'male' ? 'Pria' : 'Wanita')"></span>
                                        </span>
                                        <span class="inline-flex items-center text-[11px] font-bold px-3 py-1 rounded-lg bg-slate-50 border border-slate-200/50 text-slate-600">
                                            <span x-text="'Min. ' + posting.requirements.education.value"></span>
                                        </span>
                                        <span class="inline-flex items-center text-[11px] font-bold px-3 py-1 rounded-lg bg-slate-50 border border-slate-200/50 text-slate-600">
                                            <span x-text="posting.requirements.age.min + '-' + posting.requirements.age.max + ' thn'"></span>
                                        </span>
                                        <template x-if="posting.shift_type && posting.shift_type !== 'none'">
                                            <span class="inline-flex items-center text-[11px] font-bold px-3 py-1 rounded-lg bg-slate-50 border border-slate-200/50 text-slate-600">
                                                <span x-text="posting.shift_type === 'shift' ? 'Shift' : 'Non-Shift'"></span>
                                            </span>
                                        </template>
                                    </div>
                                </div>
                            </div>
 
                            <!-- Right Section: Deadline & Direct Apply Button -->
                            <div class="flex items-center justify-between md:justify-end gap-5 shrink-0 border-t border-slate-100 md:border-t-0 pt-4 md:pt-0">
                                <span class="text-xs font-semibold text-slate-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Batas: <span class="text-slate-600 font-extrabold ml-1.5" x-text="posting.active_until"></span>
                                </span>
                                
                                <template x-if="posting.has_applied">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button @click.stop.prevent="window.location.href = '{{ route('pelamar.riwayat') }}'" 
                                                class="px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100/85 text-emerald-700 border border-emerald-200/60 text-[10px] font-black uppercase flex items-center gap-1.5 hover:shadow-sm transition-all select-none">
                                            Status: Sudah Melamar
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button @click.stop.prevent="window.location.href = posting.apply_url" 
                                                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#003d7c]/90 to-[#005fb8]/90 hover:from-[#003d7c] hover:to-[#005fb8] text-white text-[10px] font-black uppercase flex items-center gap-1 shadow-sm transition-all">
                                            Perbarui Lamaran
                                        </button>
                                    </div>
                                </template>
                                <template x-if="!posting.has_applied">
                                    <button class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#003d7c] to-[#005fb8] text-white text-xs font-black uppercase shadow-md group-hover:shadow-lg group-hover:brightness-105 transition-all flex items-center gap-1.5">
                                        Lamar Sekarang
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

            </div>
        </template>
    </div>

    <!-- Extra Styles for Custom Scrollbar -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(203, 213, 225, 0.6);
            border-radius: 9999px;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.8);
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('jobBoard', () => ({
                postings: @json($mappedPostings),
                search: '',
                selectedCategory: '',
                selectedCity: '',

                get uniqueCategories() {
                    return [...new Set(this.postings.map(p => p.category))];
                },

                get uniqueCities() {
                    return [...new Set(this.postings.map(p => p.location_city))].filter(c => c);
                },

                get filteredPostings() {
                    return this.postings.filter(p => {
                        const matchesSearch = p.title.toLowerCase().includes(this.search.toLowerCase()) || 
                                             p.category.toLowerCase().includes(this.search.toLowerCase());
                        const matchesCategory = !this.selectedCategory || p.category === this.selectedCategory;
                        const matchesCity = !this.selectedCity || p.location_city === this.selectedCity;
                        return matchesSearch && matchesCategory && matchesCity;
                    });
                }
            }));
        });
    </script>
@endsection
