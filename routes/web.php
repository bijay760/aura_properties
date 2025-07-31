<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Staff\StaffController;
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
    $router->get('staff', [StaffController::class, 'index'])->name('staff.index');
    $router->get('staff/create', [StaffController::class, 'create'])->name('staff.create');
    $router->post('staff', [StaffController::class, 'store'])->name('staff.store');
    $router->get('staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    $router->put('staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    $router->delete('staff/{id}/delete', [StaffController::class, 'destroy'])->name('staff.destroy');
      $router->get('role', [RoleController::class, 'index'])->name('role.index');
        $router->get('role/create', [RoleController::class, 'create'])->name('role.create');

});
