<?php

require_once __DIR__ . '/bootstrap/app.php';

use App\Jobs\SendWebhookJob;
use Illuminate\Support\Facades\DB;

echo "🧪 Testing Laravel Queue Workers\n";
echo "================================\n\n";

// Check if jobs table exists and is accessible
try {
    $pendingJobs = DB::table('jobs')->count();
    echo "✅ Jobs table accessible\n";
    echo "📊 Pending jobs: {$pendingJobs}\n\n";
} catch (Exception $e) {
    echo "❌ Error accessing jobs table: " . $e->getMessage() . "\n";
    exit(1);
}

// Dispatch a test webhook job
echo "🚀 Dispatching test webhook job...\n";
try {
    SendWebhookJob::dispatch('queue_test', [
        'message' => 'Queue worker test',
        'timestamp' => now()->toISOString(),
        'test_id' => uniqid()
    ]);
    echo "✅ Test job dispatched successfully\n\n";
} catch (Exception $e) {
    echo "❌ Error dispatching job: " . $e->getMessage() . "\n";
    exit(1);
}

// Check jobs count after dispatch
$newPendingJobs = DB::table('jobs')->count();
echo "📊 Jobs after dispatch: {$newPendingJobs}\n";

if ($newPendingJobs > $pendingJobs) {
    echo "✅ Job was queued successfully\n";
    echo "⏳ Job should be processed by queue workers within a few seconds\n\n";
    
    echo "🔍 To monitor job processing:\n";
    echo "   sudo supervisorctl status laravel-worker:*\n";
    echo "   sudo tail -f /var/log/supervisor/laravel-worker.log\n\n";
    
    echo "📋 To check if job was processed:\n";
    echo "   php artisan tinker --execute=\"echo 'Pending jobs: ' . DB::table('jobs')->count();\"\n";
} else {
    echo "⚠️  Job may not have been queued properly\n";
}

echo "\n🎯 Queue worker test complete!\n";
