<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
   public function index(Request $request)
{
    $id = $request->input('id');
    $email = $request->input('email');
    $name = $request->input('name');
    $type = $request->input('type');
    $createdAt = $request->input('created_at');
    $updatedAt = $request->input('updated_at');
    $perPage = $request->input('per_page', 3);

    $query = DB::table('users');
    if ($id) {
        $query->where('users.id', $id);
    }

    if ($email) {
        $query->where('users.email', 'like', "%$email%");
    }
    if ($name) {
        $query->where('users.name', 'like', "%$name%");
    }
    if ($type) {
        $query->where('users.type', 'like', "%$type%");
    }
    if ($createdAt) {
        $query->whereDate('users.created_at', $createdAt);
    }

    $query->orderBy('users.created_at', 'desc');
    $users = $query->paginate($perPage);

    return view('admin.user.index', compact('users'));
}
    public function create()
    {
        
        return view('admin.user.create');
    }
public function store(Request $request)
{
   $data = $request->validate([
    'email' => 'required|email|unique:users,email',
    'name' => 'required|string|max:255',
    'type' => 'required|string|max:255',
    'password' => 'required|string|min:6',
]);

    DB::table('users')->insert($data);

    return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
}
  public function edit($id)
{
    $user = DB::table('users')->find($id);
return view('admin.user.edit',compact('user'));
}

public function update(Request $request, $id)
{
    $data = $request->validate([
        'email' => 'required|email|unique:users,email,' . $id,
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'password' => 'nullable|string|min:6',
    ]);

    if (empty($data['password'])) {
        unset($data['password']);
    }

    DB::table('users')->where('id', $id)->update($data);

    return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
}

    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully.');
    }
}
