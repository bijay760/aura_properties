<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Get filters from request
        $email = $request->input('email');
        $firstName = $request->input('first_name');
        $surname = $request->input('surname');
        $createdAt = $request->input('created_at');
        $perPage = $request->input('per_page', 3);
        $query = DB::table('admin_users');
        if ($email) {
            $query->where('email', 'like', "%$email%");
        }
        if ($firstName) {
            $query->where('first_name', 'like', "%$firstName%");
        }
        if ($surname) {
            $query->where('surname', 'like', "%$surname%");
        }
        if ($createdAt) {
            // Filter by date only (optional time-aware filter)
            $query->whereDate('created_at', $createdAt);
        }
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
        'email' => 'required|email|unique:admin_users,email',
        'first_name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'password' => 'required|string|min:6',
    ]);


    DB::table('admin_users')->insert($data);

    return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
}
  public function edit($id)
{
    $user = DB::table('admin_users')->find($id);
    return view('admin.user.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $data = $request->validate([
        'email' => 'required|email|unique:admin_users,email,' . $id,
        'first_name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'password' => 'nullable|string|min:6',
    ]);

    if (empty($data['password'])) {
        unset($data['password']);
    }

    DB::table('admin_users')->where('id', $id)->update($data);

    return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
}

    public function destroy($id)
    {
        DB::table('admin_users')->where('id', $id)->delete();
        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully.');
    }
}
