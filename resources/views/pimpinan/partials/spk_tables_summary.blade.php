@php
    // Gabungkan semua pelamar (prioritas dan non-prioritas)
    $allSpkApplications = $posting->spk_status === 'completed' 
        ? $priorityApplications->merge($nonPriorityApplications) 
        : $allApplications;

    // Kalkulasi SPK untuk semua pelamar agar siap dilooping
    $mappedApps = $allSpkApplications->map(function($app) use ($posting) {
        $spk = $app->spk_details;
        if (is_string($spk)) $spk = json_decode($spk, true);
        
        // Jika belum ada riwayat, sedang pending, atau menggunakan cache versi lama (tidak ada 'target')
        if (
            empty($spk) || 
            empty($spk['criteria_details']) || 
            !isset($spk['criteria_details'][0]['target'])
        ) {
            $spk = $posting->calculateSpkScoreDetailed($app);
            
            // Perbarui cache di database agar tidak perlu kalkulasi ulang terus menerus
            $app->update(['spk_details' => json_encode($spk)]);
        }
        
        return [
            'app' => $app,
            'spk' => $spk,
            'criteria' => collect($spk['criteria_details'] ?? [])
        ];
    });

    // Ambil list kriteria master dari pelamar pertama untuk dijadikan header tabel
    $masterCriteria = $mappedApps->first() ? $mappedApps->first()['criteria'] : collect([]);
    $hasApplicants = $mappedApps->isNotEmpty();
@endphp

