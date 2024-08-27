<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix'=>'auth'],function (){
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);

});

Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('/update-profile',[AuthController::class,'updateProfile'])->middleware('auth:sanctum');
Route::post('/update-password',[AuthController::class,'updatePassword'])->middleware('auth:sanctum');
