#!/bin/bash

# Fix Mail Configuration Issues
# This script addresses SMTP connection problems

set -e

echo "📧 Fixing Mail Configuration..."

# Navigate to app directory
cd /var/www/referral-app/current

echo "🔍 Testing current mail configuration..."

# Test basic connectivity to mail server
echo "📡 Testing SMTP connectivity..."
timeout 10 telnet live.smtp.mailtrap.io 587 || echo "❌ Cannot connect to SMTP server"

# Check if we can resolve the hostname
echo "🌐 Testing DNS resolution..."
nslookup live.smtp.mailtrap.io || echo "❌ DNS resolution failed"

# Test with a simple mail configuration test
echo "📬 Testing Laravel mail configuration..."
php artisan tinker --execute="
try {
    \$config = config('mail');
    echo 'Mail driver: ' . \$config['default'] . PHP_EOL;
    echo 'SMTP host: ' . \$config['mailers']['smtp']['host'] . PHP_EOL;
    echo 'SMTP port: ' . \$config['mailers']['smtp']['port'] . PHP_EOL;
    echo 'SMTP username: ' . \$config['mailers']['smtp']['username'] . PHP_EOL;
} catch (Exception \$e) {
    echo 'Config error: ' . \$e->getMessage();
}
"

# Check if we need to configure firewall rules
echo "🔥 Checking outbound connections..."
sudo ufw status | grep -E "(587|25|465)" || echo "ℹ️ No specific mail port rules found"

echo ""
echo "🔧 Potential fixes:"
echo "1. Check if Mailtrap credentials are correct"
echo "2. Verify server can make outbound SMTP connections"
echo "3. Consider using a different mail service (SendGrid, Mailgun, etc.)"
echo "4. Test with a simple mail service like Gmail SMTP"

echo ""
echo "📋 To test mail manually:"
echo "  php artisan tinker"
echo "  Mail::raw('Test', function(\$m) { \$m->to('test@example.com')->subject('Test'); });"
