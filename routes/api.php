<?php

use App\Http\Controllers\AiChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// AI Chat Routes
Route::prefix('ai-chat')->group(function () {
    Route::post('/start', [AiChatController::class, 'startSession']);
    Route::post('/', [AiChatController::class, 'chat']);
    Route::get('/history', [AiChatController::class, 'getChatHistory']);
});