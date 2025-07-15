#!/bin/bash

# Setup Laravel Queue Workers with Supervisor
# This script configures and starts Laravel queue workers

set -e

echo "🚀 Setting up Laravel Queue Workers..."

# Copy supervisor configuration
echo "📋 Installing supervisor configuration..."
sudo cp /var/www/referral-app/current/scripts/supervisor-laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Create log directory if it doesn't exist
echo "📁 Creating log directory..."
sudo mkdir -p /var/log/supervisor
sudo chown www-data:www-data /var/log/supervisor

# Reload supervisor configuration
echo "🔄 Reloading supervisor configuration..."
sudo supervisorctl reread
sudo supervisorctl update

# Start the workers
echo "▶️ Starting Laravel queue workers..."
sudo supervisorctl start laravel-worker:*

# Check status
echo "✅ Checking worker status..."
sudo supervisorctl status laravel-worker:*

echo "🎉 Queue workers setup complete!"
echo ""
echo "📊 To monitor workers:"
echo "  sudo supervisorctl status laravel-worker:*"
echo "  sudo tail -f /var/log/supervisor/laravel-worker.log"
echo ""
echo "🔧 To restart workers:"
echo "  sudo supervisorctl restart laravel-worker:*"
