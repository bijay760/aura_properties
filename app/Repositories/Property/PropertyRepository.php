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
            $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');

            if ($request->property_category_id == 1) {
                try {
                    $amenities = $request->amenities;
                    if (is_string($amenities)) {
                        $amenities = json_decode($amenities, true) ?? [];
                    }
                    $amenities = is_array($amenities) ? $amenities : [];

                    // Ensure gallery images is properly formatted
                    $galleryImages = $uploadedFiles;
                    if (is_string($galleryImages)) {
                        $galleryImages = json_decode($galleryImages, true) ?? [];
                    }
                    $galleryImages = is_array($galleryImages) ? $galleryImages : [];

                    $data = [
                        'user_id' => a_auth('user_id'),
                        'listing_type' => $request->listing_type,
                        'property_category_id' => $request->property_category_id,
                        'covered_area' => $request->covered_area,
                        'carpet_area' => $request->carpet_area,
                        'total_price' => $request->total_price,
                        'is_price_negotiable' => $request->is_price_negotiable,
                        'city' => $request->city,
                        'locality' => $request->locality,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'floor_count' => $request->floor_count,
                        'total_floors' => $request->total_floors,
                        'transaction_type' => $request->transaction_type,
                        'availability_status' => $request->availability_status,
                        'possession' => $request->possession,
                        'approved_by_bank' => $request->approved_by_bank,
                        'amenities' => json_encode($amenities),
                        'gallery_images' => json_encode($galleryImages),
                        'flooring_type' => $request->flooring_type,
                        'landmark' => $request->landmark,
                        'status' => 'active',
                        'created_at' => now()
                    ];
                    DB::table('properties')->insert($data);
                    return [
                        'code' => 200,
                        'status' => true,
                        'message' => 'Property created successfully',
                        'data' => []
                    ];
                } catch (\Throwable $e) {
                    return [
                        'code' => 500,
                        'status' => false,
                        'message' => 'Property creation failed: ' . $e->getMessage(),
                        'data' => []
                    ];
                }
            } else {
                return [
                    'code' => 400,
                    'status' => false,
                    'message' => 'Invalid property category.',
                    'data' => []
                ];
            }
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }


    public function getProperty(Request $request): array
    {

    }
}
