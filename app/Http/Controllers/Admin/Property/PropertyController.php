<?php

namespace App\Http\Controllers\Admin\property;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
public function index(Request $request)
{
    $id = $request->input('id');
    $email = $request->input('email');
    $categoryId = $request->input('category_id');
    $listingType = $request->input('listing_type');
    $perPage = $request->input('per_page', 3);

    $query = DB::table('properties')
        ->leftJoin('users', 'properties.user_id', '=', 'users.id')
        ->leftJoin('property_categories', 'properties.property_category_id', '=', 'property_categories.id')
        ->select(
            'properties.*',
            DB::raw("COALESCE(users.name, 'Not found') as user_name"),
            DB::raw("COALESCE(users.email, 'Not found') as user_email"),
            DB::raw("COALESCE(users.phone, 'Not found') as user_contact"),
            DB::raw("COALESCE(property_categories.name, 'Not found') as category_name")
        );

    if ($id) {
        $query->where('properties.id', $id);
    }

    if ($email) {
        $query->where('users.email', 'like', "%$email%");
    }
    if ($categoryId) {
    $query->where('properties.property_category_id', $categoryId);
}
if ($listingType) {
    $query->where('properties.listing_type', $listingType);
}

    $properties = $query->paginate($perPage);
    $categories = DB::table('property_categories')->get();

    return view('admin.property.index', compact('properties','categories'));
}
      public function create()
    {
        return view('admin.property.create');
    }
}
