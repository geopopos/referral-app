#!/bin/bash

# Fix Queue Worker Issues
# This script addresses stuck jobs and configuration problems

set -e

echo "🔧 Fixing Queue Worker Issues..."

# Navigate to app directory
cd /var/www/referral-app/current

# Stop current workers
echo "⏹️ Stopping current queue workers..."
sudo supervisorctl stop laravel-worker:* || true

# Clear any stuck jobs
echo "🧹 Clearing stuck jobs..."
php artisan queue:clear

# Clear failed jobs
echo "🗑️ Clearing failed jobs..."
php artisan queue:flush

# Restart queue workers with database connection
echo "🔄 Restarting queue workers..."
sudo supervisorctl start laravel-worker:*

# Check worker status
echo "✅ Checking worker status..."
sudo supervisorctl status laravel-worker:*

# Test mail configuration
echo "📧 Testing mail configuration..."
php artisan tinker --execute="
try {
    Mail::raw('Test email from queue worker setup', function(\$message) {
        \$message->to('test@example.com')->subject('Queue Test');
    });
    echo 'Mail configuration test passed';
} catch (Exception \$e) {
    echo 'Mail error: ' . \$e->getMessage();
}
"

echo ""
echo "🎉 Queue issues fixed!"
echo ""
echo "📊 Monitor with:"
echo "  sudo tail -f /var/log/supervisor/laravel-worker.log"
echo "  php artisan queue:work --verbose"
