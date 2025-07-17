<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OtpService
{
    const TEST_OTP = '123456';
    const OTP_EXPIRY_MINUTES = 5;

    public function generateOtp(Request $request, string $recipient, string $type)
    {
        $existingOtp = DB::table('otps')
            ->where('number', $recipient)
            ->where('type', $type)
            ->first();

        if ($existingOtp && !$this->isOtpExpired($existingOtp)) {
            return self::TEST_OTP;
        }

        DB::table('otps')->updateOrInsert(
            ['number' => $recipient, 'type' => $type],
            [
                'otp' => self::TEST_OTP,
                'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
                'attempts' => 0,
                'verified' => false,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'updated_at' => now(),
                'created_at' => $existingOtp->created_at?$existingOtp->created_at:now(),
            ]
        );

        return self::TEST_OTP;
    }

    public function verifyOtp(string $recipient, string $otp, string $type)
    {
        $record = DB::table('otps')
            ->where('number', $recipient)
            ->where('type', $type)
            ->first();

        if (!$record || $this->isOtpExpired($record)) {
            return false;
        }

        if ($record->otp === $otp) {
            DB::table('otps')
                ->where('id', $record->id)
                ->update(['verified' => true]);

            return true;
        }

        // Increment attempts on failure
        DB::table('otps')
            ->where('id', $record->id)
            ->increment('attempts');

        return false;
    }

    private function isOtpExpired($otpRecord)
    {
        return now()->greaterThan($otpRecord->expires_at);
    }
}
