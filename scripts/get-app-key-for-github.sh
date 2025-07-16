#!/bin/bash

echo "🔑 Getting APP_KEY for GitHub Secrets"
echo "====================================="

# Check if we're on the server
if [ -f "/var/www/referral-app/current/.env" ]; then
    echo "✅ Found production .env file"
    
    # Get the APP_KEY
    APP_KEY=$(grep "APP_KEY=" /var/www/referral-app/current/.env | cut -d'=' -f2)
    
    if [ -n "$APP_KEY" ] && [ "$APP_KEY" != "" ]; then
        echo ""
        echo "🔑 Current APP_KEY:"
        echo "APP_KEY=$APP_KEY"
        echo ""
        echo "📋 Instructions:"
        echo "1. Copy the APP_KEY line above (including APP_KEY=)"
        echo "2. Go to your GitHub repository"
        echo "3. Settings → Secrets and variables → Actions"
        echo "4. Edit the ENV_FILE secret"
        echo "5. Make sure it includes this APP_KEY line"
        echo ""
        echo "⚠️  IMPORTANT: Include the full line with 'APP_KEY=' prefix"
        echo "   The GitHub secret should contain: APP_KEY=$APP_KEY"
    else
        echo "❌ APP_KEY is empty or missing!"
        echo ""
        echo "🔧 Generating a new APP_KEY..."
        cd /var/www/referral-app/current
        php artisan key:generate --show
        
        echo ""
        echo "📋 Next steps:"
        echo "1. Run: php artisan key:generate --force"
        echo "2. Copy the new APP_KEY to your GitHub secrets"
        echo "3. Clear webhook settings: php artisan tinker --execute=\"App\\Models\\WebhookSetting::truncate();\""
    fi
else
    echo "❌ Not on production server or .env file not found"
    echo ""
    echo "📋 Run this script on your production server:"
    echo "ssh root@165.227.66.174"
    echo "cd /var/www/referral-app/current"
    echo "./scripts/get-app-key-for-github.sh"
fi

echo ""
echo "🔍 Verification:"
echo "After updating GitHub secrets, the next deployment should:"
echo "- Preserve the APP_KEY"
echo "- Keep webhook settings working"
echo "- Not show 'MAC is invalid' errors"
