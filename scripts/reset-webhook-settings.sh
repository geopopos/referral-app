#!/bin/bash

echo "🔧 Resetting Webhook Settings"
echo "============================="

echo "This script will clear all webhook settings to fix 'MAC is invalid' errors"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Not in Laravel root directory"
    echo "Run this from: /var/www/referral-app/current"
    exit 1
fi

echo "🔍 Current webhook settings status:"
php artisan tinker --execute="
try {
    \$count = \App\Models\WebhookSetting::count();
    echo 'Found ' . \$count . ' webhook settings' . PHP_EOL;
    if (\$count > 0) {
        echo 'These will be cleared to fix encryption issues' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error accessing webhook settings: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
read -p "Do you want to clear all webhook settings? (y/N): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🗑️  Clearing webhook settings..."
    
    php artisan tinker --execute="
    try {
        \App\Models\WebhookSetting::truncate();
        echo '✅ Webhook settings cleared successfully!' . PHP_EOL;
        echo 'You can now access /admin/webhooks and reconfigure your settings.' . PHP_EOL;
    } catch (Exception \$e) {
        echo '❌ Error clearing webhook settings: ' . \$e->getMessage() . PHP_EOL;
    }
    "
    
    echo ""
    echo "🔄 Clearing Laravel caches..."
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    
    echo ""
    echo "✅ Webhook reset complete!"
    echo ""
    echo "📋 Next steps:"
    echo "1. Visit /admin/webhooks - it should load without errors"
    echo "2. Configure your webhook URL and settings"
    echo "3. Test the webhook functionality"
    echo "4. Deploy again to verify settings persist"
    
else
    echo "❌ Operation cancelled"
fi

echo ""
echo "🔍 To check webhook status after reset:"
echo "curl -I http://volumeupagencyreferrals.com/admin/webhooks"
