<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Hasil_SPK_{{ str_replace(' ', '_', $application->user->name) }}_{{ date('Ymd_His') }}</title>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;850&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Kop Surat */
        .header-kop {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #003d7c;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }
        .header-kop img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-right: 15px;
        }
        .header-kop .company-details {
            flex-grow: 1;
        }
        .header-kop h1 {
            font-size: 16px;
            font-weight: 850;
            color: #003d7c;
            margin: 0 0 2px 0;
            letter-spacing: -0.5px;
        }
        .header-kop p {
            font-size: 9px;
            color: #64748b;
            margin: 0;
            font-weight: 500;
        }

        /* Document Title */
        .doc-title-container {
            text-align: center;
            margin-bottom: 12px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 850;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }
        .doc-subtitle {
            font-size: 10px;
            color: #475569;
            font-weight: 600;
            margin: 0;
        }

        /* Grid info */
        .section-title {
            font-size: 10px;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #003d7c;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-bottom: 8px;
            margin-top: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 12px;
        }

        .info-card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 8px 12px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
            font-size: 10px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            width: 100px;
            color: #64748b;
            font-weight: 500;
            flex-shrink: 0;
        }
        .info-value {
            color: #1e293b;
            font-weight: 700;
        }

        /* Table Calculations */
        table.spk-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 10px;
        }
        table.spk-table th {
            background-color: #003d7c;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
            padding: 6px 10px;
            text-align: left;
            border: 1px solid #003d7c;
        }
        table.spk-table td {
            padding: 5px 10px;
            border: 1px solid #e2e8f0;
            color: #334155;
        }
        table.spk-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .badge-core {
            font-weight: 700;
            color: #ef4444;
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            padding: 1px 4px;
            border-radius: 4px;
            font-size: 8px;
            text-transform: uppercase;
        }
        .badge-secondary {
            font-weight: 700;
            color: #3b82f6;
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            padding: 1px 4px;
            border-radius: 4px;
            font-size: 8px;
            text-transform: uppercase;
        }

        /* Summary box (Minimalist & Compact Layout) */
        .summary-wrapper {
            margin-top: 15px;
            display: grid;
            grid-template-cols: 1fr 1fr;
            gap: 15px;
            page-break-inside: avoid;
        }

        .summary-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .score-card {
            background-color: #003d7c;
            color: #ffffff;
            border-radius: 10px;
            padding: 10px 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .score-value {
            font-size: 28px;
            font-weight: 850;
            line-height: 1;
            margin: 3px 0;
            color: #ffffff;
        }
        .score-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }

        /* Printing elements hiding */
        .btn-print-container {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .btn-print {
            background-color: #003d7c;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 11px;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover {
            background-color: #002d5c;
            transform: translateY(-1px);
        }

        @media print {
            .btn-print-container {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Print Button Header (Invisible when printed) -->
        <div class="btn-print-container">
            <button onclick="window.print()" class="btn-print">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.615 0-1.101-.476-1.12-1.08L5.82 18m11.84 0h-11.84m12.48-5.323a1.947 1.947 0 00-2.38-1.947h-6.562a1.947 1.947 0 00-2.38 1.947m11.322 0A1.947 1.947 0 0119.5 13.5v3.11a1.947 1.947 0 01-1.84 1.947m-11.322 0A1.947 1.947 0 004.5 16.61v-3.11c0-.88.667-1.63 1.522-1.752z"></path>
                </svg>
                Cetak Laporan
            </button>
        </div>

        <!-- Kop Surat -->
        <div class="header-kop">
            <img src="{{ asset('image/LOGO UCI.jpeg') }}" alt="Logo PT UCI">
            <div class="company-details">
                <h1>PT. UNGGUL CIPTA INDAH</h1>
                <p>Ruko Kranggan Permai, Jl. Raya Kranggan, RT.015/RW.015, Jatisampurna, Kec. Jatisampurna, Kota Bks, Jawa Barat 17433
</p>
                <p>Telp: (021) 813-1555-2926 | Email: unggulcuptaindah@gmail.com </p>
            </div>
        </div>

        <!-- Title -->
        <div class="doc-title-container">
            <h2 class="doc-title">Laporan Analisis Penilaian SPK Pelamar</h2>
            <p class="doc-subtitle">Metode Profile Matching (GAP Analysis)</p>
        </div>

        <!-- Section: Data Pelamar -->
        <div class="section-title">Data Profil & Lowongan Pekerjaan</div>
        <div class="info-grid">
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Nama Pelamar</span>
                    <span class="info-value">: {{ $application->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">: {{ $application->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telepon</span>
                    <span class="info-value">: {{ $profile->phone ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">: {{ $application->gender === 'male' ? 'Pria' : 'Wanita' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Usia</span>
                    <span class="info-value">: {{ $application->age ?? '-' }} Tahun</span>
                </div>
            </div>
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Posisi Dilamar</span>
                    <span class="info-value">: {{ $posting->title }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kategori Pekerjaan</span>
                    <span class="info-value">: {{ $posting->category }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Melamar</span>
                    <span class="info-value">: {{ $application->created_at->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Klasifikasi</span>
                    <span class="info-value">: {{ $application->is_priority ? 'Prioritas' : 'Tidak prioritas' }}</span>
                </div>
            </div>
        </div>

        <!-- Section: Detail GAP Analysis -->
        <div class="section-title">Rincian Perhitungan SPK (Profile Matching)</div>
        <table class="spk-table">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 45%">Kriteria / Parameter Kualifikasi</th>
                    <th style="width: 15%">Jenis Faktor</th>
                    <th style="text-align: center; width: 10%">Target (Ideal)</th>
                    <th style="text-align: center; width: 10%">Kandidat</th>
                    <th style="text-align: center; width: 8%">GAP</th>
                    <th style="text-align: center; width: 7%">Bobot</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calculationDetails as $idx => $detail)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-weight: 600;">{{ $detail['criteria'] }}</td>
                        <td>
                            @if($detail['factor_type'] === 'Core Factor')
                                <span class="badge-core">Core</span>
                            @else
                                <span class="badge-secondary">Secondary</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: 700;">{{ $detail['target'] }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $detail['candidate'] }}</td>
                        <td style="text-align: center; font-weight: 700; color: {{ $detail['gap'] < 0 ? '#ef4444' : ($detail['gap'] > 0 ? '#3b82f6' : '#10b981') }}">
                            {{ $detail['gap'] > 0 ? '+' . $detail['gap'] : $detail['gap'] }}
                        </td>
                        <td style="text-align: center; font-weight: 800; background-color: #f8fafc;">{{ number_format($detail['weight'], 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary section (Minimalist & Clean) -->
        <div class="summary-wrapper">
            <div class="summary-card">
                <div style="font-weight: 850; color: #003d7c; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; margin-bottom: 5px;">Ringkasan Nilai Aspek</div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="color: #64748b; font-weight: 500;">Rata-rata Core Factor (NCF):</span>
                    <strong style="color: #ef4444; font-weight: 700;">{{ number_format($ncf, 2) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                    <span style="color: #64748b; font-weight: 500;">Rata-rata Secondary Factor (NSF):</span>
                    <strong style="color: #3b82f6; font-weight: 700;">{{ number_format($nsf, 2) }}</strong>
                </div>
                <div style="margin-top: 4px; padding-top: 4px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #475569; font-style: italic; font-weight: 500;">
                    Formula Penilaian: Nilai Akhir = (60% x NCF) + (40% x NSF)
                </div>
            </div>
            
            <div class="score-card">
                <span class="score-label">Skor Akhir SPK</span>
                <span class="score-value">{{ $calculatedScore }}%</span>
                <span class="score-label">Tingkat Kelayakan</span>
            </div>
        </div>
    </div>

    <!-- Auto-print trigger -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
