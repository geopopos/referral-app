<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test webhook system
echo "=== Webhook System Test ===\n\n";

try {
    // Create a webhook setting
    $webhookSetting = \App\Models\WebhookSetting::create([
        'url' => 'https://webhook.site/test-endpoint',
        'auth_type' => 'bearer',
        'auth_credentials' => ['token' => 'test-token-123'],
        'enabled_events' => ['lead.created', 'commission.approved'],
        'is_active' => true,
        'max_retry_attempts' => 3,
        'retry_delays' => [60, 300, 900],
        'secret_key' => 'test-secret-key',
    ]);

    echo "✓ Created webhook setting with ID: {$webhookSetting->id}\n";

    // Test webhook service
    $webhookService = app(\App\Services\WebhookService::class);
    
    // Send a test webhook
    echo "✓ Testing webhook delivery...\n";
    
    $result = $webhookService->sendWebhook('test.event', [
        'message' => 'This is a test webhook',
        'timestamp' => now()->toISOString(),
        'test_data' => [
            'user_id' => 1,
            'action' => 'webhook_test'
        ]
    ]);

    if ($result) {
        echo "✓ Test webhook sent successfully\n";
    } else {
        echo "✗ Test webhook failed\n";
    }

    // Check webhook logs
    $logs = \App\Models\WebhookLog::latest()->take(5)->get();
    echo "\n--- Recent Webhook Logs ---\n";
    foreach ($logs as $log) {
        echo "ID: {$log->id} | Event: {$log->event_type} | Status: {$log->status} | Created: {$log->created_at}\n";
    }

    // Test webhook statistics
    $stats = $webhookService->getWebhookStats($webhookSetting, 30);
    echo "\n--- Webhook Statistics (Last 30 days) ---\n";
    echo "Total Deliveries: {$stats['total_deliveries']}\n";
    echo "Successful: {$stats['successful_deliveries']}\n";
    echo "Failed: {$stats['failed_deliveries']}\n";
    echo "Success Rate: {$stats['success_rate']}%\n";

    // Test creating a lead (should trigger webhook)
    echo "\n--- Testing Lead Creation Webhook ---\n";
    
    $user = \App\Models\User::where('role', 'partner')->first();
    if ($user) {
        $lead = \App\Models\Lead::create([
            'name' => 'Test Lead ' . time(),
            'email' => 'test' . time() . '@example.com',
            'phone' => '+1234567890',
            'company' => 'Test Company',
            'how_heard_about_us' => 'Referral',
            'referral_code' => $user->referral_code,
            'status' => 'lead',
        ]);

        echo "✓ Created test lead with ID: {$lead->id}\n";
        echo "✓ This should have triggered a 'lead.created' webhook\n";

        // Update lead status (should trigger webhook)
        $lead->update(['status' => 'appointment_scheduled']);
        echo "✓ Updated lead status (should trigger 'lead.updated' and 'lead.status_changed' webhooks)\n";
    } else {
        echo "✗ No partner user found to test lead creation\n";
    }

    // Test commission creation
    echo "\n--- Testing Commission Creation Webhook ---\n";
    
    if (isset($lead) && $user) {
        $commission = \App\Models\Commission::create([
            'user_id' => $user->id,
            'lead_id' => $lead->id,
            'type' => 'rev_share',
            'amount' => 250.00,
            'status' => 'pending',
        ]);

        echo "✓ Created test commission with ID: {$commission->id}\n";
        echo "✓ This should have triggered a 'commission.created' webhook\n";

        // Approve commission (should trigger webhook)
        $commission->markAsApproved();
        echo "✓ Approved commission (should trigger 'commission.approved' webhook)\n";
    }

    // Show final webhook log count
    $totalLogs = \App\Models\WebhookLog::count();
    echo "\n--- Final Results ---\n";
    echo "Total webhook logs in database: {$totalLogs}\n";

    // Test retry functionality
    echo "\n--- Testing Retry Functionality ---\n";
    $failedLogs = \App\Models\WebhookLog::where('status', 'failed')->count();
    if ($failedLogs > 0) {
        $retriedCount = $webhookService->retryFailedWebhooks();
        echo "✓ Queued {$retriedCount} failed webhooks for retry\n";
    } else {
        echo "✓ No failed webhooks to retry\n";
    }

    echo "\n=== Webhook System Test Complete ===\n";
    echo "Check your webhook endpoint to see if the webhooks were delivered!\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
