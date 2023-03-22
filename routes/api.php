<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\AuthenticateController;

use App\Http\Controllers\ApiController;

use App\Http\Api\RickAndMorty;

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

/**
 * Twitter
 */

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

/**
 * Rick And Morty API
 */

Route::middleware(['auth:sanctum'])->prefix('rickandmorty')->group(function () {

    Route::get('/', [RickAndMorty::class, 'default']);

    Route::get('/character/{ids?}', [RickAndMorty::class, 'character']);

    Route::get('/location/{ids?}', [RickAndMorty::class, 'location']);

    Route::get('/episode/{ids?}', [RickAndMorty::class, 'episode']);

});

