<?php

namespace app\Repositories\Register;

use App\Exceptions\ApiException;
use App\Exceptions\RegisterException;
use App\Exceptions\ValidationException;
use App\Repositories\Contracts\RegisterInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class RegisterRepository implements RegisterInterface
{
    /**
     * @throws RegisterException
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        // Check if token is valid
        $tokenExists = DB::table('otp_verify_tokens')
            ->where('phone', $request->phone)
            ->where('token', $request->token)
            ->exists();

        if (!$tokenExists) {
            return [
                'code' => 404,
                'message' => 'Invalid token',
                'data' => [],
                'status' => false
            ];
        }

        // Check if phone already exists
        if (DB::table('users')->where('phone', $request->phone)->exists()) {
            return [
                'code' => 409,
                'message' => 'Phone number already exists',
                'data' => [],
                'status' => false
            ];
        }

        // Check if email already exists
        if (DB::table('users')->where('email', $request->email)->exists()) {
            return [
                'code' => 409,
                'message' => 'Email already exists',
                'data' => [],
                'status' => false
            ];
        }

        // Register the user
        DB::table('users')->insert([
            'name' => $request->full_name,
            'email' => $request->email,
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make($request->password),
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => $request->type,
            'phone' => $request->phone,
        ]);

        return [
            'code' => 200,
            'message' => 'Registration successful',
            'data' => [],
            'status' => true
        ];
    }
}
