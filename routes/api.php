<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Categorias
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories/create', [CategoryController::class, 'create']);
Route::put('/categories/{id}/update', [CategoryController::class, 'update']);
Route::delete('/categories/{id}/delete', [CategoryController::class, 'delete']);
Route::get('/categories/{id}/details', [CategoryController::class, 'details']);

// Produtos
Route::get('/products', [ProductController::class, 'index']);
