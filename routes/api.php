<?php

use App\Http\Controllers\JwtUserController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('products/{id?}',[ProductController::class,'getData']);
Route::post('products',[ProductController::class,'store']);
Route::put('products/{id}',[ProductController::class,'update']);
Route::delete('products/{id}',[ProductController::class,'delete']);

Route::post('users/login', [JwtUserController::class,'login']);
Route::post('users/register', [JwtUserController::class,'register']);
Route::get('users/logout', [JwtUserController::class,'logout']);
Route::get('users', [JwtUserController::class,'getUser']);