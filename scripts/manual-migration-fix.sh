#!/bin/bash

# Manual Migration Fix for PostgreSQL
# This script manually fixes the leads table structure without relying on the problematic migration

set -e

echo "🔧 Manual Migration Fix for PostgreSQL..."

# Navigate to current app directory
cd /var/www/referral-app/current

echo "🗃️ Checking current leads table structure..."
php artisan tinker --execute="
try {
    \$columns = DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ? ORDER BY ordinal_position', ['leads']);
    \$columnNames = array_column(\$columns, 'column_name');
    echo 'Current leads table columns: ' . implode(', ', \$columnNames) . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error checking columns: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔄 Manually adding missing columns if they don't exist..."
php artisan tinker --execute="
try {
    // Check if new columns exist
    \$columns = DB::select('SELECT column_name FROM information_schema.columns WHERE table_name = ?', ['leads']);
    \$columnNames = array_column(\$columns, 'column_name');
    
    if (!in_array('appointment_scheduled_at', \$columnNames)) {
        echo 'Adding missing columns...' . PHP_EOL;
        
        // Add columns one by one
        DB::statement('ALTER TABLE leads ADD COLUMN appointment_scheduled_at TIMESTAMP NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN appointment_completed_at TIMESTAMP NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN offer_amount DECIMAL(12,2) NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN offer_made_at TIMESTAMP NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN sale_amount DECIMAL(12,2) NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN sale_closed_at TIMESTAMP NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN appointment_notes TEXT NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN offer_notes TEXT NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN sale_notes TEXT NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN lost_reason TEXT NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN commission_override_percentage DECIMAL(5,2) NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN commission_override_amount DECIMAL(10,2) NULL');
        DB::statement('ALTER TABLE leads ADD COLUMN commission_override_reason TEXT NULL');
        
        echo 'Columns added successfully!' . PHP_EOL;
    } else {
        echo 'Columns already exist!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error adding columns: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔄 Fixing status column..."
php artisan tinker --execute="
try {
    // Drop existing constraint if it exists
    DB::statement('ALTER TABLE leads DROP CONSTRAINT IF EXISTS leads_status_check');
    
    // Change column type
    DB::statement('ALTER TABLE leads ALTER COLUMN status TYPE VARCHAR(255)');
    
    // Add new constraint
    DB::statement('ALTER TABLE leads ADD CONSTRAINT leads_status_check CHECK (status IN (\'lead\', \'appointment_scheduled\', \'appointment_completed\', \'offer_made\', \'sale\', \'lost\'))');
    
    // Set default
    DB::statement('ALTER TABLE leads ALTER COLUMN status SET DEFAULT \'lead\'');
    
    echo 'Status column fixed successfully!' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error fixing status column: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔄 Adding indexes..."
php artisan tinker --execute="
try {
    DB::statement('CREATE INDEX IF NOT EXISTS leads_appointment_scheduled_at_index ON leads (appointment_scheduled_at)');
    DB::statement('CREATE INDEX IF NOT EXISTS leads_offer_made_at_index ON leads (offer_made_at)');
    DB::statement('CREATE INDEX IF NOT EXISTS leads_sale_closed_at_index ON leads (sale_closed_at)');
    echo 'Indexes added successfully!' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Error adding indexes: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "🔄 Marking migration as completed..."
php artisan tinker --execute="
try {
    // Check if migration record exists
    \$migration = DB::table('migrations')->where('migration', '2025_07_12_051438_enhance_leads_table_with_pipeline_fields')->first();
    if (!\$migration) {
        DB::table('migrations')->insert([
            'migration' => '2025_07_12_051438_enhance_leads_table_with_pipeline_fields',
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        echo 'Migration marked as completed!' . PHP_EOL;
    } else {
        echo 'Migration already marked as completed!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error marking migration: ' . \$e->getMessage() . PHP_EOL;
}
"

echo "✅ Manual migration fix complete!"
echo ""
echo "🗃️ Final table structure:"
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
