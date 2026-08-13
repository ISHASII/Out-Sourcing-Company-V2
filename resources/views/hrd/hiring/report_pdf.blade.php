<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil SPK</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #000; margin: 20px 30px; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 14px; font-weight: bold; margin: 0 0 20px 0; }
        
        .info-section { margin-bottom: 25px; line-height: 1.6; }
        .info-section div { display: flex; }
        .info-section .label { font-weight: bold; width: 180px; display: inline-block; }
        
        .section-title { font-size: 11px; font-weight: bold; margin: 20px 0 10px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; page-break-inside: avoid; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { font-weight: bold; }
        
        /* Table 2 and 3 specific styling */
        .two-cols th { width: 50%; }
        
        .total-row { font-weight: bold; }
        .total-row td { padding-top: 10px; padding-bottom: 10px; border-top: 1px solid #000; }
        
        /* List styles within tables */
        ul { margin: 0; padding-left: 15px; }
        li { margin-bottom: 3px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="title">LAPORAN HASIL SPK</div>
    </div>

    <div class="info-section">
        <div><span class="label">Admin pembuat laporan</span> : {{ $adminName ?? 'HRD System' }}</div>
        <div><span class="label">tanggal</span> : {{ date('d F Y') }}</div>
        <div><span class="label">jenis Pekerjaan</span> : {{ $posting->title }}</div>
        <div><span class="label">Jumlah Pelamar Kerja</span> : {{ $allApplications->count() }}</div>
    </div>

    <div class="section-title">Tabel perbandingan hasil profile matching</div>
    <table>
        <thead>
            <tr>
                <th>Nama Kandidat</th>
                <th>Nilai Akhir</th>
                <th>Persentase Cocok (Matching Score)</th>
                <th>Status Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allApplications as $app)
            @php
                $spkDetails = is_string($app->spk_details) ? json_decode($app->spk_details, true) : $app->spk_details;
                $nilaiAkhir = $spkDetails['nilai_akhir'] ?? 0;
                $matchingScore = $spkDetails['matching_score'] ?? 0;
                $statusText = $app->status === 'accepted' ? 'Diterima' : ($app->status === 'rejected' ? 'Ditolak' : 'Pending');
            @endphp
            <tr>
                <td>{{ $app->user->name }}</td>
                <td>{{ number_format($nilaiAkhir, 2) }}</td>
                <td>{{ number_format($matchingScore, 0) }}%</td>
                <td>{{ $statusText }}</td>
            </tr>
            @endforeach
            @if($allApplications->isEmpty())
            <tr>
                <td colspan="4" style="text-align:center">Belum ada pelamar.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">Kandidat prioritas vs non-prioritas dari hasil profile matching</div>
    <table class="two-cols">
        <thead>
            <tr>
                <th>kandidat prioritas</th>
                <th>kandidat non prioritas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul>
                        @foreach($priority as $cand)
                            <li>{{ $cand->user->name }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul>
                        @foreach($nonPriority as $cand)
                            <li>{{ $cand->user->name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            <tr class="total-row">
                <td>total kandidiat : {{ $priority->count() }}</td>
                <td>total kandidiat : {{ $nonPriority->count() }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">kandidat terpilih vs kandidat ditolak</div>
    <table class="two-cols">
        <thead>
            <tr>
                <th>kandidat terpilih</th>
                <th>kandidat tertolak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul>
                        @foreach($accepted as $cand)
                            <li>{{ $cand->user->name }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul>
                        @foreach($rejected as $cand)
                            <li>{{ $cand->user->name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            <tr class="total-row">
                <td>total kandidiat : {{ $accepted->count() }}</td>
                <td>total kandidiat : {{ $rejected->count() }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
