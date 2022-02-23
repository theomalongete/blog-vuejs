<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIAuthController;
use App\Http\Controllers\API\V1\Post\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Login
Route::group(['middleware' => ['api']], function () {
    Route::post('/auths/register', [APIAuthController::class,'register']);
    Route::post('/auths/login', [APIAuthController::class,'login']);
    Route::get('/auths/generate/{password}', [APIAuthController::class,'generate']);
});

Route::group(['middleware' => ['auth','api']], function () {
    Route::post('/auths/logout', [APIAuthController::class,'logout']);
    Route::post('/auths/refresh', [APIAuthController::class,'refresh']);
    Route::get('/auths/profile', [APIAuthController::class,'profile']);
});

//User
// Route::group(['prefix' => 'v1', 'middleware' => ['api','cors']], function(){
//     Route::get('/users', [UserController::class,'index']);
//     Route::get('/users/{id}', [UserController::class,'show']);
//     Route::put('/users/{id}', [UserController::class,'update']);
//     Route::delete('/users/id}', [UserController::class,'destroy']);
// });

//Post
Route::group(['prefix' => 'v1', 'middleware' => ['api']], function(){
    Route::get('/posts', [PostController::class,'index']);
    Route::get('/posts/{id}', [PostController::class,'show']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth','api']], function(){
    Route::post('/posts/create', [PostController::class,'store']);
    Route::put('/posts/{id}', [PostController::class,'update']);
    Route::delete('/posts/{id}', [PostController::class,'destroy']);
});