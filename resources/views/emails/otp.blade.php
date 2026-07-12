<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP - PT. Unggul Cipta Indah</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" style="width: 100%; max-width: 520px; border-collapse: collapse; background: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0, 40, 85, 0.12); overflow: hidden;">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #002855 0%, #003d7c 50%, #0056a8 100%); padding: 32px 40px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; margin: 0 0 6px 0; letter-spacing: -0.3px;">
                                PT. Unggul Cipta Indah
                            </h1>
                            <p style="color: rgba(255,255,255,0.75); font-size: 13px; margin: 0;">
                                Outsourcing & Recruitment Solutions
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 36px 40px 20px;">
                            <p style="color: #1a2b3c; font-size: 15px; line-height: 1.6; margin: 0 0 8px 0;">
                                Halo <strong>{{ $userName }}</strong>,
                            </p>
                            <p style="color: #4a5568; font-size: 14px; line-height: 1.7; margin: 0 0 28px 0;">
                                @if($type === 'registration')
                                    Terima kasih telah mendaftar di PT. Unggul Cipta Indah. Untuk menyelesaikan proses pendaftaran, silakan masukkan kode verifikasi berikut:
                                @else
                                    Anda telah meminta untuk mereset password akun Anda. Silakan masukkan kode verifikasi berikut untuk melanjutkan:
                                @endif
                            </p>
                        </td>
                    </tr>

                    {{-- OTP Code Box --}}
                    <tr>
                        <td style="padding: 0 40px 28px;" align="center">
                            <table role="presentation" style="border-collapse: collapse;">
                                <tr>
                                    <td style="background: linear-gradient(135deg, #f8fafc, #eef2f7); border: 2px dashed #003d7c; border-radius: 12px; padding: 20px 40px; text-align: center;">
                                        <p style="color: #64748b; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 8px 0;">Kode Verifikasi</p>
                                        <p style="color: #002855; font-size: 36px; font-weight: 800; letter-spacing: 10px; margin: 0; font-family: 'Courier New', monospace;">
                                            {{ $otpCode }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Warning --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0;">
                                <tr>
                                    <td style="padding: 14px 16px;">
                                        <p style="color: #92400e; font-size: 12px; line-height: 1.6; margin: 0;">
                                            Kode ini berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini kepada siapapun termasuk pihak yang mengatasnamakan PT. Unggul Cipta Indah.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="color: #94a3b8; font-size: 11px; line-height: 1.6; margin: 0;">
                                Email ini dikirim secara otomatis. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
                            </p>
                            <p style="color: #cbd5e1; font-size: 10px; margin: 12px 0 0 0;">
                                &copy; {{ date('Y') }} PT. Unggul Cipta Indah. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
