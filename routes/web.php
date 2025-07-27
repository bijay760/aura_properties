<?php
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    return view('admin.index');
});

Route::get('/admin/users', function () {
    $users = User::limit(100)->get();
    dump($users); // dumps to terminal
    return view('admin.user', compact('users'));
});
