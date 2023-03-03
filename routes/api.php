<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthenticateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function(){
    Route::post('/register', [AuthenticateController::class, 'register']);
    Route::post('/login', [AuthenticateController::class, 'login']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthenticateController::class, 'logout']);

    Route::get('/me', [UsersController::class, 'seeMe']);
    Route::post('/me', [UsersController::class, 'storeMe']);
    Route::get('/user/{id}', [UsersController::class, 'byId']);
    Route::get('/users', [UsersController::class, 'list']);
    
    Route::post('/create/user', [UsersController::class, 'new']);

});

