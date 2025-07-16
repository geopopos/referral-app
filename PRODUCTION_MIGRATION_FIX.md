# Production Migration Fix Guide

## Issue Description
The production server is experiencing a 500 error when accessing `/admin/webhooks` due to a PostgreSQL syntax error in the migration `2025_07_12_051438_enhance_leads_table_with_pipeline_fields.php`.

The error occurs because:
1. The migration partially ran, adding new columns but failing on the status column modification
2. The PostgreSQL syntax for modifying the status column is causing a syntax error
3. The migration is stuck in a failed state, preventing access to admin routes

## Solution Options

### Option 1: Use the Automated Fix Script (Recommended)

Run the automated fix script on the production server:

```bash
cd /var/www/referral-app/current
bash scripts/fix-production-migration.sh
```

This script will:
- Check the current migration status
- Remove the problematic migration record
- Verify table structure
- Manually fix the status column
- Mark the migration as completed
- Run any remaining migrations

### Option 2: Manual Database Fix

If the script doesn't work, manually execute these commands on the production server:

```bash
cd /var/www/referral-app/current

# 1. Remove the problematic migration record
php artisan tinker --execute="DB::table('migrations')->where('migration', '2025_07_12_051438_enhance_leads_table_with_pipeline_fields')->delete();"

# 2. Check table structure
php artisan tinker --execute="print_r(Schema::getColumnListing('leads'));"

# 3. Manually fix the status column (PostgreSQL)
php artisan tinker --execute="
try {
    DB::statement('ALTER TABLE leads ALTER COLUMN status TYPE VARCHAR(255)');
    DB::statement('ALTER TABLE leads ALTER COLUMN status SET DEFAULT \'lead\'');
    echo 'Status column fixed\n';
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . '\n';
}
"

# 4. Mark migration as completed
php artisan tinker --execute="
DB::table('migrations')->insert([
    'migration' => '2025_07_12_051438_enhance_leads_table_with_pipeline_fields',
    'batch' => DB::table('migrations')->max('batch') + 1
]);
"

# 5. Run remaining migrations
php artisan migrate --force
```

### Option 3: Use the New Fix Migration

If the above options don't work, deploy the new fix migration:

1. The new migration `2025_07_15_000000_fix_leads_status_column.php` has been created
2. This migration only handles the status column fix with comprehensive error handling
3. Deploy this file to production and run:

```bash
php artisan migrate --force
```

## Verification Steps

After applying any fix, verify the solution:

1. **Check migration status:**
   ```bash
   php artisan migrate:status
   ```

2. **Test the admin webhooks route:**
   ```bash
   curl -I http://your-domain.com/admin/webhooks
   ```
   (Should return 200 or 302, not 500)

3. **Check application logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Root Cause Analysis

The issue was caused by:
1. **PostgreSQL Syntax Error**: The original migration had malformed SQL for PostgreSQL
2. **Partial Migration**: The migration added columns but failed on status column modification
3. **Inconsistent State**: The migration was marked as "ran" but actually failed

## Prevention

To prevent similar issues in the future:
1. Always test migrations on a staging environment with the same database type as production
2. Use database-agnostic Laravel schema methods when possible
3. Add comprehensive error handling for database-specific operations
4. Test migrations with both fresh databases and existing data

## Files Modified

1. `database/migrations/2025_07_12_051438_enhance_leads_table_with_pipeline_fields.php` - Fixed with idempotent logic
2. `scripts/fix-production-migration.sh` - Automated fix script
3. `database/migrations/2025_07_15_000000_fix_leads_status_column.php` - Backup fix migration

## Contact

If you encounter any issues with these fixes, check the Laravel logs and contact the development team with the specific error messages.
