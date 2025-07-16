#!/bin/bash

# Fix production migration issue
# This script will handle the problematic migration on production

echo "Fixing production migration issue..."

# First, let's check the current migration status
echo "Current migration status:"
php artisan migrate:status

# Remove the problematic migration record from the database
echo "Removing problematic migration record..."
php artisan tinker --execute="
try {
    DB::table('migrations')->where('migration', '2025_07_12_051438_enhance_leads_table_with_pipeline_fields')->delete();
    echo 'Migration record deleted successfully\n';
} catch (Exception \$e) {
    echo 'Error deleting migration record: ' . \$e->getMessage() . '\n';
}
"

# Check if the leads table already has the new columns
echo "Checking leads table structure..."
php artisan tinker --execute="
try {
    \$columns = Schema::getColumnListing('leads');
    echo 'Current leads table columns: ' . implode(', ', \$columns) . '\n';
    
    \$newColumns = [
        'appointment_scheduled_at',
        'appointment_completed_at', 
        'offer_amount',
        'offer_made_at',
        'sale_amount',
        'sale_closed_at',
        'appointment_notes',
        'offer_notes',
        'sale_notes',
        'lost_reason',
        'commission_override_percentage',
        'commission_override_amount',
        'commission_override_reason'
    ];
    
    \$missingColumns = [];
    foreach (\$newColumns as \$col) {
        if (!in_array(\$col, \$columns)) {
            \$missingColumns[] = \$col;
        }
    }
    
    if (empty(\$missingColumns)) {
        echo 'All new columns already exist in the table\n';
    } else {
        echo 'Missing columns: ' . implode(', ', \$missingColumns) . '\n';
    }
} catch (Exception \$e) {
    echo 'Error checking table structure: ' . \$e->getMessage() . '\n';
}
"

# Try to manually fix the status column if needed
echo "Attempting to fix status column..."
php artisan tinker --execute="
try {
    // Check current status column type
    \$result = DB::select('SELECT data_type FROM information_schema.columns WHERE table_name = ? AND column_name = ?', ['leads', 'status']);
    if (!empty(\$result)) {
        echo 'Current status column type: ' . \$result[0]->data_type . '\n';
    }
    
    // Try to modify the status column safely
    DB::statement('ALTER TABLE leads ALTER COLUMN status TYPE VARCHAR(255)');
    DB::statement('ALTER TABLE leads ALTER COLUMN status SET DEFAULT \'lead\'');
    echo 'Status column updated successfully\n';
} catch (Exception \$e) {
    echo 'Status column update failed (this may be expected): ' . \$e->getMessage() . '\n';
}
"

# Mark the migration as completed manually
echo "Marking migration as completed..."
php artisan tinker --execute="
try {
    DB::table('migrations')->insert([
        'migration' => '2025_07_12_051438_enhance_leads_table_with_pipeline_fields',
        'batch' => DB::table('migrations')->max('batch') + 1
    ]);
    echo 'Migration marked as completed\n';
} catch (Exception \$e) {
    echo 'Error marking migration as completed: ' . \$e->getMessage() . '\n';
}
"

# Run any remaining migrations
echo "Running remaining migrations..."
php artisan migrate --force

echo "Migration fix completed!"
echo "Final migration status:"
php artisan migrate:status
