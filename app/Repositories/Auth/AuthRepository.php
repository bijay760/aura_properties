<?php

namespace App\Repositories\Auth;

use App\Helpers\Auth;
use App\Helpers\Encryption;
use App\Repositories\Contracts\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthInterface
{
    private $jwt;

    /**
     * @var Encryption
     */
    private $encryption;

    private function authorize(array $user): string
    {
        return $this->jwt->signIn([
            $user['id'],
            $user['phone'],
            $user['session_id']
        ]);
    }

    private function isOtpExpired($otpRecord)
    {
        return now()->greaterThan($otpRecord->expires_at);
    }

    public function __construct(Auth $jwt, Encryption $encryption)
    {
        $this->jwt = $jwt;
        $this->encryption = $encryption;
    }

    public function login(Request $request)
    {

        $identifier = DB::table('users')->where('email', '=', $request->identifier)->orWhere('phone', '=', $request->identifier)->first();
        if (!$identifier) {
            return [
                'code' => 404,
                'status' => false,
                'data' => [],
                'message' => 'User not found'
            ];
        }

        $existingOtp = DB::table('otps')
            ->where('phone', $identifier->phone)
            ->where('type', 'login')
            ->where('expires_at', '>', now())
            ->where('verified', '=', false)->first();
        if ($existingOtp) {
            return [
                'code' => 200,
                'message' => 'OTP send successfully',
                'data' => [],
                'status' => true
            ];
        } else {
            $otp = 123456;
//            send otp here
            DB::table('otps')->updateOrInsert(
                ['phone' => $identifier->phone, 'type' => 'login'],
                [
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(5),
                    'attempts' => 0,
                    'verified' => false,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'updated_at' => now(),
                    'created_at' => $existingOtp->created_at ?? now(),
                ]
            );
            return [
                'code' => 200,
                'message' => 'OTP send successfully',
                'data' => [],
                'status' => true
            ];
        }

    }

    public function login_confirmation(Request $request)
    {
        if ($request->type == 'login') {
            $identifier = DB::table('users')->where('email', '=', $request->identifier)->orWhere('phone', '=', $request->identifier)->first();
            $otpRecord = DB::table('otps')
                ->where('phone', $identifier->phone)
                ->where('type', 'login')
                ->first();

            if (!$otpRecord) {
                return [
                    'code' => 400,
                    'message' => 'invalid otp',
                    'data' => [],
                    'status' => false
                ];
            }

            if ($this->isOtpExpired($otpRecord)) {
                return [
                    'code' => 410,
                    'message' => 'OTP has expired',
                    'data' => [],
                    'status' => false
                ];
            }

            if ($otpRecord->otp != $request->otp) {
                DB::table('otps')
                    ->where('phone', $identifier->phone)
                    ->where('type', 'login')
                    ->increment('attempts');

                return [
                    'code' => 401,
                    'message' => 'Invalid OTP',
                    'data' => [],
                    'status' => false
                ];
            }
            if ($otpRecord->verified) {
                return [
                    'code' => 401,
                    'message' => 'Invalid OTP',
                    'data' => [],
                    'status' => false
                ];
            }
            // Mark as verified
            DB::table('otps')
                ->where('phone', $identifier->phone)
                ->where('type', 'login')
                ->update([
                    'verified' => true,
                    'updated_at' => now()
                ]);
            $session_id = Str::uuid()->toString();

            $user = (array)DB::table('users')->select(['id','name','email','type','phone','email_verified_at'])->where('phone', $identifier->phone)->first();
            $user['session_id'] = $session_id;
            $data = ['session_id' => $session_id,
                'id'=>Str::uuid()->toString(),
                'last_activity' => 1,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payload' => json_encode($user),
                'user_id' => $user['id']];
            DB::table('sessions')->updateOrInsert($data);
            $access_token = $this->authorize($user);
            unset($user['session_id']);
            $user['access_token'] = $access_token;
            $mailingdata=[
                'name'=>$user['name'],
                'email'=>$user['email'],
                'login_at'=>now(),
            ];
//            dispatchNotification($mailingdata,'LoginAlert');
            return [
                'code' => 200,
                'message' => 'Login successfully',
                'data' => $user,
                'status' => true
            ];

        } else {
            return [
                'code' => 400,
                'status' => false,
                'data' => [],
                'message' => 'Invalid type'
            ];
        }
    }
}
