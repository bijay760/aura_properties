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
        if ($result['status']) {
            return [
                'status' => $result['status'],
                'code' => $result['code'],
                'message' => $result['message'],
                'data' => $result['data'],
            ];
        } else {
            throw new ApiException($result['message'], $result['code']);
        }
    }
}
