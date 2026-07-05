<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\LookupController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/lookups/{key}', [LookupController::class, 'index']);

    // Module bundle route groups will be registered here, one per delivery:
    // Route::apiResource('members', MemberController::class);
    // Route::apiResource('honorary-members', HonoraryMemberController::class);
    // ... (Bundle 1)
});
