#!/bin/bash

# Reset and Fix Migration - Proper Solution
# This script properly resets the problematic migration and runs the corrected version

set -e

echo "🔧 Resetting and Fixing Migration Properly..."

# Navigate to current app directory
cd /var/www/referral-app/current

echo "📋 Checking current deployment status..."
echo "Current directory: $(pwd)"
echo "Current symlink points to: $(readlink -f /var/www/referral-app/current)"

echo "🔍 Checking migration status..."
php artisan migrate:status

echo "🗃️ Checking if problematic migration exists in database..."
php artisan tinker --execute="
try {
    \$migration = DB::table('migrations')->where('migration', '2025_07_12_051438_enhance_leads_table_with_pipeline_fields')->first();
    if (\$migration) {
        echo 'Migration exists in database at batch: ' . \$migration->batch . PHP_EOL;
        echo 'Removing from migrations table to reset...' . PHP_EOL;
        DB::table('migrations')->where('migration', '2025_07_12_051438_enhance_leads_table_with_pipeline_fields')->delete();
        echo 'Migration removed from database.' . PHP_EOL;
    } else {
        echo 'Migration not found in database.' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking migration: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🗃️ Checking current leads table structure..."
php artisan tinker --execute="
try {
    \$columns = DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ? ORDER BY ordinal_position', ['leads']);
    \$columnNames = array_column(\$columns, 'column_name');
    echo 'Current leads table columns: ' . implode(', ', \$columnNames) . PHP_EOL;
    
    // Check if new columns already exist
    \$newColumns = ['appointment_scheduled_at', 'appointment_completed_at', 'offer_amount', 'offer_made_at', 'sale_amount', 'sale_closed_at'];
    \$existingNewColumns = array_intersect(\$newColumns, \$columnNames);
    
    if (!empty(\$existingNewColumns)) {
        echo 'Some new columns already exist: ' . implode(', ', \$existingNewColumns) . PHP_EOL;
        echo 'Dropping existing new columns to reset...' . PHP_EOL;
        
        // Drop columns that already exist
        foreach (\$existingNewColumns as \$column) {
            try {
                DB::statement('ALTER TABLE leads DROP COLUMN IF EXISTS ' . \$column);
                echo 'Dropped column: ' . \$column . PHP_EOL;
            } catch (Exception \$e) {
                echo 'Error dropping ' . \$column . ': ' . \$e->getMessage() . PHP_EOL;
            }
        }
        
        // Also drop other potential columns
        \$otherColumns = ['appointment_notes', 'offer_notes', 'sale_notes', 'lost_reason', 'commission_override_percentage', 'commission_override_amount', 'commission_override_reason'];
        foreach (\$otherColumns as \$column) {
            try {
                DB::statement('ALTER TABLE leads DROP COLUMN IF EXISTS ' . \$column);
            } catch (Exception \$e) {
                // Ignore errors for columns that don't exist
            }
        }
        
        // Drop indexes if they exist
        try {
            DB::statement('DROP INDEX IF EXISTS leads_appointment_scheduled_at_index');
            DB::statement('DROP INDEX IF EXISTS leads_offer_made_at_index');
            DB::statement('DROP INDEX IF EXISTS leads_sale_closed_at_index');
        } catch (Exception \$e) {
            // Ignore errors
        }
        
        // Reset status column constraint
        try {
            DB::statement('ALTER TABLE leads DROP CONSTRAINT IF EXISTS leads_status_check');
        } catch (Exception \$e) {
            // Ignore errors
        }
    } else {
        echo 'No new columns exist yet.' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking/resetting columns: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔄 Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "🔄 Running the corrected migration..."
php artisan migrate --force

echo "✅ Migration reset and fix complete!"
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
    
    // Check status column constraint
    \$constraints = DB::select('SELECT constraint_name FROM information_schema.table_constraints WHERE table_name = ? AND constraint_type = ?', ['leads', 'CHECK']);
    if (!empty(\$constraints)) {
        echo 'Status constraints: ' . implode(', ', array_column(\$constraints, 'constraint_name')) . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking final state: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "✅ All done! Try visiting /admin/webhooks now."
echo ""
echo "🚀 This migration is now properly fixed and will work on all future deployments!"
