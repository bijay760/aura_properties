<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'code'=>200,
        'status'=>true,
        'message'=>'welcome to Aura Property',
        'data'=>[]
    ];
});
