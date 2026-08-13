<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Wawancara PT. UCI</title>
    <style>
        body { font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333333; background-color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header { background-color: #003d7c; color: white; padding: 30px 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .content { padding: 40px; }
        .greeting { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #1e293b; }
        .message-box { background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 20px; rounded: 8px; margin-bottom: 25px; border-radius: 8px;}
        .message-box p { margin: 0; color: #166534; font-weight: 500;}
        .job-details { background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .job-details h3 { margin-top: 0; margin-bottom: 10px; color: #0f172a; font-size: 16px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;}
        .job-details ul { margin: 0; padding-left: 20px; color: #475569; font-size: 14px;}
        .job-details li { margin-bottom: 5px; }
        .footer { background-color: #f8fafc; padding: 20px 40px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #003d7c; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PT. Unggul Cipta Indah</h1>
        </div>
        <div class="content">
            <div class="greeting">Halo, {{ $application->user->name }}!</div>
            
            <div class="message-box">
                <p>Selamat! Lamaran Anda telah <strong>DITERIMA</strong> dan Anda dinyatakan lolos seleksi berkas administrasi.</p>
            </div>

            <p>Terima kasih atas ketertarikan Anda untuk bergabung bersama tim kami. Kami mengundang Anda untuk lanjut ke tahap <strong>Wawancara (Interview)</strong> untuk posisi berikut:</p>
            
            <div class="job-details">
                <h3>Detail Posisi</h3>
                <ul>
                    <li><strong>Posisi:</strong> {{ $application->posting->title }}</li>
                    <li><strong>Kategori:</strong> {{ $application->posting->category }}</li>
                </ul>
            </div>

            <p>Tim HRD kami akan segera menghubungi Anda melalui Nomor Telepon / WhatsApp atau Email ini untuk menginformasikan jadwal dan teknis wawancara lebih lanjut.</p>
            
            <p>Silakan pantau terus dashboard pelamar Anda secara berkala melalui tombol di bawah ini:</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('login') }}" class="btn">Masuk ke Portal Pelamar</a>
            </div>

            <p style="margin-top: 30px; font-size: 14px;">Salam hangat,<br><strong>Tim Rekrutmen PT. Unggul Cipta Indah</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} PT. Unggul Cipta Indah.<br>
            Email ini dibuat secara otomatis, mohon tidak membalas email ini secara langsung.
        </div>
    </div>
</body>
</html>
