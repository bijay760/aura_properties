<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Role\RoleController;


Route::get('/', function () {
    return [
        'code' => 200,
        'message' => 'OK',
        'data' => [],
        'status' => true
    ];
});

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function ($router) {
    // Use $router instead of Route inside this group
    $router->get('/', function () {
        return [
            'code' => 200,
            'message' => 'OK',
            'data' => [],
            'status' => true
        ];
    });
    $router->get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    $router->get('user', [UserController::class, 'index'])->name('user.index');
    $router->get('user/create', [UserController::class, 'create'])->name('user.create');
    $router->post('user', [UserController::class, 'store'])->name('user.store');
    $router->get('user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    $router->put('user/{id}', [UserController::class, 'update'])->name('user.update');
    $router->delete('user/{id}/delete', [UserController::class, 'destroy'])->name('user.destroy');
      $router->get('role', [RoleController::class, 'index'])->name('role.index');
        $router->get('role/create', [RoleController::class, 'create'])->name('role.create');

});
