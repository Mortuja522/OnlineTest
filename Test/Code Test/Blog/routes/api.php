<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;

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

Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/login',[AuthController::class,'login']);
Route::get('/auth/user',[AuthController::class,'user'])->middleware('auth:sanctum');
Route::post('/auth/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::post('/blogs/create',[BlogController::class,'create'])->middleware('auth:sanctum');
Route::put('/blogs/{id}/update',[BlogController::class,'update'])->middleware('auth:sanctum');
Route::delete('/blogs/{id}/delete',[BlogController::class,'delete'])->middleware('auth:sanctum');
Route::get('/blogs/show',[BlogController::class,'list']);
Route::get('/blogs/imageshow/{id}',[BlogController::class,'imageshow']);

Route::post('/blogs/{blog_id}/comments/create',[CommentController::class,'create'])->middleware('auth:sanctum');