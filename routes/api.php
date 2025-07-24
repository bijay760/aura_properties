<?php

use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Content\AddCitiesController;
use App\Http\Controllers\Content\GetProjectsController;
use App\Http\Controllers\Content\AddLocalitiesController;
use App\Http\Controllers\Content\AddProjectsController;
use App\Http\Controllers\Content\GetLocalitiesController;
use App\Http\Controllers\Content\GetCitiesController;
use App\Http\Controllers\Property\PostPropertyController;
use App\Http\Controllers\Property\EditPropertyController;
use App\Http\Controllers\Property\GetMyPropertyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Property\GetCategoriesController;
use App\Http\Controllers\Auth\LoginVerifyController;
use App\Http\Controllers\Content\OtpSendController;
use App\Http\Controllers\Content\VerifyOtpController;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['apiHash', 'sanitize'])->group(function ($router) {
    $router->post('register', RegisterController::class);
    $router->get('send-otp', OtpSendController::class);
    $router->post('verify-otp', VerifyOtpController::class);
    $router->post('login', LoginController::class);
    $router->post('login-verify', LoginVerifyController::class);

//    get properties categories
    $router->get('category',GetCategoriesController::class);
    $router->post('add-city', AddCitiesController::class);
    $router->get('get-cities', GetCitiesController::class);
    $router->get('get-localities', GetLocalitiesController::class);
    $router->post('add-locality', AddLocalitiesController::class);
    $router->post('add-project', AddProjectsController::class);
    $router->get('get-projects', GetProjectsController::class);

    Route::middleware('validated-user')->group(function ($router) {
        $router->get('profile', ProfileController::class);
        $router->post('post-property', PostPropertyController::class);
        $router->post('edit-property', EditPropertyController::class);
        $router->get('my-property', GetMyPropertyController::class);
    });
});
Route::fallback(function () {
    return response()->json([
        'code' => 404,
        'status' => false,
        'data' => [],
        'message' => 'Page Not Found. If error persists, contact support',
    ], 404);
});
