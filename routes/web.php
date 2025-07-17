<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Public referral routes
Route::get('/referral', [ReferralController::class, 'index'])->name('referral.form');
Route::post('/referral', [ReferralController::class, 'store'])->name('referral.store');
Route::get('/referral/thank-you', [ReferralController::class, 'thankYou'])->name('referral.thank-you');

// Onboarding routes (must be before profile.complete middleware)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/onboarding/{step?}', [App\Http\Controllers\OnboardingController::class, 'show'])->name('onboarding.step');
    Route::post('/onboarding/{step}', [App\Http\Controllers\OnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/onboarding-skip', [App\Http\Controllers\OnboardingController::class, 'skip'])->name('onboarding.skip');
});

// Authenticated routes with profile completion check
Route::middleware(['auth', 'verified', 'profile.complete'])->group(function () {
    // Partner dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Partner routes
    Route::prefix('partner')->name('partner.')->group(function () {
        Route::get('/leads', [DashboardController::class, 'leads'])->name('leads');
        Route::get('/commissions', [DashboardController::class, 'commissions'])->name('commissions');
        Route::get('/tools', [DashboardController::class, 'tools'])->name('tools');
        Route::get('/payout-settings', [DashboardController::class, 'payoutSettings'])->name('payout-settings');
        Route::post('/payout-settings', [DashboardController::class, 'updatePayoutSettings'])->name('payout-settings.update');
    });
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/leads', [AdminController::class, 'leads'])->name('leads');
    Route::get('/leads/{lead}', [AdminController::class, 'showLead'])->name('leads.show');
    Route::patch('/leads/{lead}/status', [AdminController::class, 'updateLeadStatus'])->name('leads.update-status');
    Route::get('/commissions', [AdminController::class, 'commissions'])->name('commissions');
    Route::patch('/commissions/{commission}/status', [AdminController::class, 'updateCommissionStatus'])->name('commissions.update-status');
    Route::get('/partners', [AdminController::class, 'partners'])->name('partners');
    Route::get('/partners/{partner}', [AdminController::class, 'partnerDetails'])->name('partners.show');
    Route::get('/export/leads', [AdminController::class, 'exportLeads'])->name('export.leads');
    Route::get('/landing-page', [AdminController::class, 'landingPage'])->name('landing-page');
    Route::post('/landing-page', [AdminController::class, 'updateLandingPage'])->name('landing-page.update');
    Route::get('/referral-page', [AdminController::class, 'referralPage'])->name('referral-page');
    Route::post('/referral-page', [AdminController::class, 'updateReferralPage'])->name('referral-page.update');
    
    // Commission settings routes
    Route::get('/commission-settings', [AdminController::class, 'commissionSettings'])->name('commission-settings');
    Route::post('/commission-settings', [AdminController::class, 'storeCommissionSetting'])->name('commission-settings.store');
    Route::patch('/commission-settings/{setting}/activate', [AdminController::class, 'activateCommissionSetting'])->name('commission-settings.activate');
    
    // Pipeline analytics
    Route::get('/pipeline-analytics', [AdminController::class, 'pipelineAnalytics'])->name('pipeline-analytics');
    
    // Webhook management routes
    Route::get('/webhooks', [AdminController::class, 'webhooks'])->name('webhooks');
    Route::post('/webhooks', [AdminController::class, 'storeWebhookSetting'])->name('webhooks.store');
    Route::get('/webhooks/{webhookSetting}/edit', [AdminController::class, 'editWebhookSetting'])->name('webhooks.edit');
    Route::patch('/webhooks/{webhookSetting}', [AdminController::class, 'updateWebhookSetting'])->name('webhooks.update');
    Route::delete('/webhooks/{webhookSetting}', [AdminController::class, 'deleteWebhookSetting'])->name('webhooks.delete');
    Route::patch('/webhooks/{webhookSetting}/toggle', [AdminController::class, 'toggleWebhookSetting'])->name('webhooks.toggle');
    Route::post('/webhooks/settings', [AdminController::class, 'updateWebhookSettings'])->name('webhooks.settings.update');
    Route::post('/webhooks/test', [AdminController::class, 'testWebhook'])->name('webhooks.test');
    Route::post('/webhooks/retry-failed', [AdminController::class, 'retryFailedWebhooks'])->name('webhooks.retry-failed');
    Route::delete('/webhooks/logs/cleanup', [AdminController::class, 'cleanupWebhookLogs'])->name('webhooks.logs.cleanup');
});

require __DIR__.'/auth.php';
