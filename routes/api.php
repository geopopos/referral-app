<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LeadApiController;
use App\Http\Controllers\Api\CommissionApiController;
use App\Http\Controllers\Api\WebhookApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/auth/token', [AuthApiController::class, 'generateToken'])->name('api.auth.token');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/auth/me', [AuthApiController::class, 'me'])->name('api.auth.me');
    Route::post('/auth/revoke', [AuthApiController::class, 'revokeToken'])->name('api.auth.revoke');
});

// Admin-only API routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->name('api.admin.')->group(function () {
    
    // Leads API endpoints
    Route::apiResource('leads', LeadApiController::class);
    
    // Commissions API endpoints
    Route::apiResource('commissions', CommissionApiController::class);
    
    // Webhook management endpoints
    Route::prefix('webhooks')->name('webhooks.')->group(function () {
        Route::get('/settings', [WebhookApiController::class, 'getSettings'])->name('settings.get');
        Route::put('/settings', [WebhookApiController::class, 'updateSettings'])->name('settings.update');
        Route::post('/test', [WebhookApiController::class, 'testWebhook'])->name('test');
        Route::get('/logs', [WebhookApiController::class, 'getLogs'])->name('logs.index');
        Route::get('/logs/{webhookLog}', [WebhookApiController::class, 'getLogDetails'])->name('logs.show');
        Route::post('/logs/{webhookLog}/retry', [WebhookApiController::class, 'retryWebhook'])->name('logs.retry');
        Route::post('/retry-all-failed', [WebhookApiController::class, 'retryAllFailed'])->name('retry-all-failed');
        Route::get('/stats', [WebhookApiController::class, 'getStats'])->name('stats');
        Route::delete('/logs/cleanup', [WebhookApiController::class, 'cleanupLogs'])->name('logs.cleanup');
    });
    
});
