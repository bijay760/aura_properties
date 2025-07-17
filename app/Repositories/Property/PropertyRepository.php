<?php

namespace App\Repositories\Property;

use App\Exceptions\ApiException;
use App\Exceptions\ValidationException;
use App\Helpers\JwtHelper;
use App\Repositories\Contracts\ProfileInterface;
use App\Repositories\Contracts\PropertiesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PropertyRepository implements PropertiesInterface
{
    public function getCategories(Request $request): array
    {
        $sql = 'CALL sp_get_categories()';
        $result = wdb($sql);
        return [
            'code' => 200,
            'data' => $result,
            'message' => 'Fetched successfully',
            'status' => true
        ];
    }

    public function postProperty(Request $request): array
    {
        try {
            $uploadedFiles = uploadAndSanitizeImage($request->file('feature_images'), 'public/uploads/gallery', [
                'max_size' => 5120, // 5MB
                'resize' => ['width' => 1200] // Resize to max width 1200px, maintaining aspect ratio
            ]);
            dd($uploadedFiles);
        } catch (\Exception $e) {
            // Handle error
            dd($e->getMessage());
        }
    }
}
