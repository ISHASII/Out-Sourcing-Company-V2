<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otpCode;
    public string $type;
    public string $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode, string $type, string $userName = 'Pelamar')
    {
        $this->otpCode = $otpCode;
        $this->type = $type;
        $this->userName = $userName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'registration'
            ? 'Kode Verifikasi Akun — PT. Unggul Cipta Indah'
            : 'Kode Reset Password — PT. Unggul Cipta Indah';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }
}
