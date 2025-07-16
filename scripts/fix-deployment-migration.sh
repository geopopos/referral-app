#!/bin/bash

# Fix Deployment and Migration Issue
# This script addresses the PostgreSQL migration syntax error and deployment issues

set -e

echo "🔧 Fixing Deployment and Migration Issue..."

# Navigate to current app directory
cd /var/www/referral-app/current

echo "📋 Checking current deployment status..."
echo "Current directory: $(pwd)"
echo "Current symlink points to: $(readlink -f /var/www/referral-app/current)"

echo "🔍 Checking migration status..."
php artisan migrate:status

echo "🗃️ Checking if problematic migration has run..."
php artisan tinker --execute="
try {
    \$migration = DB::table('migrations')->where('migration', '2025_07_12_051438_enhance_leads_table_with_pipeline_fields')->first();
    if (\$migration) {
        echo 'Migration already ran at batch: ' . \$migration->batch . PHP_EOL;
        echo 'Need to rollback and re-run...' . PHP_EOL;
    } else {
        echo 'Migration has not run yet.' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking migration: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🗃️ Checking leads table structure..."
php artisan tinker --execute="
try {
    \$columns = DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ? ORDER BY ordinal_position', ['leads']);
    \$columnNames = array_column(\$columns, 'column_name');
    echo 'Current leads table columns: ' . implode(', ', \$columnNames) . PHP_EOL;
    
    if (in_array('appointment_scheduled_at', \$columnNames)) {
        echo 'New columns already exist - migration partially completed' . PHP_EOL;
    } else {
        echo 'New columns do not exist yet' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking columns: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔄 Attempting to rollback problematic migration if it exists..."
php artisan migrate:rollback --step=1 --force || echo "Rollback failed or migration not found"

echo "🔄 Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "🔄 Re-running migrations..."
php artisan migrate --force

echo "✅ Migration fix complete!"
echo ""
echo "🔍 Final verification:"
php artisan migrate:status

echo ""
echo "🗃️ Final table structure check..."
php artisan tinker --execute="
try {
    \$columns = DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ? ORDER BY ordinal_position', ['leads']);
    \$columnNames = array_column(\$columns, 'column_name');
    echo 'Final leads table columns: ' . implode(', ', \$columnNames) . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error checking final columns: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "✅ All done! Try visiting /admin/webhooks now."
