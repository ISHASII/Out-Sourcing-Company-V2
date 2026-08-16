<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil SPK</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 20px 30px; line-height: 1.4; }
        
        /* Header & Title */
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #003d7c; padding-bottom: 10px; }
        .title { font-size: 16px; font-weight: bold; color: #003d7c; text-transform: uppercase; letter-spacing: 1px; }
        .subtitle { font-size: 10px; color: #666; margin-top: 5px; }
        
        /* Information Section */
        .info-container { display: table; width: 100%; margin-bottom: 25px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .info-row { margin-bottom: 5px; }
        .info-label { font-weight: bold; color: #555; display: inline-block; width: 140px; }
        .info-value { font-weight: bold; color: #000; }
        
        /* Recap Box */
        .recap-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 10px 15px; margin-bottom: 25px; }
        .recap-title { font-size: 12px; font-weight: bold; color: #003d7c; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; text-transform: uppercase; }
        .recap-grid { display: table; width: 100%; }
        .recap-item { display: table-cell; width: 25%; text-align: center; border-right: 1px solid #e2e8f0; }
        .recap-item:last-child { border-right: none; }
        .recap-val { font-size: 16px; font-weight: bold; color: #0f172a; }
        .recap-desc { font-size: 9px; color: #64748b; text-transform: uppercase; margin-top: 3px; }

        /* Tables */
        .section-title { font-size: 12px; font-weight: bold; color: #003d7c; margin: 20px 0 8px 0; text-transform: uppercase; border-left: 3px solid #003d7c; padding-left: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; page-break-inside: avoid; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; vertical-align: top; }
        th { background-color: #003d7c; color: #ffffff; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        tr:nth-child(even) td { background-color: #f8fafc; }
        
        .two-cols th { width: 50%; background-color: #0ea5e9; }
        .two-cols-3 th { width: 50%; background-color: #10b981; }
        
        .total-row { background-color: #f1f5f9 !important; }
        .total-row td { font-weight: bold; color: #0f172a; padding: 10px; border-top: 2px solid #cbd5e1; text-align: center; }
        
        ul { margin: 0; padding-left: 18px; }
        li { margin-bottom: 4px; }
        
        /* Badges */
        .badge { display: inline-block; padding: 3px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-acc { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-rej { background-color: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }
        .badge-pen { background-color: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
    </style>
</head>
<body>

    @php
        $totalMatchingScore = 0;
        $highestScore = 0;
        $validCount = 0;
        foreach($allApplications as $app) {
            $spkDetails = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
            $ms = $spkDetails['matching_score'] ?? 0;
            $totalMatchingScore += $ms;
            if($ms > $highestScore) $highestScore = $ms;
            $validCount++;
        }
        $avgScore = $validCount > 0 ? ($totalMatchingScore / $validCount) : 0;
    @endphp

    <div class="header">
        <div class="title">LAPORAN HASIL REKRUTMEN (SPK)</div>
        <div class="subtitle">Sistem Pendukung Keputusan Penempatan Karyawan</div>
    </div>

    <div class="info-container">
        <div class="info-col">
            <div class="info-row"><span class="info-label">Kategori / Posisi</span> : <span class="info-value">{{ $posting->category }} - {{ $posting->title }}</span></div>
            <div class="info-row"><span class="info-label">Nama Mitra (Klien)</span> : <span class="info-value">{{ $posting->mitra_name }}</span></div>
            <div class="info-row"><span class="info-label">Total Pelamar Terdata</span> : <span class="info-value">{{ $allApplications->count() }} Orang</span></div>
        </div>
        <div class="info-col">
            <div class="info-row"><span class="info-label">Tanggal Laporan</span> : <span class="info-value">{{ date('d F Y') }}</span></div>
            <div class="info-row"><span class="info-label">Admin Penyusun</span> : <span class="info-value">{{ $adminName ?? 'HRD System' }}</span></div>
        </div>
    </div>

    <!-- REKAPITULASI -->
    <div class="recap-box">
        <div class="recap-title">Rekapitulasi Hasil Profile Matching</div>
        <div class="recap-grid">
            <div class="recap-item">
                <div class="recap-val">{{ number_format($avgScore, 1) }}%</div>
                <div class="recap-desc">Rata-Rata Kecocokan</div>
            </div>
            <div class="recap-item">
                <div class="recap-val">{{ number_format($highestScore, 1) }}%</div>
                <div class="recap-desc">Skor Tertinggi</div>
            </div>
            <div class="recap-item">
                <div class="recap-val" style="color: #10b981;">{{ $accepted->count() }}</div>
                <div class="recap-desc">Kandidat Diterima</div>
            </div>
            <div class="recap-item">
                <div class="recap-val" style="color: #ef4444;">{{ $rejected->count() }}</div>
                <div class="recap-desc">Kandidat Ditolak</div>
            </div>
        </div>
    </div>

    <div class="section-title">1. Tabel Perbandingan Hasil Profile Matching</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Kandidat</th>
                <th style="width: 15%; text-align: center;">Nilai Akhir</th>
                <th style="width: 25%; text-align: center;">Matching Score</th>
                <th style="width: 20%; text-align: center;">Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allApplications as $i => $app)
            @php
                $spkDetails = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
                $nilaiAkhir = $spkDetails['nilai_akhir'] ?? 0;
                $matchingScore = $spkDetails['matching_score'] ?? 0;
                
                $badgeClass = 'badge-pen';
                $statusText = 'Pending';
                if ($app->status === 'accepted') { $badgeClass = 'badge-acc'; $statusText = 'Diterima'; }
                if ($app->status === 'rejected') { $badgeClass = 'badge-rej'; $statusText = 'Ditolak'; }
            @endphp
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td><strong>{{ $app->user->name }}</strong></td>
                <td style="text-align: center;">{{ number_format($nilaiAkhir, 2) }}</td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($matchingScore, 0) }}%</td>
                <td style="text-align: center;"><span class="badge {{ $badgeClass }}">{{ $statusText }}</span></td>
            </tr>
            @endforeach
            @if($allApplications->isEmpty())
            <tr>
                <td colspan="5" style="text-align:center; padding: 15px;">Belum ada pelamar.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">2. Kandidat Prioritas vs Non-Prioritas</div>
    <table class="two-cols">
        <thead>
            <tr>
                <th>Daftar Kandidat Prioritas (Disarankan)</th>
                <th>Daftar Kandidat Non-Prioritas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul>
                        @foreach($priority as $cand)
                            <li>{{ $cand->user->name }}</li>
                        @endforeach
                        @if($priority->isEmpty())
                            <li style="color: #94a3b8; list-style: none;">Tidak ada kandidat prioritas.</li>
                        @endif
                    </ul>
                </td>
                <td>
                    <ul>
                        @foreach($nonPriority as $cand)
                            <li>{{ $cand->user->name }}</li>
                        @endforeach
                        @if($nonPriority->isEmpty())
                            <li style="color: #94a3b8; list-style: none;">Tidak ada kandidat non-prioritas.</li>
                        @endif
                    </ul>
                </td>
            </tr>
            <tr class="total-row">
                <td>Total: {{ $priority->count() }} Orang</td>
                <td>Total: {{ $nonPriority->count() }} Orang</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">3. Keputusan Akhir Kandidat</div>
    <table class="two-cols-3">
        <thead>
            <tr>
                <th>Kandidat Terpilih (Lolos Seleksi Wawancara)</th>
                <th>Kandidat Tertolak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul>
                        @foreach($accepted as $cand)
                            <li><strong>{{ $cand->user->name }}</strong></li>
                        @endforeach
                        @if($accepted->isEmpty())
                            <li style="color: #94a3b8; list-style: none;">Belum ada kandidat terpilih.</li>
                        @endif
                    </ul>
                </td>
                <td>
                    <ul>
                        @foreach($rejected as $cand)
                            <li>{{ $cand->user->name }}</li>
                        @endforeach
                        @if($rejected->isEmpty())
                            <li style="color: #94a3b8; list-style: none;">Tidak ada kandidat tertolak.</li>
                        @endif
                    </ul>
                </td>
            </tr>
            <tr class="total-row">
                <td>Total Terpilih: {{ $accepted->count() }} Orang</td>
                <td>Total Tertolak: {{ $rejected->count() }} Orang</td>
            </tr>
        </tbody>
    </table>


    @php
        $firstApp = $allApplications->first();
        $firstSpk = $firstApp ? (is_string($firstApp->spk_details) ? json_decode($firstApp->spk_details, true) : $firstApp->spk_details) : null;
        
        // Fallback for older database formats if not exist
        if ($firstApp && empty($firstSpk)) {
            $firstSpk = $posting->calculateSpkScoreDetailed($firstApp);
        }
        
        $criteriaList = $firstSpk['criteria_details'] ?? ($firstSpk['breakdown'] ?? []);
    @endphp

    @if(!empty($criteriaList))
    <div style="page-break-before: always;"></div>
    
    <div class="header">
        <div class="title">LAMPIRAN: DETAIL PERHITUNGAN PROFILE MATCHING</div>
        <div class="subtitle">Rekapitulasi Proses Perhitungan SPK Secara Transparan</div>
    </div>

    <div class="section-title">A. Rumus dan Ketentuan Penilaian</div>
    <div class="recap-box" style="margin-bottom: 20px;">
        <p style="margin-top: 0;"><strong>Pemetaan GAP (Selisih):</strong> GAP = Nilai Kandidat - Nilai Target Posisi</p>
        <p><strong>Pembobotan GAP:</strong></p>
        <ul style="font-size: 10px; margin-bottom: 10px;">
            <li>GAP 0 = 5.0 (Kompetensi Sesuai Kebutuhan)</li>
            <li>GAP 1 = 4.5 (Kompetensi Kelebihan 1 Tingkat)</li>
            <li>GAP -1 = 4.0 (Kompetensi Kekurangan 1 Tingkat)</li>
            <li>GAP 2 = 3.5 (Kompetensi Kelebihan 2 Tingkat)</li>
            <li>GAP -2 = 3.0 (Kompetensi Kekurangan 2 Tingkat)</li>
        </ul>
        <p><strong>Perhitungan Nilai Akhir:</strong><br/>
           - Nilai Core Factor (NCF): Rata-rata bobot kriteria utama (60%)<br/>
           - Nilai Secondary Factor (NSF): Rata-rata bobot kriteria pendukung (40%)<br/>
           - <strong>Nilai Akhir (Total) = (NCF x 60%) + (NSF x 40%)</strong>
        </p>
    </div>

    <div class="section-title">B. Tabel Profil Kandidat vs Target</div>
    <table style="font-size: 9px;">
        <thead>
            <tr>
                <th style="width: 20%;">Nama Kandidat</th>
                @foreach($criteriaList as $c)
                    <th style="text-align: center;">{{ $c['label'] }}<br/><span style="font-size: 7px; color: #bae6fd;">({{ ucfirst($c['status'] ?? 'secondary') }})</span></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($allApplications as $app)
                @php
                    $spk = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
                    if(empty($spk)) $spk = $posting->calculateSpkScoreDetailed($app);
                    $cl = $spk['criteria_details'] ?? ($spk['breakdown'] ?? []);
                @endphp
                <tr>
                    <td><strong>{{ $app->user->name }}</strong></td>
                    @foreach($cl as $c)
                        <td style="text-align: center;">{{ $c['applicant_display'] ?? ($c['applicant_value'] ?? '-') }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">C. Tabel Selisih (GAP)</div>
    <table style="font-size: 9px;">
        <thead>
            <tr>
                <th style="width: 20%;">Nama Kandidat</th>
                @foreach($criteriaList as $c)
                    <th style="text-align: center;">{{ $c['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($allApplications as $app)
                @php
                    $spk = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
                    if(empty($spk)) $spk = $posting->calculateSpkScoreDetailed($app);
                    $cl = $spk['criteria_details'] ?? ($spk['breakdown'] ?? []);
                @endphp
                <tr>
                    <td><strong>{{ $app->user->name }}</strong></td>
                    @foreach($cl as $c)
                        <td style="text-align: center; color: {{ ($c['gap'] ?? 0) < 0 ? '#e11d48' : '#059669' }}; font-weight: bold;">
                            {{ ($c['gap'] ?? 0) > 0 ? '+'.($c['gap'] ?? 0) : ($c['gap'] ?? 0) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">D. Tabel Konversi Bobot GAP</div>
    <table style="font-size: 9px;">
        <thead>
            <tr>
                <th style="width: 20%;">Nama Kandidat</th>
                @foreach($criteriaList as $c)
                    <th style="text-align: center;">{{ $c['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($allApplications as $app)
                @php
                    $spk = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
                    if(empty($spk)) $spk = $posting->calculateSpkScoreDetailed($app);
                    $cl = $spk['criteria_details'] ?? ($spk['breakdown'] ?? []);
                @endphp
                <tr>
                    <td><strong>{{ $app->user->name }}</strong></td>
                    @foreach($cl as $c)
                        <td style="text-align: center; font-weight: bold;">{{ number_format($c['bobot_nilai'] ?? 0, 1) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">E. Tabel Perhitungan Nilai Akhir (NCF & NSF)</div>
    <table style="font-size: 9px;">
        <thead>
            <tr>
                <th>Nama Kandidat</th>
                <th style="text-align: center;">Total Bobot Core</th>
                <th style="text-align: center;">NCF (Total / Jumlah)</th>
                <th style="text-align: center;">Total Bobot Secondary</th>
                <th style="text-align: center;">NSF (Total / Jumlah)</th>
                <th style="text-align: center; background-color: #0369a1;">Nilai Akhir (Total)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allApplications as $app)
                @php
                    $spk = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
                    if(empty($spk)) $spk = $posting->calculateSpkScoreDetailed($app);
                @endphp
                <tr>
                    <td><strong>{{ $app->user->name }}</strong></td>
                    <td style="text-align: center;">{{ number_format($spk['ncf_raw'] ?? 0, 2) }}</td>
                    <td style="text-align: center;"><strong>{{ number_format($spk['ncf'] ?? 0, 2) }}</strong></td>
                    <td style="text-align: center;">{{ number_format($spk['nsf_raw'] ?? 0, 2) }}</td>
                    <td style="text-align: center;"><strong>{{ number_format($spk['nsf'] ?? 0, 2) }}</strong></td>
                    <td style="text-align: center; font-size: 11px; font-weight: bold; color: #0369a1; background-color: #f0f9ff;">
                        {{ number_format($spk['nilai_akhir'] ?? 0, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="section-title" style="page-break-before: always;">F. Detail Perhitungan Tiap Kandidat</div>
    @foreach($allApplications as $app)
        @php
            $spk = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
            if(empty($spk)) $spk = $posting->calculateSpkScoreDetailed($app);
            
            $cl = $spk['criteria_details'] ?? ($spk['breakdown'] ?? []);
            
            $cfTotal = 0; $sfTotal = 0;
            $cfWeight = $spk['cf_weight_percent'] ?? 60;
            $sfWeight = $spk['sf_weight_percent'] ?? 40;
            
            $cfFormula = [];
            $sfFormula = [];
            
            foreach($cl as $c) {
                $w = ($c['weight'] ?? 0) / 100;
                $b = $c['bobot_nilai'] ?? 0;
                $str = "(".number_format($w, 2)." &times; ".number_format($b, 1).")";
                if (($c['status'] ?? 'secondary') === 'core') {
                    $cfFormula[] = $str;
                } else {
                    $sfFormula[] = $str;
                }
            }
            
            $cfDiv = ($cfWeight / 100);
            $sfDiv = ($sfWeight / 100);
            
            $cfFormulaStr = !empty($cfFormula) ? implode(" + ", $cfFormula) . " / " . number_format($cfDiv, 2) : "0";
            $sfFormulaStr = !empty($sfFormula) ? implode(" + ", $sfFormula) . " / " . number_format($sfDiv, 2) : "0";
        @endphp
        <div class="recap-box" style="margin-bottom: 15px; page-break-inside: avoid;">
            <div style="font-weight: bold; font-size: 11px; margin-bottom: 8px; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px;">Kandidat: {{ $app->user->name }}</div>
            
            <div style="margin-bottom: 6px;">
                <span style="font-weight: bold;">Rata-rata Core Factor (NCF):</span> {{ number_format($spk['ncf'] ?? 0, 2) }}<br/>
                <span style="font-size: 9px; color: #64748b;">Rumus: {{ $cfFormulaStr }} = {{ number_format($spk['ncf'] ?? 0, 2) }}</span>
            </div>
            
            <div style="margin-bottom: 6px;">
                <span style="font-weight: bold;">Rata-rata Secondary Factor (NSF):</span> {{ number_format($spk['nsf'] ?? 0, 2) }}<br/>
                <span style="font-size: 9px; color: #64748b;">Rumus: {{ $sfFormulaStr }} = {{ number_format($spk['nsf'] ?? 0, 2) }}</span>
            </div>
            
            <div>
                <span style="font-weight: bold;">Formula Perhitungan Akhir:</span><br/>
                <span style="font-size: 9px; color: #64748b;">
                    Nilai Akhir = ({{ $cfWeight }}% &times; NCF) + ({{ $sfWeight }}% &times; NSF)<br/>
                    Nilai Akhir = ({{ $cfWeight }}% &times; {{ number_format($spk['ncf'] ?? 0, 2) }}) + ({{ $sfWeight }}% &times; {{ number_format($spk['nsf'] ?? 0, 2) }}) = {{ number_format($spk['nilai_akhir'] ?? 0, 2) }}<br/>
                    Skor = ((Nilai Akhir - 1) / 4) &times; 100%<br/>
                    Skor = (({{ number_format($spk['nilai_akhir'] ?? 0, 2) }} - 1) / 4) &times; 100% = {{ $spk['matching_score'] ?? 0 }}%
                </span>
            </div>
        </div>
    @endforeach
    @endif
</body>
</html>
