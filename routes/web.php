<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Staff\StaffController;
use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Property\PropertyController;
use App\Http\Controllers\Admin\Categories\CategoriesController;


Route::get('/', function () {
    return [
        'code' => 200,
        'message' => 'OK',
        'data' => [],
        'status' => true
    ];
});

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function ($router) {

    $router->get('/', function () {
        return [
            'code' => 200,
            'message' => 'OK',
            'data' => [],
            'status' => true
        ];
    });

    $router->get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Staff routes group
    $router->group(['prefix' => 'staff', 'as' => 'staff.'], function ($router) {
        $router->get('/', [StaffController::class, 'index'])->name('index');
        $router->get('create', [StaffController::class, 'create'])->name('create');
        $router->post('/', [StaffController::class, 'store'])->name('store');
        $router->get('{id}/edit', [StaffController::class, 'edit'])->name('edit');
        $router->put('{id}', [StaffController::class, 'update'])->name('update');
        $router->delete('{id}/delete', [StaffController::class, 'destroy'])->name('destroy');
    });

    // Role routes group
    $router->group(['prefix' => 'role', 'as' => 'role.'], function ($router) {
        $router->get('/', [RoleController::class, 'index'])->name('index');
        $router->get('create', [RoleController::class, 'create'])->name('create');
    });

    // User routes group
    $router->group(['prefix' => 'user', 'as' => 'user.'], function ($router) {
        $router->get('/', [UserController::class, 'index'])->name('index');
         $router->get('create', [UserController::class, 'create'])->name('create');
        $router->post('/', [UserController::class, 'store'])->name('store');
        $router->get('{id}/edit', [UserController::class, 'edit'])->name('edit');
        $router->put('{id}', [UserController::class, 'update'])->name('update');
        $router->delete('{id}/delete', [UserController::class, 'destroy'])->name('destroy');
    });

    // Property routes group
    $router->group(['prefix' => 'property', 'as' => 'property.'], function ($router) {
    $router->get('/', [PropertyController::class, 'index'])->name('index');
    $router->get('create', [PropertyController::class, 'create'])->name('create');
    });


   // Categories routes group
$router->group(['prefix' => 'categories', 'as' => 'categories.'], function ($router) {
    $router->get('/', [CategoriesController::class, 'index'])->name('index');
    $router->get('create', [CategoriesController::class, 'create'])->name('create');
    $router->post('/', [CategoriesController::class, 'store'])->name('store');
    $router->get('edit', [CategoriesController::class, 'edit'])->name('edit');
    $router->put('/', [CategoriesController::class, 'update'])->name('update');
    $router->delete('{id}', [CategoriesController::class, 'destroy'])->name('destroy');
    
});

});