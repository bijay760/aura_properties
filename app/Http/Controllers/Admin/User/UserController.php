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
}
