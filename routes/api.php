<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{category}', [CategoryController::class, 'show']);
Route::get('list/categories', [CategoryController::class, 'list'] );
Route::post('categories', [CategoryController::class, 'store']);
Route::get('products', [ProductController::class, 'index']);
Route::put('categories/{category}', [CategoryController::class, 'update']);

