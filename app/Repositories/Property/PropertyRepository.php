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
            if ($request->property_category_id == 1) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'floor_count' => $request->floor_count,
                        'total_floors' => $request->total_floors,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            } elseif ($request->property_category_id == 3) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'floor_count' => $request->floor_count,
                        'total_floors' => $request->total_floors,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
                        'number_of_open_side' => $request->number_of_open_side,
                        'width_of_road_facing_plot' => $request->width_of_road_facing_plot,
                        'floor_allowed_for_construction' => $request->floor_allowed_for_construction,
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
            } elseif ($request->property_category_id == 2) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'floor_count' => $request->floor_count,
                        'total_floors' => $request->total_floors,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
                        'number_of_open_side' => $request->number_of_open_side,
                        'width_of_road_facing_plot' => $request->width_of_road_facing_plot,
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
            } elseif ($request->property_category_id == 4) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'floor_count' => $request->floor_count,
                        'total_floors' => $request->total_floors,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            } elseif ($request->property_category_id == 5) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'floor_allowed_for_construction'=>$request->floor_allowed_for_construction,
                        'boundary_wall_made'=>$request->boundary_wall_made,
                        'number_of_open_side'=>$request->number_of_open_side,
                        'any_construction_done'=>$request->any_construction_done,
                        'is_colony_have_gate'=>$request->is_colony_have_gate,
                        'width_of_road_facing_plot'=>$request->width_of_road_facing_plot,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            } elseif ($request->property_category_id == 6 || $request->property_category_id==7) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'floor_count' => $request->floor_count,
                        'total_floors' => $request->total_floors,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            }elseif ($request->property_category_id == 8 ||$request->property_category_id == 9) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'address' => $request->address,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'floor_count' => $request->floor_count,
                        'total_floors' => $request->total_floors,
                        'pantry_cafeteria' => $request->pantry_cafeteria,
                        'personal_washroom' => $request->personal_washroom,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            }elseif ($request->property_category_id == 11) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'total_numbers' => $request->total_numbers,
                        'near_by_business'=>$request->near_by_business,
                        'floor_allowed_for_construction'=>$request->floor_allowed_for_construction,
                        'number_of_open_side'=>$request->number_of_open_side,
                        'width_of_road_facing_plot'=>$request->width_of_road_facing_plot,
                        'boundary_wall_made'=>$request->boundary_wall_made,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            }elseif ($request->property_category_id == 12) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'total_numbers' => $request->total_numbers,
                        'near_by_business'=>$request->near_by_business,
                        'floor_allowed_for_construction'=>$request->floor_allowed_for_construction,
                        'number_of_open_side'=>$request->number_of_open_side,
                        'any_construction_done'=>$request->any_construction_done,
                        'width_of_road_facing_plot'=>$request->width_of_road_facing_plot,
                        'boundary_wall_made'=>$request->boundary_wall_made,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            }elseif ($request->property_category_id == 13) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'total_numbers' => $request->total_numbers,
                        'total_floors' => $request->total_floors,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            }elseif ($request->property_category_id == 14) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'total_numbers' => $request->total_numbers,
                        'boundary_wall_made' => $request->boundary_wall_made,
                        'number_of_open_side' => $request->number_of_open_side,
                        'width_of_road_facing_plot' => $request->width_of_road_facing_plot,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            }elseif ($request->property_category_id == 15) {
                $uploadedFiles = uploadFiles($request->file('feature_images'), 'uploads/gallery');
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
                        'project_name'=>$request->project_name,
                        'total_numbers' => $request->total_numbers,
                        'bedrooms_count' => $request->bedrooms_count,
                        'bathroom_count' => $request->bathrooms_count,
                        'balcony_count' => $request->balcony_count,
                        'is_furnishing' => $request->is_furnishing,
                        'number_of_open_side' => $request->number_of_open_side,
                        'width_of_road_facing_plot' => $request->width_of_road_facing_plot,
                        'additional_rooms' => json_encode($request->additional_rooms),
                        'overlooking' => json_encode($request->overlooking),
                        'directional_facing' => $request->directional_facing,
                        'ownership_type' => $request->ownership_type,
                        'more_property_details' => $request->more_property_details,
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
            }else {
                return [
                    'code' => 400,
                    'status' => false,
                    'message' => 'Invalid property category.',
                    'data' => []
                ];
            }
        } catch (\Exception $e) {
            return ['code' => 500,
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []];
        }
    }

    public function getProperty(Request $request): array
    {
        $listing_type=$request->listing_type??'sale';
        $property_category_id = ($request->property_category_id !== null && $request->property_category_id !== '') ? $request->property_category_id : 0;
        $status=$request->status??'active';
        $data=[
            'listing_type' => $listing_type,
            'property_category_id' => $property_category_id,
            'status' => $status,
        ];
        $params=['json'=>json_encode($data)];
        $sql = 'CALL GET_PROPERTIES_MINE(:json)';
        $result = wdb($sql,$params);
        return [
            'code' => 200,
            'data' => $result,
            'message' => 'Fetched successfully',
            'status' => true
        ];
    }
}
