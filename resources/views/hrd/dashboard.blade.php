@extends('layouts.dashboard')

@section('dashboard-title', 'Overview - HRD Dashboard')

@section('dashboard-content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-8 animate-fade-in" x-data="{ activeApplicant: null }">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#002855] to-[#004b93] text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="relative z-10 space-y-2">
            <span class="bg-blue-500/25 text-blue-200 text-[10px] font-extrabold px-3.5 py-1 rounded-lg uppercase tracking-widest border border-blue-400/20">HRD Portal</span>
            <h1 class="text-3xl font-extrabold tracking-tight">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
            <p class="text-blue-100/80 max-w-xl text-xs leading-relaxed">Kelola semua berkas lamaran pekerjaan, lowongan aktif, dan seleksi kandidat PT. Unggul Cipta Indah dengan cepat dan efisien.</p>
        </div>
        <div class="relative z-10 bg-white/15 p-5 rounded-2xl border border-white/20 text-center min-w-[160px]">
            <span class="block text-4xl font-extrabold text-white tracking-tight">{{ $activePostingsCount }}</span>
            <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block mt-1">Lowongan Aktif</span>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pelamar -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all duration-300">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Pelamar</span>
                <p class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalApplicantsCount }}</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100/30 flex items-center justify-center shrink-0" style="width: 48px; height: 48px;">
                <svg class="w-6 h-6 text-blue-600" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Perlu Review -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all duration-300">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Perlu Review</span>
                <p class="text-3xl font-black text-amber-600 tracking-tight">{{ $totalApplicantsCount }}</p>
            </div>
            <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl border border-amber-100/30 flex items-center justify-center shrink-0" style="width: 48px; height: 48px;">
                <svg class="w-6 h-6 text-amber-600" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Pelamar Prioritas -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all duration-300">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Pelamar Prioritas</span>
                <p class="text-3xl font-black text-emerald-600 tracking-tight">{{ $priorityCount }}</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100/30 flex items-center justify-center shrink-0" style="width: 48px; height: 48px;">
                <svg class="w-6 h-6 text-emerald-600" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Pelamar Non-Prioritas -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between hover:shadow-md transition-all duration-300">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Pelamar Non-Prioritas</span>
                <p class="text-3xl font-black text-slate-500 tracking-tight">{{ $nonPriorityCount }}</p>
            </div>
            <div class="p-3 bg-slate-100 text-slate-650 rounded-2xl border border-slate-200/50 flex items-center justify-center shrink-0" style="width: 48px; height: 48px;">
                <svg class="w-6 h-6 text-slate-500" style="width: 24px; height: 24px; min-width: 24px; min-height: 24px; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Interactive Line Charts Section -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8 space-y-6" 
         x-data="activityChartComponent()">
         
        <!-- Chart Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Analisis Aktivitas Harian</h3>
                <p class="text-xs text-slate-500 mt-1">Tren pendaftaran akun dan lamaran pekerjaan yang diajukan perhari.</p>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap">
                <!-- Chart Type Selector -->
                <div class="flex bg-slate-100 p-1 rounded-2xl border border-slate-200/50">
                    <button @click="chartFilter = 'all'" 
                            :class="chartFilter === 'all' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-550 hover:text-slate-800'"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all uppercase tracking-wide">
                        Semua
                    </button>
                    <button @click="chartFilter = 'registrations'" 
                            :class="chartFilter === 'registrations' ? 'bg-[#005fb8] text-white shadow-sm' : 'text-slate-550 hover:text-slate-800'"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all uppercase tracking-wide">
                        Registrasi
                    </button>
                    <button @click="chartFilter = 'applications'" 
                            :class="chartFilter === 'applications' ? 'bg-[#10b981] text-white shadow-sm' : 'text-slate-550 hover:text-slate-800'"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all uppercase tracking-wide">
                        Lamaran
                    </button>
                </div>

                <!-- Timeframe Selector -->
                <select x-model="timeFilter" 
                        class="px-3 py-2 bg-slate-50/70 border border-slate-200 focus:border-blue-500 rounded-2xl text-xs font-bold text-slate-700 transition-all outline-none cursor-pointer appearance-none pr-8"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%252394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 0.85rem;">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="14">14 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                </select>
            </div>
        </div>

        <!-- Legend indicators -->
        <div class="flex items-center gap-4 text-xs font-semibold text-slate-500">
            <template x-if="chartFilter === 'all' || chartFilter === 'registrations'">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#005fb8]"></span>
                    <span>Registrasi User</span>
                </div>
            </template>
            <template x-if="chartFilter === 'all' || chartFilter === 'applications'">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#10b981]"></span>
                    <span>Lamaran Masuk</span>
                </div>
            </template>
        </div>

        <!-- Chart Canvas -->
        <div class="relative h-80 w-full">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        if (!window.activityChartRegistered) {
            window.activityChartRegistered = true;
            Alpine.data('activityChartComponent', () => ({
                chartFilter: 'all',
                timeFilter: '30',
                chartInstance: null,
                chartData: @json($chartData),
                init() {
                    if (typeof Chart !== 'undefined') {
                        this.initChart();
                    } else {
                        const interval = setInterval(() => {
                            if (typeof Chart !== 'undefined') {
                                clearInterval(interval);
                                this.initChart();
                            }
                        }, 100);
                    }
                    this.$watch('chartFilter', value => this.updateChart());
                    this.$watch('timeFilter', value => this.updateChart());
                },
                initChart() {
                    const ctx = document.getElementById('activityChart').getContext('2d');
                    
                    const config = {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: []
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    titleFont: { size: 11, weight: 'bold' },
                                    bodyFont: { size: 12 },
                                    padding: 12,
                                    cornerRadius: 12,
                                    displayColors: true,
                                    callbacks: {
                                        label: function(context) {
                                            return ' ' + context.dataset.label + ': ' + context.parsed.y + ' aktivitas';
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: { size: 10, weight: '600' },
                                        color: '#94a3b8'
                                    }
                                },
                                y: {
                                    grid: {
                                        color: '#f1f5f9'
                                    },
                                    ticks: {
                                        precision: 0,
                                        font: { size: 10, weight: '600' },
                                        color: '#94a3b8'
                                    },
                                    min: 0
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            }
                        }
                    };

                    this.chartInstance = new Chart(ctx, config);
                    this.updateChart();
                },
                updateChart() {
                    if (!this.chartInstance) return;

                    const limit = parseInt(this.timeFilter);
                    const filteredData = this.chartData.slice(-limit);

                    const labels = filteredData.map(item => item.label);
                    const regData = filteredData.map(item => item.registrations);
                    const appData = filteredData.map(item => item.applications);

                    const datasets = [];
                    const ctx = document.getElementById('activityChart').getContext('2d');
                    
                    // Blue line gradient
                    const blueGradient = ctx.createLinearGradient(0, 0, 0, 300);
                    blueGradient.addColorStop(0, 'rgba(0, 95, 184, 0.35)');
                    blueGradient.addColorStop(1, 'rgba(0, 95, 184, 0.01)');
                    
                    // Green line gradient
                    const greenGradient = ctx.createLinearGradient(0, 0, 0, 300);
                    greenGradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
                    greenGradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

                    if (this.chartFilter === 'all' || this.chartFilter === 'registrations') {
                        datasets.push({
                            label: 'Registrasi User',
                            data: regData,
                            borderColor: '#005fb8',
                            borderWidth: 3,
                            backgroundColor: blueGradient,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#005fb8',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        });
                    }

                    if (this.chartFilter === 'all' || this.chartFilter === 'applications') {
                        datasets.push({
                            label: 'Lamaran Masuk',
                            data: appData,
                            borderColor: '#10b981',
                            borderWidth: 3,
                            backgroundColor: greenGradient,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        });
                    }

                    this.chartInstance.data.labels = labels;
                    this.chartInstance.data.datasets = datasets;
                    this.chartInstance.update();
                }
            }));
        }
    });
    </script>



    <!-- Reusable Detailed Applicant Modal (Lightbox-style) -->
    <div x-show="activeApplicant !== null" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" @click="activeApplicant = null"></div>

        <!-- Modal Content Container -->
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-4xl transform rounded-3xl bg-white p-6 md:p-8 text-left shadow-2xl transition-all border border-slate-100 overflow-hidden"
                 x-show="activeApplicant !== null"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <!-- Top color stripe -->
                <div class="absolute top-0 left-0 right-0 h-1.5"
                     :class="activeApplicant && activeApplicant.is_priority ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gradient-to-r from-slate-400 to-slate-500'"></div>

                <!-- Header -->
                <div class="flex items-start justify-between border-b border-slate-100 pb-4 mb-6">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span x-text="activeApplicant && activeApplicant.is_priority ? 'Pelamar Prioritas' : 'Pelamar Non-Prioritas'" 
                                  class="text-[10px] font-extrabold uppercase tracking-widest px-2.5 py-1 rounded-lg"
                                  :class="activeApplicant && activeApplicant.is_priority ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-700 border border-slate-200'"></span>
                            
                            <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100/50"
                                  x-text="activeApplicant ? activeApplicant.matching_score + '% Match' : ''"></span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 tracking-tight mt-2" x-text="activeApplicant ? activeApplicant.name : ''"></h3>
                        <p class="text-xs text-slate-500 mt-1" x-text="activeApplicant ? activeApplicant.email : ''"></p>
                    </div>
                    <button @click="activeApplicant = null" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-50 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[60vh] overflow-y-auto pr-1">
                    
                    <!-- Left Panel: Profile & Contact -->
                    <div class="space-y-6">
                        <!-- Basic Profile -->
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Informasi Pribadi & Kontak</h4>
                            <div class="grid grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                                <div>
                                    <span class="text-slate-400 block mb-0.5">Jenis Kelamin</span>
                                    <strong class="text-slate-700 font-bold" x-text="activeApplicant ? activeApplicant.gender : '-'"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block mb-0.5">Usia</span>
                                    <strong class="text-slate-700 font-bold" x-text="activeApplicant ? activeApplicant.age + ' Tahun' : '-'"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block mb-0.5">Tempat Lahir</span>
                                    <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.birth_place ? activeApplicant.birth_place : '-'"></strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block mb-0.5">Tanggal Lahir</span>
                                    <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.birth_date ? activeApplicant.birth_date : '-'"></strong>
                                </div>
                                <div class="col-span-2 border-t border-slate-100 pt-3">
                                    <span class="text-slate-400 block mb-0.5">Nomor Telepon</span>
                                    <strong class="text-slate-700 font-bold text-sm" x-text="activeApplicant ? activeApplicant.phone : '-'"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Domicile -->
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Alamat Domisili</h4>
                            <div class="text-xs space-y-2.5">
                                <div>
                                    <span class="text-slate-400 block mb-0.5">Alamat Lengkap</span>
                                    <strong class="text-slate-700 font-bold" x-text="activeApplicant ? activeApplicant.address : '-'"></strong>
                                </div>
                                <div class="grid grid-cols-2 gap-4 border-t border-slate-100 pt-2.5">
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Kota / Kabupaten</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.city ? activeApplicant.city : '-'"></strong>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 block mb-0.5">Provinsi</span>
                                        <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.province ? activeApplicant.province : '-'"></strong>
                                    </div>
                                </div>
                                <div class="border-t border-slate-100 pt-2.5">
                                    <span class="text-slate-400 block mb-0.5">Kode Pos</span>
                                    <strong class="text-slate-700 font-bold" x-text="activeApplicant && activeApplicant.postal_code ? activeApplicant.postal_code : '-'"></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel: Qualifications & Files -->
                    <div class="space-y-6">
                        <!-- Kualifikasi SPK -->
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Kualifikasi Utama</h4>
                            <div class="grid grid-cols-2 gap-y-3.5 gap-x-4 text-xs">
                                <div class="col-span-2">
                                    <span class="text-slate-400 block mb-0.5">Pendidikan Terakhir</span>
                                    <strong class="text-slate-700 font-bold text-[13px]" x-text="activeApplicant ? activeApplicant.education_level : '-'"></strong>
                                </div>
                                <div class="col-span-2 border-t border-slate-100 pt-3">
                                    <span class="text-slate-400 block mb-0.5">Kesiapan Penempatan Kerja</span>
                                    <strong class="font-bold text-[13px]" 
                                            :class="activeApplicant && activeApplicant.placement_ready === 'Siap' ? 'text-emerald-600' : 'text-rose-600'"
                                            x-text="activeApplicant ? activeApplicant.placement_ready : '-'"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Document Links -->
                        <div class="bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Dokumen Pendukung</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- CV -->
                                <template x-if="activeApplicant && activeApplicant.cv_path">
                                    <a :href="activeApplicant.cv_path" target="_blank"
                                       class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                        <div class="p-2 bg-rose-50 text-rose-600 rounded-lg group-hover:bg-rose-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Curriculum Vitae</span>
                                            <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat CV</span>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="!activeApplicant || !activeApplicant.cv_path">
                                    <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                        <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Curriculum Vitae</span>
                                            <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                        </div>
                                    </div>
                                </template>

                                <!-- Foto -->
                                <template x-if="activeApplicant && activeApplicant.photo_path">
                                    <a :href="activeApplicant.photo_path" target="_blank"
                                       class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Foto Profil</span>
                                            <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Foto</span>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="!activeApplicant || !activeApplicant.photo_path">
                                    <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                        <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Foto Profil</span>
                                            <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                        </div>
                                    </div>
                                </template>

                                <!-- AGD Certificate -->
                                <template x-if="activeApplicant && activeApplicant.agd_path">
                                    <a :href="activeApplicant.agd_path" target="_blank"
                                       class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Sertifikat AGD</span>
                                            <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="!activeApplicant || !activeApplicant.agd_path">
                                    <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                        <div class="p-2 bg-slate-250 text-slate-400 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Sertifikat AGD</span>
                                            <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                        </div>
                                    </div>
                                </template>

                                <!-- SIM C -->
                                <template x-if="activeApplicant && activeApplicant.sim_c_path">
                                    <a :href="activeApplicant.sim_c_path" target="_blank"
                                       class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM C</span>
                                            <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="!activeApplicant || !activeApplicant.sim_c_path">
                                    <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                        <div class="p-2 bg-slate-250 text-slate-400 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM C</span>
                                            <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                        </div>
                                    </div>
                                </template>

                                <!-- SIM B1 -->
                                <template x-if="activeApplicant && activeApplicant.sim_b1_path">
                                    <a :href="activeApplicant.sim_b1_path" target="_blank"
                                       class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                        <div class="p-2 bg-teal-50 text-teal-600 rounded-lg group-hover:bg-teal-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM B1</span>
                                            <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="!activeApplicant || !activeApplicant.sim_b1_path">
                                    <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                        <div class="p-2 bg-slate-250 text-slate-400 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                        </div>
                                        <div class="text-left">
                                            <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM B1</span>
                                            <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Full Width Bottom Section: Work Experiences -->
                    <div class="col-span-1 md:col-span-2 border-t border-slate-100 pt-6">
                        <h4 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-4">Riwayat Pengalaman Kerja</h4>
                        
                        <!-- Loop experiences -->
                        <div class="space-y-4">
                            <template x-if="activeApplicant && activeApplicant.experiences && activeApplicant.experiences.length > 0">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="(exp, index) in activeApplicant.experiences" :key="index">
                                        <div class="p-4 bg-slate-50/75 border border-slate-100 rounded-2xl relative overflow-hidden transition-all hover:bg-slate-50">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wide" x-text="exp.position"></h5>
                                                    <p class="text-[11px] text-slate-500 font-bold mt-0.5" x-text="exp.company"></p>
                                                </div>
                                                <span class="shrink-0 text-[9px] font-extrabold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-100/50" 
                                                      x-text="exp.duration"></span >
                                            </div>
                                            <div class="mt-2.5 text-xs text-slate-605 leading-relaxed border-t border-slate-200/50 pt-2"
                                                 x-text="exp.description"></div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!activeApplicant || !activeApplicant.experiences || activeApplicant.experiences.length === 0">
                                <div class="text-center py-8 bg-slate-50/50 rounded-2xl border border-slate-100/50 text-slate-400 font-semibold text-xs">
                                    Belum ada riwayat pengalaman kerja yang dicantumkan.
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                    <button @click="activeApplicant = null"
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all border border-slate-200/50">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
