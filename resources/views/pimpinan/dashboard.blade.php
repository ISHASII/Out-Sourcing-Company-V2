@extends('layouts.dashboard')

@section('dashboard-title', 'Panel Pimpinan')

@section('dashboard-content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6 animate-fade-in">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dashboard Pimpinan</h3>
            <p class="text-sm text-slate-500 mt-1">Ikhtisar ringkas metrik utama dan grafik aktivitas pendaftaran.</p>
        </div>
    </div>

    {{-- Minimalist Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Admin -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Admin</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-black text-slate-700">{{ $totalAdmin }}</p>
                <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
        
        <!-- Laporan Admin -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Laporan SPK</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-black text-[#003d7c]">{{ $totalLaporan }}</p>
                <div class="w-8 h-8 rounded-full bg-blue-50 text-[#003d7c] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        
        <!-- Lowongan Dibuat -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Loker Dibuat</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-black text-emerald-600">{{ $totalLoker }}</p>
                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
        
        <!-- Mitra -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Daftar Mitra</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-black text-amber-500">{{ $totalMitra }}</p>
                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Pelamar -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Pelamar</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-black text-rose-500">{{ $totalPelamar }}</p>
                <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive Line Charts Section --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6" x-data="activityChartComponent()">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Tren Pendaftaran & Lamaran</h3>
                <p class="text-xs text-slate-500 mt-1">Pantau perkembangan pengguna baru dan aplikasi lowongan.</p>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200/50">
                    <button @click="chartFilter = 'all'" :class="chartFilter === 'all' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all uppercase tracking-wide">Semua</button>
                    <button @click="chartFilter = 'registrations'" :class="chartFilter === 'registrations' ? 'bg-[#005fb8] text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all uppercase tracking-wide">Registrasi</button>
                    <button @click="chartFilter = 'applications'" :class="chartFilter === 'applications' ? 'bg-[#10b981] text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all uppercase tracking-wide">Lamaran</button>
                </div>
                
                <select x-model="timeFilter" class="px-3 py-2 bg-slate-50/70 border border-slate-200 focus:border-blue-500 rounded-xl text-xs font-bold text-slate-700 transition-all outline-none cursor-pointer appearance-none pr-8" style="background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%252394a3b8%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 0.85rem;">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="14">14 Hari Terakhir</option>
                    <option value="30">30 Hari Terakhir</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-4">
            <template x-if="chartFilter === 'all' || chartFilter === 'registrations'">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#005fb8]"></span>
                    <span>Registrasi User</span>
                </div>
            </template>
            <template x-if="chartFilter === 'all' || chartFilter === 'applications'">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                    <span>Lamaran Masuk</span>
                </div>
            </template>
        </div>

        <div class="relative h-64 w-full">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    {{-- Admin Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-800">Daftar Akun HRD & Admin</h4>
        </div>

        @if($admins->isEmpty())
        <div class="py-16 text-center">
            <svg class="w-14 h-14 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-slate-400 text-sm font-semibold">Belum ada akun yang terdaftar.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50/70 text-slate-500 text-[10px] uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold">#</th>
                        <th class="px-6 py-3 text-left font-bold">Nama & Email</th>
                        <th class="px-6 py-3 text-left font-bold">Role</th>
                        <th class="px-6 py-3 text-left font-bold">Status</th>
                        <th class="px-6 py-3 text-left font-bold">Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($admins as $i => $admin)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-3 text-slate-400 font-semibold text-xs">{{ $i + 1 }}</td>
                        <td class="px-6 py-3">
                            <div class="font-bold text-slate-800 text-xs">{{ $admin->name }}</div>
                            <div class="text-xs text-slate-400">{{ $admin->email }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-widest border border-slate-200">
                                {{ $admin->role }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            @if($admin->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-widest border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-widest border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-slate-500 font-medium text-xs">
                            {{ $admin->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
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
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    titleFont: { size: 11, weight: 'bold' },
                                    bodyFont: { size: 12 },
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: true,
                                }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: '#94a3b8' } },
                                y: { grid: { color: '#f1f5f9' }, ticks: { precision: 0, font: { size: 10, weight: '600' }, color: '#94a3b8' }, min: 0 }
                            },
                            interaction: { intersect: false, mode: 'index' }
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
                    
                    const blueGradient = ctx.createLinearGradient(0, 0, 0, 300);
                    blueGradient.addColorStop(0, 'rgba(0, 95, 184, 0.3)');
                    blueGradient.addColorStop(1, 'rgba(0, 95, 184, 0)');
                    
                    const greenGradient = ctx.createLinearGradient(0, 0, 0, 300);
                    greenGradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
                    greenGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

                    if (this.chartFilter === 'all' || this.chartFilter === 'registrations') {
                        datasets.push({
                            label: 'Registrasi User',
                            data: regData,
                            borderColor: '#005fb8',
                            backgroundColor: blueGradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#005fb8',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        });
                    }

                    if (this.chartFilter === 'all' || this.chartFilter === 'applications') {
                        datasets.push({
                            label: 'Lamaran Masuk',
                            data: appData,
                            borderColor: '#10b981',
                            backgroundColor: greenGradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 3,
                            pointHoverRadius: 5
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
@endsection
