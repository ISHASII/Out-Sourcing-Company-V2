@php
    $pageStr = $xDataPrefix ? $xDataPrefix . 'Page' : 'page';
    $perPageStr = $xDataPrefix ? $xDataPrefix . 'PerPage' : 'perPage';
    $totalStr = $xDataPrefix ? 'total' . ucfirst($xDataPrefix) : 'total';
@endphp
<div class="overflow-x-auto" x-data="{ expandedRows: {} }">
    <table class="min-w-full text-xs">
        <thead class="text-slate-400 border-b border-slate-150">
            <tr>
                <th class="text-left pb-3 font-bold uppercase tracking-wider">Nama Pelamar</th>
                <th class="text-left pb-3 font-bold uppercase tracking-wider">Status</th>
                @if($isCompleted)
                <th class="text-left pb-3 font-bold uppercase tracking-wider">Skor SPK</th>
                @endif
                <th class="text-left pb-3 font-bold uppercase tracking-wider">Kualifikasi Utama</th>
                <th class="text-left pb-3 font-bold uppercase tracking-wider">Dokumen Pendukung</th>
                <th class="text-center pb-3 font-bold uppercase tracking-wider w-28">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-slate-750 divide-y divide-slate-100">
            @forelse($applications as $index => $application)
                @php
                    $spkDetails = $application->spk_details ?? null;
                    if (is_string($spkDetails)) {
                        $spkDetails = json_decode($spkDetails, true);
                    }
                    $criteriaList = $spkDetails['criteria_details'] ?? ($spkDetails['breakdown'] ?? []);

                    // Fallback on-the-fly calculation for older database entries
                    if ($isCompleted && (empty($spkDetails) || empty($criteriaList))) {
                        $spkDetails = $posting->calculateSpkScoreDetailed($application);
                        $criteriaList = $spkDetails['criteria_details'] ?? [];
                    }
                    
                    // Prepare data for the accordion
                    $hasSpk = $isCompleted && !empty($spkDetails) && !empty($criteriaList);
                    $rowDisplay = $xDataPrefix ? "$pageStr === Math.ceil((" . ($index + 1) . ") / $perPageStr)" : "true";
                @endphp
                
                {{-- Main Applicant Row --}}
                <tr class="hover:bg-slate-50/50 transition-colors" x-show="{{ $rowDisplay }}">
                    <td class="py-4 pr-2">
                        <div class="flex items-center gap-2">
                            @if($hasSpk)
                            <button @click="expandedRows[{{ $application->id }}] = !expandedRows[{{ $application->id }}]" class="p-1 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-all" title="Lihat Rincian Perhitungan SPK">
                                <svg class="w-4 h-4 transform transition-transform" :class="expandedRows[{{ $application->id }}] ? 'rotate-90 text-indigo-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                            @endif
                            <div>
                                <div class="font-bold text-slate-800 text-sm">{{ $application->user->name }}</div>
                                <div class="text-[10px] text-slate-400 mt-1 font-semibold">{{ $application->gender === 'male' ? 'Pria' : 'Wanita' }}, {{ $application->age ?? '-' }} Tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        @if($application->status === 'accepted')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/70">Diterima</span>
                        @elseif($application->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100/70">Ditolak</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100/70">Pending</span>
                        @endif
                    </td>
                    @if($isCompleted)
                    <td class="py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/70 shadow-sm">
                            <svg class="text-emerald-600 shrink-0" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            {{ number_format($application->matching_score, 0) }}% Match
                        </span>
                    </td>
                    @endif
                    <td class="py-4 pr-2">
                        <div class="space-y-1">
                            <span class="block">Pendidikan: <strong class="text-slate-800 font-bold">{{ $application->education_level }}</strong></span>
                            <span class="block text-[10px] text-slate-500">Penempatan: <strong class="font-bold text-emerald-600">{{ $application->placement_ready ? 'Siap' : 'Tidak' }}</strong></span>
                        </div>
                    </td>
                    <td class="py-4 pr-2">
                        <div class="flex flex-wrap gap-1.5">
                            @if($application->agd_certificate_path)
                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100/50" href="{{ asset('storage/' . $application->agd_certificate_path) }}" target="_blank">AGD</a>
                            @endif
                            @if($application->sim_c_path)
                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100/50" href="{{ asset('storage/' . $application->sim_c_path) }}" target="_blank">SIM C</a>
                            @endif
                            @if($application->sim_b1_path)
                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-100/50" href="{{ asset('storage/' . $application->sim_b1_path) }}" target="_blank">SIM B1</a>
                            @endif
                            @if($application->user->profile?->cv_path)
                                <a class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100/50" href="{{ asset('storage/' . $application->user->profile->cv_path) }}" target="_blank">CV</a>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            @if($application->status === 'pending')
                                <form id="accept-form-{{ $application->id }}" action="{{ route('hrd.applications.accept', $application) }}" method="POST" class="hidden">@csrf</form>
                                <form id="reject-form-{{ $application->id }}" action="{{ route('hrd.applications.reject', $application) }}" method="POST" class="hidden">@csrf</form>
                                <button @click="$dispatch('open-confirm-modal', {
                                        title: 'Terima Pelamar',
                                        message: 'Apakah Anda yakin ingin menerima pelamar {{ $application->user->name }}?',
                                        confirmText: 'Ya, Terima', type: 'info', actionType: 'submit',
                                        formElement: document.getElementById('accept-form-{{ $application->id }}')
                                    })" class="inline-flex items-center justify-center p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-700 rounded-xl transition-all border border-emerald-100/50 shadow-sm" title="Terima Pelamar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                </button>
                                <button @click="$dispatch('open-confirm-modal', {
                                        title: 'Tolak Pelamar',
                                        message: 'Apakah Anda yakin ingin menolak pelamar {{ $application->user->name }}?',
                                        confirmText: 'Ya, Tolak', type: 'danger', actionType: 'submit',
                                        formElement: document.getElementById('reject-form-{{ $application->id }}')
                                    })" class="inline-flex items-center justify-center p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 rounded-xl transition-all border border-rose-100/50 shadow-sm" title="Tolak Pelamar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @elseif($application->status === 'accepted' && empty($application->interview_status))
                                <form id="valid-form-{{ $application->id }}" action="{{ route('hrd.applications.markValid', $application) }}" method="POST" class="hidden">@csrf</form>
                                <form id="invalid-form-{{ $application->id }}" action="{{ route('hrd.applications.markInvalid', $application) }}" method="POST" class="hidden">@csrf</form>
                                <button @click="$dispatch('open-confirm-modal', {
                                        title: 'Validasi Wawancara',
                                        message: 'Apakah pelamar {{ $application->user->name }} LOLOS wawancara?',
                                        confirmText: 'Ya, Lolos (Valid)', type: 'info', actionType: 'submit',
                                        formElement: document.getElementById('valid-form-{{ $application->id }}')
                                    })" class="inline-flex items-center justify-center p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-xl border border-emerald-100/50 shadow-sm" title="Wawancara Valid">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                <button @click="$dispatch('open-confirm-modal', {
                                        title: 'Validasi Wawancara',
                                        message: 'Apakah pelamar {{ $application->user->name }} GAGAL wawancara?',
                                        confirmText: 'Ya, Gagal (Tidak Valid)', type: 'danger', actionType: 'submit',
                                        formElement: document.getElementById('invalid-form-{{ $application->id }}')
                                    })" class="inline-flex items-center justify-center p-2 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl border border-rose-100/50 shadow-sm" title="Wawancara Tidak Valid">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            @endif

                            {{-- Chat WA --}}
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->user->profile->phone ?? '') }}" target="_blank" class="inline-flex items-center justify-center p-2 text-green-600 bg-green-50 hover:bg-green-100 rounded-xl transition-all border border-green-100/50 shadow-sm" title="Chat WhatsApp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/></svg>
                            </a>
                            
                            {{-- Email --}}
                            <a href="mailto:{{ $application->user->email }}" class="inline-flex items-center justify-center p-2 text-sky-600 bg-sky-50 hover:bg-sky-100 rounded-xl transition-all border border-sky-100/50 shadow-sm" title="Kirim Email">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </a>
                            @if($isCompleted)
                            <a href="{{ route('hrd.applications.pdf', $application) }}" target="_blank" class="inline-flex items-center justify-center p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 hover:text-indigo-700 rounded-xl transition-all border border-indigo-100/50 shadow-sm" title="Cetak PDF SPK">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.615 0-1.101-.476-1.12-1.08L5.82 18m11.84 0h-11.84m12.48-5.323a1.947 1.947 0 00-2.38-1.947h-6.562a1.947 1.947 0 00-2.38 1.947m11.322 0A1.947 1.947 0 0119.5 13.5v3.11a1.947 1.947 0 01-1.84 1.947m-11.322 0A1.947 1.947 0 004.5 16.61v-3.11c0-.88.667-1.63 1.522-1.752z"></path></svg>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Accordion SPK Detail Tables --}}
                @if($hasSpk)
                <tr class="bg-slate-50/50" x-show="expandedRows[{{ $application->id }}] && {{ $rowDisplay }}" x-transition style="display: none;">
                    <td colspan="6" class="p-6 border-b border-slate-200">
                        <div class="grid grid-cols-1 gap-6">
                            
                            {{-- 1. Tabel Nilai Profile Matching --}}
                            <div>
                                <h6 class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">1. Tabel Nilai Profile Matching Pelamar</h6>
                                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                    <table class="w-full text-xs">
                                        <thead class="bg-indigo-50/50 text-indigo-800">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Nama</th>
                                                @foreach($criteriaList as $c)
                                                    <th class="px-3 py-2 text-center">{{ $c['label'] }} ({{ ucfirst($c['status'] ?? 'secondary') }})</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <tr>
                                                <td class="px-3 py-2 font-bold text-slate-700">{{ $application->user->name }}</td>
                                                @foreach($criteriaList as $c)
                                                    <td class="px-3 py-2 text-center text-slate-600">{{ $c['applicant_display'] ?? ($c['applicant_value'] ?? '-') }}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- 2. Tabel Pemetaan GAP --}}
                            <div>
                                <h6 class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">2. Tabel Pemetaan GAP Kandidat</h6>
                                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                    <table class="w-full text-xs">
                                        <thead class="bg-amber-50/50 text-amber-800">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Nama</th>
                                                @foreach($criteriaList as $c)
                                                    <th class="px-3 py-2 text-center">{{ $c['label'] }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <tr>
                                                <td class="px-3 py-2 font-bold text-slate-700">{{ $application->user->name }}</td>
                                                @foreach($criteriaList as $c)
                                                    <td class="px-3 py-2 text-center font-mono {{ ($c['gap'] ?? 0) < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                                        {{ ($c['gap'] ?? 0) > 0 ? '+'.($c['gap'] ?? 0) : ($c['gap'] ?? 0) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- 3. Tabel Konversi Nilai GAP Menjadi Nilai Bobot --}}
                            <div>
                                <h6 class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">3. Tabel Konversi Nilai GAP Menjadi Nilai Bobot</h6>
                                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                    <table class="w-full text-xs">
                                        <thead class="bg-emerald-50/50 text-emerald-800">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Nama</th>
                                                @foreach($criteriaList as $c)
                                                    <th class="px-3 py-2 text-center">{{ $c['label'] }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <tr>
                                                <td class="px-3 py-2 font-bold text-slate-700">{{ $application->user->name }}</td>
                                                @foreach($criteriaList as $c)
                                                    <td class="px-3 py-2 text-center font-bold text-slate-700">{{ number_format($c['bobot_nilai'] ?? 0, 1) }}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- 4. Tabel Perhitungan Nilai Akhir --}}
                            <div>
                                <h6 class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">4. Tabel Perhitungan Nilai Akhir</h6>
                                <div class="overflow-x-auto border border-slate-200 rounded-lg bg-white p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-1">
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">Nilai Core Factor (NCF)</div>
                                            <div class="font-mono text-xs text-slate-700">
                                                @php
                                                    $coreItems = collect($criteriaList)->where('status', 'core');
                                                    $cfTerms = [];
                                                    $cfWeightSum = 0;
                                                    foreach ($coreItems as $c) {
                                                        $w = ($c['weight'] ?? 0) / 100;
                                                        $val = (float)($c['bobot_nilai'] ?? 0);
                                                        $valFormatted = floor($val) == $val ? number_format($val, 0) : number_format($val, 1);
                                                        $cfTerms[] = "(" . number_format($w, 2) . " × " . $valFormatted . ")";
                                                        $cfWeightSum += $w;
                                                    }
                                                    $cfString = !empty($cfTerms) ? implode(' + ', $cfTerms) : '0';
                                                    $cfDivider = number_format($cfWeightSum ?: 1.0, 2);
                                                @endphp
                                                ({{ $cfString }}) / {{ $cfDivider }} = <strong class="text-rose-600">{{ number_format($spkDetails['ncf'] ?? 0, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">Nilai Secondary Factor (NSF)</div>
                                            <div class="font-mono text-xs text-slate-700">
                                                @php
                                                    $secItems = collect($criteriaList)->where('status', 'secondary');
                                                    $sfTerms = [];
                                                    $sfWeightSum = 0;
                                                    foreach ($secItems as $c) {
                                                        $w = ($c['weight'] ?? 0) / 100;
                                                        $val = (float)($c['bobot_nilai'] ?? 0);
                                                        $valFormatted = floor($val) == $val ? number_format($val, 0) : number_format($val, 1);
                                                        $sfTerms[] = "(" . number_format($w, 2) . " × " . $valFormatted . ")";
                                                        $sfWeightSum += $w;
                                                    }
                                                    $sfString = !empty($sfTerms) ? implode(' + ', $sfTerms) : '0';
                                                    $sfDivider = number_format($sfWeightSum ?: 1.0, 2);
                                                @endphp
                                                ({{ $sfString }}) / {{ $sfDivider }} = <strong class="text-blue-600">{{ number_format($spkDetails['nsf'] ?? 0, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="space-y-1 md:col-span-2 pt-2 border-t border-slate-100">
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">Perhitungan Nilai Akhir & Skor</div>
                                            <div class="font-mono text-xs text-slate-700 flex flex-col gap-1 mt-1">
                                                @php
                                                    $cfWeight = $spkDetails['cf_weight_percent'] ?? 60;
                                                    $sfWeight = $spkDetails['sf_weight_percent'] ?? 40;
                                                    $finalScore = $spkDetails['nilai_akhir'] ?? ($spkDetails['final_score'] ?? 0);
                                                    $matchScore = $spkDetails['matching_score'] ?? 0;
                                                @endphp
                                                <span>Nilai Akhir = ({{ $cfWeight }}% × {{ number_format($spkDetails['ncf'] ?? 0, 2) }}) + ({{ $sfWeight }}% × {{ number_format($spkDetails['nsf'] ?? 0, 2) }}) = <strong class="text-indigo-600">{{ number_format($finalScore, 2) }}</strong></span>
                                                <span>Skor Match = (({{ number_format($finalScore, 2) }} - 1) / 4) × 100% = <strong class="text-emerald-600">{{ number_format($matchScore, 0) }}%</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-slate-400 font-semibold border-t border-slate-100">
                        <div class="flex flex-col items-center justify-center gap-2 py-4">
                            <span>Tidak ada data pelamar.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Controls -->
<div class="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold text-slate-500" x-show="{{ $totalStr }} > {{ $perPageStr }}">
    <div>
        Menampilkan 
        <span class="text-slate-800" x-text="Math.min(({{ $pageStr }} - 1) * {{ $perPageStr }} + 1, {{ $totalStr }})"></span> 
        - 
        <span class="text-slate-800" x-text="Math.min({{ $pageStr }} * {{ $perPageStr }}, {{ $totalStr }})"></span> 
        dari 
        <span class="text-slate-800" x-text="{{ $totalStr }}"></span> pelamar
    </div>
    <div class="flex items-center gap-1">
        <button @click="if({{ $pageStr }} > 1) { {{ $pageStr }}-- }" :disabled="{{ $pageStr }} === 1"
            class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 transition-all duration-200">
            Sebelumnya
        </button>
        
        <template x-for="p in Math.ceil({{ $totalStr }} / {{ $perPageStr }})" :key="p">
            <button @click="{{ $pageStr }} = p" class="w-8 h-8 rounded-xl flex items-center justify-center border transition-all duration-200"
                :class="{{ $pageStr }} === p ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
                x-text="p"></button>
        </template>

        <button @click="if({{ $pageStr }} < Math.ceil({{ $totalStr }} / {{ $perPageStr }})) { {{ $pageStr }}++ }" :disabled="{{ $pageStr }} === Math.ceil({{ $totalStr }} / {{ $perPageStr }})"
            class="px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-50 transition-all duration-200">
            Selanjutnya
        </button>
    </div>
</div>
