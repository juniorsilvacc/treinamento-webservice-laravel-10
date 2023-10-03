<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Login
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        // Categorias
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::post('/categories/create', [CategoryController::class, 'create'])->middleware('ability:categories:create');
        Route::put('/categories/{id}/update', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}/delete', [CategoryController::class, 'delete']);
        Route::get('/categories/{id}/details', [CategoryController::class, 'details']);
    });

    // Produtos
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products/create', [ProductController::class, 'create']);
    Route::put('/products/{id}/update', [ProductController::class, 'update']);
    Route::delete('/products/{id}/delete', [ProductController::class, 'delete']);
    Route::get('/products/{id}/details', [ProductController::class, 'details']);
});
