<?php

namespace app\Repositories\Content;

use App\Exceptions\RegisterException;
use App\Exceptions\ValidationException;
use App\Repositories\Contracts\ContentInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ContentRepository implements ContentInterface
{
    private function isOtpExpired($otpRecord)
    {
        return now()->greaterThan($otpRecord->expires_at);
    }

    /**
     * @throws RegisterException
     * @throws ValidationException
     */
    public function SendOTP(Request $request)
    {
        $phone = $request->phone;
        $type = $request->type;
        $existingOtp = DB::table('otps')
            ->where('phone', $phone)
            ->where('type', $type)
            ->where('expires_at', '>', now())
            ->where('verified', '=', false)->first();
        if ($existingOtp && !$this->isOtpExpired($existingOtp)) {
            return [
                'code' => 200,
                'message' => 'OTP send successfully',
                'data' => [],
                'status' => true
            ];
        }
        $otp = 123456;
        DB::table('otps')->updateOrInsert(
            ['phone' => $phone, 'type' => $type],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(1),
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
            'message' => 'OTP sent successfully',
            'data' => [],
            'status' => true
        ];
    }

    public function verifyOTP(Request $request)
    {
        $phone = $request->phone;
        $type = $request->type;
        $otpInput = $request->otp;

        $otpRecord = DB::table('otps')
            ->where('phone', $phone)
            ->where('type', $type)
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

        if ($otpRecord->otp != $otpInput) {
            DB::table('otps')
                ->where('phone', $phone)
                ->where('type', $type)
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
            ->where('phone', $phone)
            ->where('type', $type)
            ->update([
                'verified' => true,
                'updated_at' => now()
            ]);

        // Generate token
        $token = Str::random(64);
        DB::table('otp_verify_tokens')->insert([
            'phone' => $phone,
            'token' => $token,
            'verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'code' => 200,
            'message' => 'OTP verified successfully',
            'data' => [
                'token' => $token,
                'phone' => $phone
            ],
            'status' => true
        ];
    }

    public function addCities(Request $request): array
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:64|unique:cities,name',
            ],
            [
                'name.required' => 'The city name is required.',
                'name.string' => 'The city name must be a valid string.',
                'name.max' => 'The city name must not exceed 64 characters.',
                'name.unique' => 'This city already exists in the system.',
            ]
        );
        $cityId = DB::table('cities')->insertGetId([
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return [
            'code' => 200,
            'message' => 'City added successfully',
            'data' => ['id' => $cityId, 'is_active' => 1,
                'name' => $validated['name']],
            'status' => true
        ];
    }

    public function getCities(Request $request): array
    {
        $page = (int)($request->input('page', 1));
        $limit = (int)($request->input('limit', 10));
        $search = $request->input('params'); // Search keyword

        $query = DB::table('cities')
            ->select(['id', 'name', 'is_active', 'created_at'])
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"));

        $total = $query->cloneWithout(['orders', 'limit', 'offset'])->count();

        $cities = $query
            ->orderBy('name')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        return [
            'status' => true,
            'code' => 200,
            'message' => 'Data fetched successfully',
            'data' => [
                'cities' => $cities,
                'total' => $total,
                'per_page' => $limit,
                'page' => $page,
            ],
        ];
    }

    public function addLocality(Request $request): array
    {
        $validated = $request->validate([
            'city_id' => [
                'required',
                'integer',
                Rule::exists('cities', 'id')->where('is_active', 1)
            ],
            'name' => [
                'required',
                'string',
                'max:64',
                Rule::unique('localities', 'name')
                    ->where('city_id', $request->city_id)
            ],
        ]);

        $locality_id = DB::table('localities')->insertGetId([
            'city_id' => $validated['city_id'],
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return [
            'code' => 200,
            'message' => 'City added successfully',
            'data' => ['id' => $locality_id, 'is_active' => 1,
                'name' => $validated['name']],
            'status' => true
        ];
    }

    public function getLocality(Request $request): array
    {
        $page = (int)($request->input('page', 1));
        $limit = (int)($request->input('limit', 10));
        $city_id = $request->input('city_id');
        $search = $request->input('params'); // Search keyword

        if($city_id) {
            $query = DB::table('localities')
                ->select(['id', 'name', 'is_active', 'created_at'])
                ->where('city_id', $city_id)
                ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"));

            $total = $query->cloneWithout(['orders', 'limit', 'offset'])->count();

            $cities = $query
                ->orderBy('name')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            return [
                'status' => true,
                'code' => 200,
                'message' => 'Data fetched successfully',
                'data' => [
                    'localities' => $cities,
                    'total' => $total,
                    'per_page' => $limit,
                    'page' => $page,
                ],
            ];
        }else{
            return [
                'status' => false,
                'code' => 401,
                'message' => 'Invalid city',
                'data' => [
                ],
            ];
        }


    }

    public function addProject(Request $request): array
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:64',
                Rule::unique('projects', 'name')
                    ->where('locality_id', $request->locality_id)
            ],
            'locality_id' => 'required|integer|exists:localities,id',
        ]);

        $project_id = DB::table('projects')->insertGetId([
            'locality_id' => $validated['locality_id'],
            'name' => $validated['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return [
            'code' => 200,
            'message' => 'City added successfully',
            'data' => ['id' => $project_id, 'is_active' => 1,
                'name' => $validated['name']],
            'status' => true
        ];

    }
    public function getProjects(Request $request): array
    {$page = (int)($request->input('page', 1));
        $limit = (int)($request->input('limit', 10));
        $locality_id = $request->input('locality_id');
        $search = $request->input('params'); // Search keyword

        if($locality_id) {
            $query = DB::table('projects')
                ->select(['id', 'name', 'is_active', 'created_at'])
                ->where('locality_id', $locality_id)
                ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"));

            $total = $query->cloneWithout(['orders', 'limit', 'offset'])->count();

            $cities = $query
                ->orderBy('name')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            return [
                'status' => true,
                'code' => 200,
                'message' => 'Data fetched successfully',
                'data' => [
                    'projects' => $cities,
                    'total' => $total,
                    'per_page' => $limit,
                    'page' => $page,
                ],
            ];
        }else{
            return [
                'status' => false,
                'code' => 401,
                'message' => 'Invalid locality',
                'data' => [
                ],
            ];
        }

    }

}
