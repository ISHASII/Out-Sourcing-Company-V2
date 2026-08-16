@extends('layouts.dashboard')

@section('dashboard-title', 'Pelamar Lowongan')

@section('dashboard-content')
    <div class="space-y-6 animate-fade-in" 
         x-data="{ 
            activeApplicant: null,
            activeSpkDetail: null,
            activeSpkName: '',
            spkDetailsMap: {{ json_encode($spkDetailsMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }},
            showSpkDetail(appId, name) {
                this.activeSpkDetail = this.spkDetailsMap[appId] || null;
                this.activeSpkName = name;
            },
            customDocsConfig: {{ json_encode(
                collect($posting->requirements_config['criteria'] ?? [])
                    ->filter(function($c) {
                        $predefined = [
                            'gender', 'age', 'education', 'major', 'experience', 
                            'agd', 'sertifikat_agd_ambulance', 'sertifikat_agd',
                            'sim_c', 'lisensi_sim_c_motor', 'sim_c_aktif',
                            'sim_b1', 'lisensi_sim_b1_mobil_berat',
                            'placement_ready', 'placement_choices',
                            'medical_support', 'medical_terms', 
                            'gardener_tech_understanding', 'gardener_nursery_skill', 'gardener_tools_skill'
                        ];
                        return ($c['status'] ?? 'nonaktif') !== 'nonaktif' && !in_array($c['key'], $predefined);
                    })
                    ->values()
                    ->toArray()
            ) }},
            priorityPage: 1,
            priorityPerPage: 5,
            totalPriority: {{ $priorityApplications->count() }},
            showPriorityRow(index) {
                const start = (this.priorityPage - 1) * this.priorityPerPage;
                const end = start + this.priorityPerPage;
                return index >= start && index < end;
            },
            
            nonPriorityPage: 1,
            nonPriorityPerPage: 5,
            totalNonPriority: {{ $nonPriorityApplications->count() }},
            showNonPriorityRow(index) {
                const start = (this.nonPriorityPage - 1) * this.nonPriorityPerPage;
                const end = start + this.nonPriorityPerPage;
                return index >= start && index < end;
            }
         }">
        <!-- Header Card -->
        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <!-- Decorative gradient banner top -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Manajemen Pelamar</span>
                    <h3 class="text-2xl font-bold text-slate-800 tracking-tight mt-2">{{ $posting->title }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Kategori Kerja: <strong class="text-slate-700 font-semibold">{{ $posting->category }}</strong> &bull; Mitra: <strong class="text-slate-700 font-semibold">{{ $posting->mitra_name }}</strong></p>
                </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('pimpinan.hiring') }}"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-100">
                        Kembali ke Daftar
                    </a>
                </div>

            <!-- Configuration Summary in Dashboard -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <details class="group cursor-pointer">
                    <summary class="flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-800 select-none">
                        <span class="transition-transform duration-200 group-open:rotate-90">▶</span>
                        Lihat Pengaturan Kriteria Kualifikasi SPK Lowongan Ini
                    </summary>
                    @php
                        $criteriaList = $posting->requirements_config['criteria'] ?? [];
                        $activeCriteria = collect($criteriaList)->filter(fn($c) => ($c['status'] ?? 'nonaktif') !== 'nonaktif' && ($c['weight'] ?? 0) > 0);
                        $coreCriteria = $activeCriteria->where('status', 'core');
                        $secondaryCriteria = $activeCriteria->where('status', 'secondary');
                        $totalCfWeight = $coreCriteria->sum('weight');
                        $totalSfWeight = $secondaryCriteria->sum('weight');
                    @endphp

                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                        @php
                            $getConfigLabel = function($status) {
                                if ($status === 'core') return '<span class="text-[9px] font-extrabold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100/50">Wajib (Core)</span>';
                                if ($status === 'secondary') return '<span class="text-[9px] font-extrabold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100/50">Nilai Tambah</span>';
                                return '<span class="text-[9px] font-semibold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100/50">Diabaikan</span>';
                            };
                        @endphp
                        
                        @forelse($activeCriteria as $c)
                            <div class="space-y-1">
                                <span class="text-[10px] text-slate-400 block font-bold uppercase truncate" title="{{ $c['label'] ?? ucwords(str_replace('_', ' ', $c['key'])) }}">{{ $c['label'] ?? ucwords(str_replace('_', ' ', $c['key'])) }}</span>
                                {!! $getConfigLabel($c['status']) !!}
                                <span class="text-[10px] text-slate-600 block mt-0.5 truncate">
                                    @if($c['key'] === 'gender')
                                        {{ ($c['value'] ?? 'male') === 'both' ? 'Pria & Wanita' : (($c['value'] ?? 'male') === 'male' ? 'Pria saja' : 'Wanita saja') }}
                                    @elseif($c['key'] === 'age')
                                        {{ $c['value']['min'] ?? 18 }} - {{ $c['value']['max'] ?? 65 }} tahun
                                    @elseif($c['key'] === 'education')
                                        Min. {{ $c['value'] ?? 'SMA/SMK' }}
                                    @elseif($c['key'] === 'experience')
                                        Min. {{ $c['value'] ?? 0 }} tahun
                                    @elseif($c['key'] === 'placement_ready')
                                        {{ ($c['value']['type'] ?? 'anywhere') === 'specific' ? ($c['value']['city'] ?? 'Kota tertentu') : 'Siap dimana saja' }}
                                    @else
                                        Bobot: {{ $c['weight'] }}%
                                    @endif
                                </span>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-xs text-slate-500 py-2">
                                Tidak ada kriteria khusus yang dikonfigurasi.
                            </div>
                        @endforelse
                    </div>

                    {{-- Section: Tabel Target Ideal --}}
                    @if($activeCriteria->isNotEmpty())
                    <div class="mt-6 pt-5 border-t border-slate-200/80">
                        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            Tabel Target Ideal
                        </h5>
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-xs">
                                <thead class="bg-rose-50/50 text-slate-600">
                                    <tr>
                                        <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">No</th>
                                        <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">Kriteria</th>
                                        <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">Nilai Standar Acuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($activeCriteria as $idx => $c)
                                        @php
                                            $standardText = '-';
                                            if ($c['key'] === 'gender') {
                                                $gLabels = ['male' => 'Pria', 'female' => 'Wanita', 'both' => 'Semua'];
                                                $standardText = $gLabels[$c['value'] ?? 'both'] ?? '-';
                                            } elseif ($c['key'] === 'age') {
                                                $standardText = ($c['value']['min'] ?? 18) . ' – ' . ($c['value']['max'] ?? 65) . ' tahun';
                                            } elseif ($c['key'] === 'education') {
                                                $standardText = 'Min. ' . ($c['value'] ?? 'SMA/SMK');
                                            } elseif ($c['key'] === 'experience') {
                                                $standardText = 'Min. ' . ($c['value'] ?? 0) . ' tahun';
                                            } elseif ($c['key'] === 'placement_ready') {
                                                $standardText = ($c['value']['type'] ?? 'anywhere') === 'specific' ? ($c['value']['city'] ?? 'Kota tertentu') : 'Siap dimana saja';
                                            } elseif ($c['key'] === 'major') {
                                                $standardText = $c['value'] ?? 'Semua jurusan';
                                            } elseif ($c['key'] === 'placement_choices') {
                                                $standardText = $c['value'] ?? 'Semua lokasi';
                                            } else {
                                                $standardText = 'Dokumen wajib upload';
                                            }
                                        @endphp
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-3 py-2 text-slate-500 font-semibold">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2 font-semibold text-slate-700">{{ $c['label'] ?? ucwords(str_replace('_', ' ', $c['key'])) }}</td>
                                            <td class="px-3 py-2 text-slate-600">{{ $standardText }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Section A: Tabel Pembobotan Kriteria --}}
                    @if($activeCriteria->isNotEmpty())
                    <div class="mt-6 pt-5 border-t border-slate-200/80">
                        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Tabel Pembobotan Kriteria Aktif
                        </h5>
                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="w-full text-xs">
                                <thead class="bg-slate-100 text-slate-500">
                                    <tr>
                                        <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">No</th>
                                        <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">Kriteria</th>
                                        <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">Tipe</th>
                                        <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">Bobot (%)</th>
                                        <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">Standar Acuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($activeCriteria as $idx => $c)
                                        @php
                                            $standardText = '-';
                                            if ($c['key'] === 'gender') {
                                                $gLabels = ['male' => 'Pria', 'female' => 'Wanita', 'both' => 'Semua'];
                                                $standardText = $gLabels[$c['value'] ?? 'both'] ?? '-';
                                            } elseif ($c['key'] === 'age') {
                                                $standardText = ($c['value']['min'] ?? 18) . ' – ' . ($c['value']['max'] ?? 65) . ' tahun';
                                            } elseif ($c['key'] === 'education') {
                                                $standardText = 'Min. ' . ($c['value'] ?? 'SMA/SMK');
                                            } elseif ($c['key'] === 'experience') {
                                                $standardText = 'Min. ' . ($c['value'] ?? 0) . ' tahun';
                                            } elseif ($c['key'] === 'placement_ready') {
                                                $standardText = ($c['value']['type'] ?? 'anywhere') === 'specific' ? ($c['value']['city'] ?? 'Kota tertentu') : 'Siap dimana saja';
                                            } elseif ($c['key'] === 'major') {
                                                $standardText = $c['value'] ?? 'Semua jurusan';
                                            } elseif ($c['key'] === 'placement_choices') {
                                                $standardText = $c['value'] ?? 'Semua lokasi';
                                            } else {
                                                $standardText = 'Dokumen wajib upload';
                                            }
                                        @endphp
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-3 py-2 text-slate-500 font-semibold">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2 font-semibold text-slate-700">{{ $c['label'] ?? ucwords(str_replace('_', ' ', $c['key'])) }}</td>
                                            <td class="px-3 py-2 text-center">
                                                @if($c['status'] === 'core')
                                                    <span class="text-[9px] font-extrabold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100/50">Core</span>
                                                @else
                                                    <span class="text-[9px] font-extrabold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100/50">Secondary</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-center font-bold text-slate-800">{{ $c['weight'] ?? 0 }}%</td>
                                            <td class="px-3 py-2 text-slate-600">{{ $standardText }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-slate-50 border-t border-slate-200">
                                    <tr>
                                        <td colspan="3" class="px-3 py-2 text-right font-bold text-slate-600 uppercase text-[10px] tracking-wider">Total Bobot</td>
                                        <td class="px-3 py-2 text-center font-extrabold text-slate-800">{{ $activeCriteria->sum('weight') }}%</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- Section B: Rumus SPK Profile Matching --}}
                    <div class="mt-6 pt-5 border-t border-slate-200/80">
                        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.871 4A17.926 17.926 0 003 12c0 2.874.673 5.59 1.871 8m14.13 0A17.926 17.926 0 0021 12a17.926 17.926 0 00-1.871-8M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 01.553-.894L9 2m0 18l6-3m-6 3V2m6 18l5.447-2.724A1 1 0 0021 16.382V5.618a1 1 0 00-.553-.894L15 2m0 18V2m0 0L9 5"/></svg>
                            Rumus SPK — Profile Matching
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 space-y-2.5">
                                <div class="text-[10px] font-bold text-blue-800 uppercase tracking-wider">Perhitungan GAP</div>
                                <div class="bg-white rounded-lg px-3 py-2 border border-blue-100/50 text-xs font-mono text-slate-700">
                                    GAP = Nilai Profil Pelamar − Nilai Profil Acuan
                                </div>
                            </div>
                            <div class="bg-emerald-50/60 border border-emerald-100 rounded-xl p-4 space-y-2.5">
                                <div class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Nilai Core Factor (NCF)</div>
                                <div class="bg-white rounded-lg px-3 py-2 border border-emerald-100/50 text-xs font-mono text-slate-700">
                                    NCF = Σ(Bobot Nilai Core) / Jumlah Kriteria Core
                                </div>
                                <div class="text-[10px] text-emerald-700 font-semibold">
                                    Jumlah Kriteria Core: {{ $coreCriteria->count() }} · Total Bobot: {{ $totalCfWeight }}%
                                </div>
                            </div>
                            <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-4 space-y-2.5">
                                <div class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">Nilai Secondary Factor (NSF)</div>
                                <div class="bg-white rounded-lg px-3 py-2 border border-amber-100/50 text-xs font-mono text-slate-700">
                                    NSF = Σ(Bobot Nilai Secondary) / Jumlah Kriteria Secondary
                                </div>
                                <div class="text-[10px] text-amber-700 font-semibold">
                                    Jumlah Kriteria Secondary: {{ $secondaryCriteria->count() }} · Total Bobot: {{ $totalSfWeight }}%
                                </div>
                            </div>
                            <div class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-4 space-y-2.5">
                                <div class="text-[10px] font-bold text-indigo-800 uppercase tracking-wider">Nilai Akhir</div>
                                <div class="bg-white rounded-lg px-3 py-2 border border-indigo-100/50 text-xs font-mono text-slate-700">
                                    Nilai Akhir = Σ(Bobot% × Bobot_Nilai) / 100
                                </div>
                                <div class="bg-white rounded-lg px-3 py-2 border border-indigo-100/50 text-xs font-mono text-slate-700">
                                    Skor (%) = ((Nilai Akhir − 1) / 4) × 100
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section C: Tabel Referensi Konversi GAP → Bobot Nilai --}}
                    <div class="mt-6 pt-5 border-t border-slate-200/80">
                        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Tabel Konversi GAP → Bobot Nilai <span class="text-[10px] font-normal text-slate-400 normal-case tracking-normal"></span>
                        </h5>
                        <div class="overflow-x-auto rounded-xl border border-slate-200 max-w-md">
                            <table class="w-full text-xs">
                                <thead class="bg-slate-100 text-slate-500">
                                    <tr>
                                        <th class="px-3 py-2 text-center font-bold uppercase tracking-wider">Selisih GAP</th>
                                        <th class="px-3 py-2 text-center font-bold uppercase tracking-wider">Bobot Nilai</th>
                                        <th class="px-3 py-2 text-left font-bold uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-center">
                                    <tr class="bg-emerald-50/30"><td class="px-3 py-1.5 font-bold text-slate-700">0</td><td class="px-3 py-1.5 font-extrabold text-emerald-700">5.0</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi sesuai</td></tr>
                                    <tr><td class="px-3 py-1.5 font-bold text-slate-700">1</td><td class="px-3 py-1.5 font-bold text-slate-700">4.5</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kelebihan 1 level</td></tr>
                                    <tr><td class="px-3 py-1.5 font-bold text-slate-700">-1</td><td class="px-3 py-1.5 font-bold text-slate-700">4.0</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kurang 1 level</td></tr>
                                    <tr><td class="px-3 py-1.5 font-bold text-slate-700">2</td><td class="px-3 py-1.5 font-bold text-slate-700">3.5</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kelebihan 2 level</td></tr>
                                    <tr><td class="px-3 py-1.5 font-bold text-slate-700">-2</td><td class="px-3 py-1.5 font-bold text-slate-700">3.0</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kurang 2 level</td></tr>
                                    <tr><td class="px-3 py-1.5 font-bold text-slate-700">3</td><td class="px-3 py-1.5 font-bold text-slate-700">2.5</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kelebihan 3 level</td></tr>
                                    <tr><td class="px-3 py-1.5 font-bold text-slate-700">-3</td><td class="px-3 py-1.5 font-bold text-slate-700">2.0</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kurang 3 level</td></tr>
                                    <tr><td class="px-3 py-1.5 font-bold text-slate-700">4</td><td class="px-3 py-1.5 font-bold text-slate-700">1.5</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kelebihan 4 level</td></tr>
                                    <tr class="bg-rose-50/30"><td class="px-3 py-1.5 font-bold text-slate-700">-4</td><td class="px-3 py-1.5 font-bold text-rose-600">1.0</td><td class="px-3 py-1.5 text-left text-slate-500">Kompetensi kurang 4 level</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </details>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="space-y-6">

            <!-- SPK Execution History & Action -->
            <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                            Status Profile Matching
                        </h4>
                        @if($posting->spk_status === 'pending')
                            <p class="text-xs text-amber-600 mt-1 font-semibold">Belum dieksekusi. Pelamar belum difilter berdasarkan skor SPK.</p>
                        @else
                            <p class="text-xs text-emerald-600 mt-1 font-semibold">Telah dieksekusi. Pelamar sudah difilter dan diurutkan.</p>
                        @endif
                        
                        @php
                            $logs = $posting->spk_execution_logs;
                            if (is_string($logs)) {
                                $logs = json_decode($logs, true) ?? [];
                            }
                        @endphp
                        @if(!empty($logs) && is_array($logs))
                            <div class="mt-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Log Histori Eksekusi:</span>
                                <ul class="mt-1 space-y-1">
                                    @foreach(array_reverse($logs) as $log)
                                        <li class="text-xs text-slate-600">
                                            <span class="font-mono text-indigo-600">{{ $log['executed_at'] ?? '-' }}</span> — {{ $log['applicant_count'] ?? 0 }} pelamar diproses
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Massive SPK Tables Summary -->
            @include('hrd.hiring.partials.spk_tables_summary')

            @if($posting->spk_status === 'pending')
                <!-- Unified Table for Pending -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden" x-data="{ page: 1, perPage: 10, total: {{ $allApplications->count() }} }">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-amber-500"></div>
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Daftar Semua Pelamar
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">Belum difilter SPK. Silakan eksekusi Profile Matching terlebih dahulu.</p>
                        </div>
                        <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-100/80 px-3 py-1 rounded-xl">
                            {{ $allApplications->count() }} Pelamar
                        </span>
                    </div>

                    @include('hrd.hiring.partials.applicant_table', ['applications' => $allApplications, 'isCompleted' => false, 'xDataPrefix' => '', 'isReadOnly' => true])

                </div>
            @else
                <!-- Completed SPK Tables -->
                <!-- Prioritas Table Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden" x-data="{ priorityPage: 1, priorityPerPage: 10, totalPriority: {{ $priorityApplications->count() }} }">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span> Pelamar Prioritas
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">Lulus semua kriteria wajib (Core Factor). Diurutkan berdasarkan skor SPK tertinggi.</p>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-100/80 px-3 py-1 rounded-xl">
                            {{ $priorityApplications->count() }} Pelamar
                        </span>
                    </div>

                    @include('hrd.hiring.partials.applicant_table', ['applications' => $priorityApplications, 'isCompleted' => true, 'xDataPrefix' => 'priority', 'isReadOnly' => true])

                </div>

                <!-- Non-Prioritas Table Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden" x-data="{ nonPriorityPage: 1, nonPriorityPerPage: 10, totalNonPriority: {{ $nonPriorityApplications->count() }} }">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-400 to-slate-500"></div>
                    
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Pelamar Non-Prioritas
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">Tidak memenuhi satu atau lebih kriteria wajib (Core Factor).</p>
                        </div>
                        <span class="text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200/80 px-3 py-1 rounded-xl">
                            {{ $nonPriorityApplications->count() }} Pelamar
                        </span>
                    </div>

                    @include('hrd.hiring.partials.applicant_table', ['applications' => $nonPriorityApplications, 'isCompleted' => true, 'xDataPrefix' => 'nonPriority', 'isReadOnly' => true])

                </div>
                <!-- Lolos Seleksi 1 Table Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden mt-6" x-data="{ lolos1Page: 1, lolos1PerPage: 10, totalLolos1: {{ $lolosSeleksi1Applications->count() }} }">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                    
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span> Lolos Seleksi 1
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">Kandidat yang telah lolos wawancara awal dan menunggu persetujuan akhir.</p>
                        </div>
                        <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200/80 px-3 py-1 rounded-xl">
                            {{ $lolosSeleksi1Applications->count() }} Pelamar
                        </span>
                    </div>

                    @include('hrd.hiring.partials.applicant_table', ['applications' => $lolosSeleksi1Applications, 'isCompleted' => true, 'xDataPrefix' => 'lolos1', 'isReadOnly' => true])

                </div>

                <!-- Lolos Wawancara Table Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden mt-6" x-data="{ validPage: 1, validPerPage: 10, totalValid: {{ $interviewPassedApplications->count() }} }">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-blue-500"></div>
                    
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span> Kandidat Lolos Wawancara
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">Kandidat yang telah diterima dan dinyatakan valid (lolos wawancara).</p>
                        </div>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100/80 px-3 py-1 rounded-xl">
                            {{ $interviewPassedApplications->count() }} Pelamar
                        </span>
                    </div>

                    @include('hrd.hiring.partials.applicant_table', ['applications' => $interviewPassedApplications, 'isCompleted' => true, 'xDataPrefix' => 'valid', 'isReadOnly' => true])

                </div>
            @endif

                <!-- Kandidat Ditolak Table Card -->
                <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden mt-6" x-data="{ rejectedPage: 1, rejectedPerPage: 10, totalRejected: {{ $rejectedApplications->count() }} }">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-500"></div>
                    
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span> Kandidat Ditolak
                            </h4>
                            <p class="text-xs text-slate-400 mt-1">Kandidat yang telah ditolak lamarannya oleh tim HRD.</p>
                        </div>
                        <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-100/80 px-3 py-1 rounded-xl">
                            {{ $rejectedApplications->count() }} Pelamar
                        </span>
                    </div>

                    @include('hrd.hiring.partials.applicant_table', ['applications' => $rejectedApplications, 'isCompleted' => $posting->spk_status === 'completed', 'xDataPrefix' => 'rejected'])

                </div>

        </div><!-- Reusable Detailed Applicant Modal (Lightbox-style) -->
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

                                <template x-if="activeApplicant && activeApplicant.status === 'accepted'">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Diterima
                                    </span>
                                </template>
                                <template x-if="activeApplicant && activeApplicant.status === 'rejected'">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-100">
                                        Ditolak
                                    </span>
                                </template>
                                <template x-if="activeApplicant && activeApplicant.status === 'pending'">
                                    <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-100">
                                        Pending
                                    </span>
                                </template>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 tracking-tight mt-2" x-text="activeApplicant ? activeApplicant.name : ''"></h3>
                            <p class="text-xs text-slate-500 mt-1" x-text="activeApplicant ? activeApplicant.email : ''"></p>
                        </div>
                        <button @click="activeApplicant = null" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                                    <div class="col-span-2 border-t border-slate-100 pt-3 flex items-center justify-between">
                                        <div>
                                            <span class="text-slate-400 block mb-0.5">Nomor Telepon</span>
                                            <strong class="text-slate-700 font-bold text-sm" x-text="activeApplicant ? activeApplicant.phone : '-'"></strong>
                                        </div>
                                        <template x-if="activeApplicant && activeApplicant.phone && activeApplicant.phone !== '-'">
                                            <a :href="'https://wa.me/' + (activeApplicant.phone.replace(/[^0-9]/g, '').startsWith('0') ? '62' + activeApplicant.phone.replace(/[^0-9]/g, '').substring(1) : activeApplicant.phone.replace(/[^0-9]/g, ''))"
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] uppercase rounded-xl transition-all border border-emerald-250 shadow-sm">
                                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12.004 2C6.48 2 2 6.48 2 12.004c0 1.91.53 3.69 1.45 5.22L2 22l4.95-1.42c1.47.81 3.16 1.27 4.95 1.27 5.52 0 10-4.48 10-10.004C21.9 6.48 17.52 2 12.004 2zm5.72 13.91c-.24.68-1.2 1.27-1.65 1.34-.45.07-.9-.05-2.88-.84-2.52-1.01-4.14-3.58-4.26-3.74-.12-.16-.96-1.28-.96-2.45 0-1.17.61-1.74.83-1.98.22-.24.47-.3.63-.3.16 0 .32 0 .46.01.15.01.35-.06.55.42.2.49.69 1.68.75 1.8.06.12.1.26.02.42-.08.17-.12.27-.24.42-.12.15-.26.33-.37.45-.12.13-.25.27-.11.51.14.24.63 1.04 1.35 1.68.93.82 1.7 1.08 1.94 1.2.24.12.38.1.52-.06.14-.16.61-.71.77-.96.16-.24.32-.2.54-.12.22.08 1.4.66 1.64.78.24.12.4.18.46.28.06.1.06.58-.18 1.26z"/>
                                                </svg>
                                                Hubungi via WA
                                            </a>
                                        </template>
                                    </div>
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Alamat Email</span>
                                        <strong class="text-slate-700 font-bold text-sm block" x-text="activeApplicant ? activeApplicant.email : '-'"></strong>
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
                                    @if(($config['major']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Jurusan Pendidikan</span>
                                        <strong class="text-slate-700 font-bold text-[13px]" x-text="activeApplicant && activeApplicant.major ? activeApplicant.major : '-'"></strong>
                                    </div>
                                    @endif
                                    @if(($config['placement_choices']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Pilihan Wilayah Penempatan</span>
                                        <strong class="text-indigo-650 font-bold text-[13px]" x-text="activeApplicant && activeApplicant.placement_choice ? activeApplicant.placement_choice : '-'"></strong>
                                    </div>
                                    @endif
                                    @if(($config['placement_ready']['status'] ?? 'core') !== 'nonaktif' || (($config['placement_ready']['status'] ?? 'core') === 'nonaktif' && ($config['placement_choices']['status'] ?? 'nonaktif') === 'nonaktif'))
                                    <div class="col-span-2 border-t border-slate-100 pt-3">
                                        <span class="text-slate-400 block mb-0.5">Kesiapan Penempatan Kerja</span>
                                        <strong class="font-bold text-[13px]" 
                                                :class="activeApplicant && activeApplicant.placement_ready === 'Siap' ? 'text-emerald-600' : 'text-rose-600'"
                                                x-text="activeApplicant ? activeApplicant.placement_ready : '-'"></strong>
                                    </div>
                                    @endif
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
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
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
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
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
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
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
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Foto Profil</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>

                                    @if(($config['agd']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <!-- AGD Certificate -->
                                    <template x-if="activeApplicant && activeApplicant.agd_path">
                                        <a :href="activeApplicant.agd_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Sertifikat AGD</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.agd_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Sertifikat AGD</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>
                                    @endif

                                    @if(($config['sim_c']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <!-- SIM C -->
                                    <template x-if="activeApplicant && activeApplicant.sim_c_path">
                                        <a :href="activeApplicant.sim_c_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM C</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.sim_c_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM C</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>
                                    @endif

                                    @if(($config['sim_b1']['status'] ?? 'nonaktif') !== 'nonaktif')
                                    <!-- SIM B1 -->
                                    <template x-if="activeApplicant && activeApplicant.sim_b1_path">
                                        <a :href="activeApplicant.sim_b1_path" target="_blank"
                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg group-hover:bg-teal-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM B1</span>
                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                            </div>
                                        </a>
                                    </template>
                                    <template x-if="!activeApplicant || !activeApplicant.sim_b1_path">
                                        <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                            <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                                            </div>
                                            <div class="text-left">
                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide">Dokumen SIM B1</span>
                                                <span class="text-xs font-bold text-slate-400">Tidak Diunggah</span>
                                            </div>
                                        </div>
                                    </template>
                                    @endif

                                    <!-- Custom / General Requirements dynamic loop -->
                                    <template x-for="(doc, idx) in customDocsConfig" :key="idx">
                                        <div class="col-span-full sm:col-span-1">
                                            <!-- If applicant provided the value -->
                                            <template x-if="activeApplicant && activeApplicant.additional_documents && activeApplicant.additional_documents[doc.key] !== undefined && activeApplicant.additional_documents[doc.key] !== null && activeApplicant.additional_documents[doc.key] !== ''">
                                                <div>
                                                    <!-- File Type: show View Link -->
                                                    <template x-if="doc.type === 'file'">
                                                        <a :href="activeApplicant.additional_documents[doc.key]" target="_blank"
                                                           class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 hover:border-blue-100 hover:bg-blue-50/50 rounded-xl transition-all group">
                                                            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-100 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            </div>
                                                            <div class="text-left">
                                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide" x-text="doc.label"></span>
                                                                <span class="text-xs font-bold text-slate-700 group-hover:text-blue-700">Lihat Berkas</span>
                                                            </div>
                                                        </a>
                                                    </template>
                                                    
                                                    <!-- Checkbox Type: show Checked State -->
                                                    <template x-if="doc.type === 'checkbox'">
                                                        <div class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 rounded-xl">
                                                            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            </div>
                                                            <div class="text-left">
                                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide" x-text="doc.label"></span>
                                                                <span class="text-xs font-bold text-emerald-700" x-text="activeApplicant.additional_documents[doc.key] ? 'Ya / Setuju' : 'Tidak / Tidak Setuju'"></span>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <!-- Text or Number Type: show Raw Value -->
                                                    <template x-if="doc.type === 'text' || doc.type === 'number'">
                                                        <div class="flex items-center gap-2.5 p-2.5 bg-white border border-slate-100 rounded-xl">
                                                            <div class="p-2 bg-blue-50 text-[#003d7c] rounded-lg">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                            </div>
                                                            <div class="text-left min-w-0 flex-1">
                                                                <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide truncate" x-text="doc.label"></span>
                                                                <span class="text-xs font-bold text-slate-700 block truncate" x-text="activeApplicant.additional_documents[doc.key]"></span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            
                                            <!-- If applicant DID NOT provide the value -->
                                            <template x-if="!activeApplicant || !activeApplicant.additional_documents || activeApplicant.additional_documents[doc.key] === undefined || activeApplicant.additional_documents[doc.key] === null || activeApplicant.additional_documents[doc.key] === ''">
                                                <div class="flex items-center gap-2.5 p-2.5 bg-slate-100/50 border border-slate-200/50 rounded-xl">
                                                    <div class="p-2 bg-slate-200 text-slate-400 rounded-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </div>
                                                    <div class="text-left">
                                                        <span class="text-[9px] text-slate-400 block font-extrabold uppercase tracking-wide" x-text="doc.label"></span>
                                                        <span class="text-xs font-bold text-slate-400">Tidak Diisi</span>
                                                    </div>
                                                </div>
                                            </template>
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
                                                <div class="mt-2.5 text-xs text-slate-600 leading-relaxed border-t border-slate-200/50 pt-2"
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

                    <!-- Hidden Forms for Modal Actions -->
                    <form id="modal-accept-form" :action="'/hrd/applications/' + (activeApplicant ? activeApplicant.id : '') + '/accept'" method="POST" class="hidden">
                        @csrf
                    </form>
                    <form id="modal-reject-form" :action="'/hrd/applications/' + (activeApplicant ? activeApplicant.id : '') + '/reject'" method="POST" class="hidden">
                        @csrf
                    </form>

                    <!-- Footer Buttons -->
                    <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <template x-if="activeApplicant && activeApplicant.status === 'pending'">
                                <div class="flex items-center gap-2">
                                    <button @click="$dispatch('open-confirm-modal', {
                                                title: 'Terima Pelamar',
                                                message: 'Apakah Anda yakin ingin menerima pelamar ' + activeApplicant.name + '?',
                                                confirmText: 'Ya, Terima',
                                                type: 'info',
                                                actionType: 'submit',
                                                formElement: document.getElementById('modal-accept-form')
                                            })"
                                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                        </svg>
                                        Terima
                                    </button>
                                    <button @click="$dispatch('open-confirm-modal', {
                                                title: 'Tolak Pelamar',
                                                message: 'Apakah Anda yakin ingin menolak pelamar ' + activeApplicant.name + '?',
                                                confirmText: 'Ya, Tolak',
                                                type: 'danger',
                                                actionType: 'submit',
                                                formElement: document.getElementById('modal-reject-form')
                                            })"
                                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </template>

                            <template x-if="activeApplicant && activeApplicant.status === 'accepted' && (!activeApplicant.interview_status || activeApplicant.interview_status === null)">
                                <div class="flex items-center gap-2">
                                    <form id="modal-valid-form" :action="'/hrd/applications/' + activeApplicant.id + '/mark-valid'" method="POST" class="hidden">@csrf</form>
                                    <form id="modal-invalid-form" :action="'/hrd/applications/' + activeApplicant.id + '/mark-invalid'" method="POST" class="hidden">@csrf</form>

                                    <button @click="$dispatch('open-confirm-modal', {
                                                title: 'Validasi Wawancara',
                                                message: 'Apakah pelamar ' + activeApplicant.name + ' LOLOS wawancara?',
                                                confirmText: 'Ya, Lolos',
                                                type: 'info',
                                                actionType: 'submit',
                                                formElement: document.getElementById('modal-valid-form')
                                            })"
                                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Wawancara Valid
                                    </button>
                                    <button @click="$dispatch('open-confirm-modal', {
                                                title: 'Validasi Wawancara',
                                                message: 'Apakah pelamar ' + activeApplicant.name + ' GAGAL wawancara?',
                                                confirmText: 'Ya, Gagal',
                                                type: 'danger',
                                                actionType: 'submit',
                                                formElement: document.getElementById('modal-invalid-form')
                                            })"
                                            class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Wawancara Tidak Valid
                                    </button>
                                </div>
                            </template>
                            
                            <a :href="'/hrd/applications/' + (activeApplicant ? activeApplicant.id : '') + '/pdf'" target="_blank"
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.615 0-1.101-.476-1.12-1.08L5.82 18m11.84 0h-11.84m12.48-5.323a1.947 1.947 0 00-2.38-1.947h-6.562a1.947 1.947 0 00-2.38 1.947m11.322 0A1.947 1.947 0 0119.5 13.5v3.11a1.947 1.947 0 01-1.84 1.947m-11.322 0A1.947 1.947 0 004.5 16.61v-3.11c0-.88.667-1.63 1.522-1.752z"></path>
                                </svg>
                                Cetak PDF SPK
                            </a>
                        </div>
                        
                        <button @click="activeApplicant = null"
                                class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all border border-slate-200/50">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SPK Detail Modal --}}
    <div x-show="activeSpkDetail" x-cloak
         class="fixed inset-0 z-[60] flex items-start justify-center overflow-y-auto py-8 px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-slate-900/60" @click="activeSpkDetail = null"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl border border-slate-200 overflow-hidden"
             @click.away="activeSpkDetail = null">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-[#002855] to-[#004b93] px-6 py-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold tracking-tight">Detail Perhitungan SPK</h3>
                        <p class="text-blue-200 text-xs mt-1">Pelamar: <strong class="text-white" x-text="activeSpkName"></strong></p>
                    </div>
                    <button @click="activeSpkDetail = null" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">

                {{-- Step 1: Tabel Perhitungan GAP --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-extrabold flex items-center justify-center">1</span>
                        Perhitungan GAP & Konversi Bobot Nilai
                    </h4>
                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-xs">
                            <thead class="bg-slate-100 text-slate-500">
                                <tr>
                                    <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">No</th>
                                    <th class="px-3 py-2.5 text-left font-bold uppercase tracking-wider">Kriteria</th>
                                    <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">Tipe</th>
                                    <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">Bobot (%)</th>
                                    <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">Standar</th>
                                    <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">Pelamar</th>
                                    <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">GAP</th>
                                    <th class="px-3 py-2.5 text-center font-bold uppercase tracking-wider">Bobot Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(item, idx) in (activeSpkDetail ? activeSpkDetail.criteria_details : [])" :key="idx">
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-3 py-2 text-slate-500 font-semibold" x-text="idx + 1"></td>
                                        <td class="px-3 py-2 font-semibold text-slate-700" x-text="item.label"></td>
                                        <td class="px-3 py-2 text-center">
                                            <span x-show="item.status === 'core'" class="text-[9px] font-extrabold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100/50">Core</span>
                                            <span x-show="item.status === 'secondary'" class="text-[9px] font-extrabold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100/50">Secondary</span>
                                        </td>
                                        <td class="px-3 py-2 text-center font-bold text-slate-700"><span x-text="item.weight + '%'"></span></td>
                                        <td class="px-3 py-2 text-center text-slate-600" x-text="item.standard_display"></td>
                                        <td class="px-3 py-2 text-center font-semibold" :class="item.is_match ? 'text-emerald-700' : 'text-rose-600'" x-text="item.applicant_display"></td>
                                        <td class="px-3 py-2 text-center font-bold" :class="item.gap === 0 ? 'text-emerald-700' : (item.gap > 0 ? 'text-blue-600' : 'text-rose-600')" x-text="item.gap"></td>
                                        <td class="px-3 py-2 text-center font-extrabold" :class="item.bobot_nilai >= 4 ? 'text-emerald-700' : (item.bobot_nilai >= 3 ? 'text-amber-600' : 'text-rose-600')" x-text="item.bobot_nilai.toFixed(1)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Step 2: Perhitungan NCF & NSF --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-emerald-600 text-white text-[10px] font-extrabold flex items-center justify-center">2</span>
                        Perhitungan NCF & NSF
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="bg-emerald-50/60 border border-emerald-100 rounded-xl p-4 space-y-2">
                            <div class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Nilai Core Factor (NCF)</div>
                            <div class="bg-white rounded-lg px-3 py-2 border border-emerald-100/50 space-y-1">
                                <div class="text-xs text-slate-600">
                                    NCF = Σ(Bobot Nilai Core) / Jumlah Kriteria Core
                                </div>
                                <template x-if="activeSpkDetail">
                                    <div class="text-xs font-mono text-slate-700">
                                        NCF = <span class="text-emerald-700 font-bold">
                                            <template x-for="(item, idx) in activeSpkDetail.criteria_details.filter(c => c.status === 'core')" :key="idx">
                                                <span><span x-show="idx > 0"> + </span><span x-text="item.bobot_nilai.toFixed(1)"></span></span>
                                            </template>
                                        </span>
                                        / <span x-text="activeSpkDetail.criteria_details.filter(c => c.status === 'core').length"></span>
                                        = <strong class="text-emerald-800" x-text="activeSpkDetail.ncf"></strong>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="bg-amber-50/60 border border-amber-100 rounded-xl p-4 space-y-2">
                            <div class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">Nilai Secondary Factor (NSF)</div>
                            <div class="bg-white rounded-lg px-3 py-2 border border-amber-100/50 space-y-1">
                                <div class="text-xs text-slate-600">
                                    NSF = Σ(Bobot Nilai Secondary) / Jumlah Kriteria Secondary
                                </div>
                                <template x-if="activeSpkDetail">
                                    <div class="text-xs font-mono text-slate-700">
                                        NSF = <span class="text-amber-700 font-bold">
                                            <template x-for="(item, idx) in activeSpkDetail.criteria_details.filter(c => c.status === 'secondary')" :key="idx">
                                                <span><span x-show="idx > 0"> + </span><span x-text="item.bobot_nilai.toFixed(1)"></span></span>
                                            </template>
                                        </span>
                                        / <span x-text="activeSpkDetail.criteria_details.filter(c => c.status === 'secondary').length"></span>
                                        = <strong class="text-amber-800" x-text="activeSpkDetail.nsf"></strong>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Nilai Akhir --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-extrabold flex items-center justify-center">3</span>
                        Nilai Akhir & Skor Matching
                    </h4>
                    <div class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-4 space-y-3">
                        <template x-if="activeSpkDetail">
                            <div class="space-y-3">
                                <div class="bg-white rounded-lg px-3 py-2.5 border border-indigo-100/50 space-y-2">
                                    <div class="text-[10px] font-bold text-indigo-800 uppercase tracking-wider">Proses Perhitungan</div>
                                    <div class="text-xs font-mono text-slate-700 space-y-1">
                                        <div>
                                            Nilai Akhir = Σ(Bobot% × Bobot_Nilai) / 100
                                        </div>
                                        <div class="text-slate-500">
                                            = <template x-for="(item, idx) in activeSpkDetail.criteria_details" :key="idx">
                                                <span><span x-show="idx > 0"> + </span>(<span x-text="item.weight"></span>% × <span x-text="item.bobot_nilai.toFixed(1)"></span>)</span>
                                            </template> / 100
                                        </div>
                                        <div class="font-bold text-indigo-800">
                                            = <span x-text="activeSpkDetail.nilai_akhir"></span> (skala 1.0 – 5.0)
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg px-3 py-2.5 border border-indigo-100/50 space-y-2">
                                    <div class="text-[10px] font-bold text-indigo-800 uppercase tracking-wider">Konversi ke Persentase</div>
                                    <div class="text-xs font-mono text-slate-700 space-y-1">
                                        <div>Skor (%) = ((<span x-text="activeSpkDetail.nilai_akhir"></span> − 1) / 4) × 100</div>
                                        <div class="font-bold text-indigo-800">= <span x-text="activeSpkDetail.matching_score"></span>%</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 pt-2">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-600">Status Prioritas:</span>
                                        <span x-show="activeSpkDetail.is_priority" class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-100">Prioritas</span>
                                        <span x-show="!activeSpkDetail.is_priority" class="text-[10px] font-extrabold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200">Non-Prioritas</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-600">Skor Akhir:</span>
                                        <span class="text-lg font-extrabold tracking-tight" :class="activeSpkDetail.matching_score >= 80 ? 'text-emerald-700' : (activeSpkDetail.matching_score >= 60 ? 'text-amber-600' : 'text-rose-600')" x-text="activeSpkDetail.matching_score + '%'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                <button @click="activeSpkDetail = null"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all border border-slate-200/50">
                    Tutup
                </button>
            </div>
        </div>
    </div>

@endsection
