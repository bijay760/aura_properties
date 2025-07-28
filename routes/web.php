<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\User\UserController;

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

});
