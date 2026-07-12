<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'type',
        'expires_at',
        'used',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used' => 'boolean',
        ];
    }

    /**
     * Check if the OTP is still valid (not expired and not used).
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    /**
     * Mark this OTP as used.
     */
    public function markUsed(): void
    {
        $this->update(['used' => true]);
    }

    /**
     * Generate a new 6-digit OTP for the given email and type.
     * Invalidates all previous unused OTPs for the same email+type.
     */
    public static function generate(string $email, string $type): self
    {
        // Invalidate all previous unused OTPs for this email+type
        static::where('email', $email)
            ->where('type', $type)
            ->where('used', false)
            ->update(['used' => true]);

        return static::create([
            'email' => $email,
            'code' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
    }

    /**
     * Verify an OTP code for the given email and type.
     * Returns the OtpCode instance if valid, null otherwise.
     */
    public static function verify(string $email, string $code, string $type): ?self
    {
        $otp = static::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->where('used', false)
            ->latest()
            ->first();

        if ($otp && $otp->isValid()) {
            return $otp;
        }

        return null;
    }
}
