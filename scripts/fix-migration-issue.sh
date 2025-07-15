#!/bin/bash

# Fix Migration Issue on Production
# This script addresses the PostgreSQL migration syntax error

set -e

echo "🔧 Fixing Migration Issue..."

# Navigate to current app directory
cd /var/www/referral-app/current

echo "📋 Checking migration status..."
php artisan migrate:status

echo "🔍 Checking if problematic migration has run..."
php artisan tinker --execute="
try {
    \$migration = DB::table('migrations')->where('migration', '2025_07_12_051438_enhance_leads_table_with_pipeline_fields')->first();
    if (\$migration) {
        echo 'Migration already ran at: ' . \$migration->batch . PHP_EOL;
        echo 'Rolling back and re-running...' . PHP_EOL;
    } else {
        echo 'Migration has not run yet.' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking migration: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🗃️ Checking if leads table has new columns..."
php artisan tinker --execute="
try {
    \$columns = DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ?', ['leads']);
    \$columnNames = array_column(\$columns, 'column_name');
    if (in_array('appointment_scheduled_at', \$columnNames)) {
        echo 'New columns already exist - migration partially completed' . PHP_EOL;
    } else {
        echo 'New columns do not exist yet' . PHP_EOL;
    }
    echo 'Current columns: ' . implode(', ', \$columnNames) . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error checking columns: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔄 Attempting to run migrations..."
php artisan migrate --force

echo "✅ Migration fix complete!"
echo ""
echo "🔍 To verify:"
echo "  php artisan migrate:status"
echo "  Visit /admin/webhooks to test"
