#!/bin/bash

echo "🔧 Fixing deployment APP_KEY issue..."

# This script fixes the deployment process to preserve APP_KEY between deployments
# The issue: php artisan key:generate --force runs on every deployment, breaking encrypted data

echo "The problem:"
echo "- Your deployment script runs 'php artisan key:generate --force' on every deployment"
echo "- This generates a new APP_KEY each time"
echo "- Encrypted webhook settings become unreadable with the new key"
echo "- This causes the 'MAC is invalid' error on /admin/webhooks"

echo ""
echo "The solution:"
echo "1. Remove 'php artisan key:generate --force' from deployment"
echo "2. Ensure APP_KEY is set in your GitHub secrets ENV_FILE"
echo "3. Add a check to only generate key if it's missing"

echo ""
echo "✅ Creating fixed deployment workflow..."

# The fix is to modify the deployment script to only generate a key if one doesn't exist
echo "The deployment script should be updated to:"
echo "- Check if APP_KEY exists in .env"
echo "- Only generate a new key if APP_KEY is empty or missing"
echo "- Never overwrite an existing APP_KEY"

echo ""
echo "🔍 Current APP_KEY status:"
if grep -q "APP_KEY=" .env 2>/dev/null; then
    if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=\"\"" .env; then
        echo "❌ APP_KEY is empty in .env"
    else
        echo "✅ APP_KEY is set in .env"
    fi
else
    echo "❌ APP_KEY not found in .env"
fi

echo ""
echo "📋 Next steps:"
echo "1. Update your GitHub secrets ENV_FILE to include a fixed APP_KEY"
echo "2. Update the deployment script to preserve the APP_KEY"
echo "3. Test the deployment to ensure webhook settings persist"

echo ""
echo "🔧 To get your current APP_KEY for GitHub secrets:"
echo "Run this on your server: grep APP_KEY /var/www/referral-app/current/.env"
