<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
   public function index(Request $request)
{
    $id = $request->input('id');
    $name = $request->input('name');
    $status = $request->input('status');
    $perPage = $request->input('per_page', 10);

    $query = DB::table('property_categories');

    if ($id) {
        $query->where('id', $id);
    }

    if ($name) {
        $query->where('name', 'like', "%$name%");
    }

  if ($status !== null) {
    $query->where('status', $status);
}

    $categories = $query->orderByDesc('id')->paginate($perPage)->appends($request->all());

    return view('admin.categories.index', compact('categories'));
}
    public function create()
     {
          return view('admin.categories.create');
     }
    
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'status' => 'required|boolean',
    ]);

    $sortOrder = DB::table('property_categories')->max('sort_order') + 1;

    DB::table('property_categories')->insert([
        'name' => $request->input('name'),
        'status' => $request->input('status'),
        'sort_order' => $sortOrder,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
}
public function edit()
{
    $categories = DB::table('property_categories')->orderBy('sort_order')->get();

    if ($categories->isEmpty()) {
        abort(404);
    }

    return view('admin.categories.edit', compact('categories'));
}
public function update(Request $request)
{
    $data = $request->input('categories', []);

    foreach ($data as $id => $fields) {
        DB::table('property_categories')
            ->where('id', $id)
            ->update([
                'name' => $fields['name'] ?? null,
                'status' => isset($fields['status']) ? (int)$fields['status'] : 0,
                'sort_order' => $fields['order'] ?? null,
            ]);
    }

    return redirect()->route('admin.categories.index')->with('success', 'Categories updated successfully.');
}
public function destroy($id)
{
    $linked = DB::table('properties')->where('category_id', $id)->exists();

    if ($linked) {
        return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category linked to properties.');
    }

    DB::table('property_categories')->where('id', $id)->delete();

    return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
}

}
