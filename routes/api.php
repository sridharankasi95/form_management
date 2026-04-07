<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// ==========================================
// Public API Routes (no auth needed)
// ==========================================
Route::get('/users', [UserController::class, 'index']);