@if($hasApplicants && $masterCriteria->isNotEmpty())
    <div class="mt-8 space-y-6" x-data="{ activeTab: 1 }">
        
        <div class="flex items-center gap-2 mb-4">
            <h4 class="text-sm font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2.5">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Tabel Rekapitulasi Profile Matching
            </h4>
        </div>

        <div class="flex flex-wrap gap-2 mb-6 border-b border-slate-200 pb-4">
            <button type="button" @click="activeTab = 1" :class="activeTab === 1 ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all">1. Profile Matching (Text)</button>
            <button type="button" @click="activeTab = 2" :class="activeTab === 2 ? 'bg-indigo-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all">2. Nilai Kandidat (1-5)</button>
            <button type="button" @click="activeTab = 3" :class="activeTab === 3 ? 'bg-rose-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all">3. Pemetaan GAP</button>
            <button type="button" @click="activeTab = 4" :class="activeTab === 4 ? 'bg-amber-500 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all">4. Konversi GAP -> Bobot</button>
            <button type="button" @click="activeTab = 5" :class="activeTab === 5 ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all">5. Perhitungan Akhir</button>
        </div>

        {{-- Tabel 1: Nilai Profile Matching --}}
        <div x-show="activeTab === 1" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm animate-fade-in" x-transition>
            <div class="p-4 border-b border-slate-100 bg-slate-50"><h5 class="text-sm font-bold text-slate-700">Tabel 1: Profile Matching (Nilai Mentah)</h5></div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-indigo-50/50 text-indigo-800">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">Nama Pelamar</th>
                            @foreach($masterCriteria as $c)
                                <th class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-indigo-100/50">
                                    {{ $c['label'] }} 
                                    <span class="block text-[9px] font-normal text-indigo-500 mt-0.5">({{ ucfirst($c['status'] ?? 'Secondary') }})</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($mappedApps as $index => $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-700 whitespace-nowrap">{{ $item['app']->user->name }}</td>
                                @foreach($item['criteria'] as $c)
                                    <td class="px-4 py-3 text-center text-slate-600 whitespace-nowrap border-l border-slate-100">{{ $c['applicant_display'] ?? ($c['applicant_value'] ?? '-') }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel 2: Nilai Kandidat (Skala 1-5) --}}
        <div x-show="activeTab === 2" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm animate-fade-in" x-transition style="display: none;">
            <div class="p-4 border-b border-slate-100 bg-slate-50"><h5 class="text-sm font-bold text-slate-700">Tabel 2: Nilai Kandidat (Dalam Skala 1-5)</h5></div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-indigo-50 text-indigo-900">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">Nama Pelamar</th>
                            @foreach($masterCriteria as $c)
                                <th class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-indigo-200/50">
                                    {{ $c['label'] }}
                                    <span class="block text-[10px] font-semibold text-indigo-600 mt-0.5">Target: {{ $c['target'] ?? 5 }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($mappedApps as $index => $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-700 whitespace-nowrap">{{ $item['app']->user->name }}</td>
                                @foreach($item['criteria'] as $c)
                                    <td class="px-4 py-3 text-center font-bold text-slate-600 whitespace-nowrap border-l border-slate-100">
                                        {{ $c['applicant_value'] ?? 0 }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel 3: Pemetaan GAP --}}
        <div x-show="activeTab === 3" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm animate-fade-in" x-transition style="display: none;">
            <div class="p-4 border-b border-slate-100 bg-slate-50"><h5 class="text-sm font-bold text-slate-700">Tabel 3: Pemetaan GAP Kandidat</h5></div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-rose-50/50 text-rose-800">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">Nama Pelamar</th>
                            @foreach($masterCriteria as $c)
                                <th class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-rose-100/50">
                                    {{ $c['label'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($mappedApps as $index => $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-700 whitespace-nowrap">{{ $item['app']->user->name }}</td>
                                @foreach($item['criteria'] as $c)
                                    @php $gap = $c['gap'] ?? 0; @endphp
                                    <td class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-slate-100 {{ $gap < 0 ? 'text-rose-600 bg-rose-50/30' : ($gap > 0 ? 'text-blue-600' : 'text-emerald-600') }}">
                                        {{ $gap > 0 ? '+'.$gap : $gap }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel 4: Konversi GAP ke Bobot --}}
        <div x-show="activeTab === 4" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm animate-fade-in" x-transition style="display: none;">
            <div class="p-4 border-b border-slate-100 bg-slate-50"><h5 class="text-sm font-bold text-slate-700">Tabel 4: Konversi Nilai GAP Menjadi Nilai Bobot</h5></div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-amber-50/50 text-amber-800">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">Nama Pelamar</th>
                            @foreach($masterCriteria as $c)
                                <th class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-amber-100/50">
                                    {{ $c['label'] }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($mappedApps as $index => $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-700 whitespace-nowrap">{{ $item['app']->user->name }}</td>
                                @foreach($item['criteria'] as $c)
                                    @php $bobot = number_format((float)($c['bobot_nilai'] ?? 0), 1); @endphp
                                    <td class="px-4 py-3 text-center font-semibold text-slate-600 whitespace-nowrap border-l border-slate-100">{{ $bobot }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel 5: Perhitungan Akhir --}}
        <div x-show="activeTab === 5" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm animate-fade-in" x-transition style="display: none;">
            <div class="p-4 border-b border-slate-100 bg-slate-50"><h5 class="text-sm font-bold text-slate-700">Tabel 5: Perhitungan Nilai Akhir</h5></div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-emerald-50/50 text-emerald-800">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">No</th>
                            <th class="px-4 py-3 text-left font-bold whitespace-nowrap">Nama Pelamar</th>
                            <th class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-emerald-100/50">Nilai Core Factor (NCF)</th>
                            <th class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-emerald-100/50">Nilai Secondary Factor (NSF)</th>
                            <th class="px-4 py-3 text-center font-bold whitespace-nowrap border-l border-emerald-100/50">Nilai Total (N)</th>
                            <th class="px-4 py-3 text-center font-black whitespace-nowrap border-l border-emerald-100/50 bg-emerald-100/50">Skor (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($mappedApps as $index => $item)
                            @php 
                                $spk = $item['spk']; 
                                $ncf = number_format($spk['ncf'] ?? 0, 2);
                                $nsf = number_format($spk['nsf'] ?? 0, 2);
                                $n = number_format( ($spk['ncf'] ?? 0) + ($spk['nsf'] ?? 0), 2);
                                $score = number_format($spk['matching_score'] ?? 0, 2);
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-bold text-slate-700 whitespace-nowrap">{{ $item['app']->user->name }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-slate-600 border-l border-slate-100">{{ $ncf }}</td>
                                <td class="px-4 py-3 text-center font-semibold text-slate-600 border-l border-slate-100">{{ $nsf }}</td>
                                <td class="px-4 py-3 text-center font-bold text-emerald-700 border-l border-slate-100">{{ $n }}</td>
                                <td class="px-4 py-3 text-center font-black text-emerald-700 border-l border-emerald-50 bg-emerald-50/30">{{ $score }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endif
