<?php

namespace App\Repositories\Profile;

use App\Exceptions\ApiException;
use App\Exceptions\ValidationException;
use App\Helpers\JwtHelper;
use App\Repositories\Contracts\ProfileInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileRepository implements ProfileInterface
{
    public function getProfile(): array
    {
        $sql = 'SELECT `USER_DETAIL`() AS result';
        $result = wdb($sql);
        $result['data']=json_decode($result['data'], true);
        if ($result['status']) {
            return [
                'status' => true,
                'code' => 200,
                'message' => 'fetched successfully',
                'data' => $result['data'],
            ];
        } else {
            throw new ApiException('fetched failed', 401);
        }
    }
}
