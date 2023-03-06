<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthenticateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/storage', function(){
    Artisan::call('storage:link'); // Storage Images Users
    return response()->json([ 'response' => 'executed' ]);
});

Route::middleware('guest')->group(function(){

    Route::post('/register', [AuthenticateController::class, 'register']);

    Route::post('/login', [AuthenticateController::class, 'login']);

});

Route::get('/avatar/{id}/update', [UsersController::class, 'modifyAvatar']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthenticateController::class, 'logout']);

    Route::get('/me', [UsersController::class, 'seeMe']);

    Route::post('/me', [UsersController::class, 'storeMe']);

    Route::get('/user/{id}', [UsersController::class, 'byId']);

    Route::get('/users', [UsersController::class, 'list']);
    
    Route::post('/user/create', [UsersController::class, 'new']);

    Route::post('/user/{id}/update', [UsersController::class, 'modifyInfo']);

    Route::patch('/avatar/{id}/update', [UsersController::class, 'modifyAvatar']);
    
    Route::get('/tweets/{user_id}', [UsersController::class, 'tweetsByIdUser']);

});

