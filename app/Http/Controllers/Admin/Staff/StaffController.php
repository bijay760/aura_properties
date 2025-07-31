<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
   public function index(Request $request)
{
    $email = $request->input('email');
    $role = $request->input('role');
    $firstName = $request->input('first_name');
    $surname = $request->input('surname');
    $createdAt = $request->input('created_at');
    $perPage = $request->input('per_page', 3);

    $query = DB::table('admin_users')
        ->leftJoin('roles', 'admin_users.role_id', '=', 'roles.id')
        ->select('admin_users.*', 'roles.name as role');

    if ($email) {
        $query->where('admin_users.email', 'like', "%$email%");
    }
    if ($firstName) {
        $query->where('admin_users.first_name', 'like', "%$firstName%");
    }
    if ($surname) {
        $query->where('admin_users.surname', 'like', "%$surname%");
    }
    if ($role) {
        $query->where('admin_users.role_id', $role);
    }
    if ($createdAt) {
        $query->whereDate('admin_users.created_at', $createdAt);
    }

    $query->orderBy('admin_users.created_at', 'desc');
    $staffs = $query->paginate($perPage);
    $rolesList = DB::table('roles')->get();

    return view('admin.staff.index', compact('staffs', 'rolesList'));
}
    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('admin.staff.create', compact('roles'));
    }
public function store(Request $request)
{
   $data = $request->validate([
    'email' => 'required|email|unique:admin_users,email',
    'first_name' => 'required|string|max:255',
    'surname' => 'required|string|max:255',
    'password' => 'required|string|min:6',
    'role_id' => 'nullable|exists:roles,id',
]);


    DB::table('admin_users')->insert($data);

    return redirect()->route('admin.staff.index')->with('success', 'User created successfully.');
}
  public function edit($id)
{
    $user = DB::table('admin_users')->find($id);
    $roles = DB::table('roles')->get();
return view('admin.staff.edit', compact('user', 'roles'));
}

public function update(Request $request, $id)
{
    $data = $request->validate([
        'email' => 'required|email|unique:admin_users,email,' . $id,
        'first_name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'role_id' => 'nullable|exists:roles,id',
        'password' => 'nullable|string|min:6',
    ]);

    if (empty($data['password'])) {
        unset($data['password']);
    }

    DB::table('admin_users')->where('id', $id)->update($data);

    return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
}

    public function destroy($id)
    {
        DB::table('admin_users')->where('id', $id)->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully.');
    }
}
