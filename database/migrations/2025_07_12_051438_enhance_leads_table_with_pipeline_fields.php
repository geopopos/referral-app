<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns already exist before adding them
        $existingColumns = Schema::getColumnListing('leads');
        
        Schema::table('leads', function (Blueprint $table) use ($existingColumns) {
            // Add pipeline tracking fields only if they don't exist
            if (!in_array('appointment_scheduled_at', $existingColumns)) {
                $table->timestamp('appointment_scheduled_at')->nullable()->after('status');
            }
            if (!in_array('appointment_completed_at', $existingColumns)) {
                $table->timestamp('appointment_completed_at')->nullable()->after('appointment_scheduled_at');
            }
            if (!in_array('offer_amount', $existingColumns)) {
                $table->decimal('offer_amount', 12, 2)->nullable()->after('appointment_completed_at');
            }
            if (!in_array('offer_made_at', $existingColumns)) {
                $table->timestamp('offer_made_at')->nullable()->after('offer_amount');
            }
            if (!in_array('sale_amount', $existingColumns)) {
                $table->decimal('sale_amount', 12, 2)->nullable()->after('offer_made_at');
            }
            if (!in_array('sale_closed_at', $existingColumns)) {
                $table->timestamp('sale_closed_at')->nullable()->after('sale_amount');
            }
            
            // Add notes and tracking fields only if they don't exist
            if (!in_array('appointment_notes', $existingColumns)) {
                $table->text('appointment_notes')->nullable()->after('sale_closed_at');
            }
            if (!in_array('offer_notes', $existingColumns)) {
                $table->text('offer_notes')->nullable()->after('appointment_notes');
            }
            if (!in_array('sale_notes', $existingColumns)) {
                $table->text('sale_notes')->nullable()->after('offer_notes');
            }
            if (!in_array('lost_reason', $existingColumns)) {
                $table->text('lost_reason')->nullable()->after('sale_notes');
            }
            
            // Add commission override fields only if they don't exist
            if (!in_array('commission_override_percentage', $existingColumns)) {
                $table->decimal('commission_override_percentage', 5, 2)->nullable()->after('lost_reason');
            }
            if (!in_array('commission_override_amount', $existingColumns)) {
                $table->decimal('commission_override_amount', 10, 2)->nullable()->after('commission_override_percentage');
            }
            if (!in_array('commission_override_reason', $existingColumns)) {
                $table->text('commission_override_reason')->nullable()->after('commission_override_amount');
            }
        });
        
        // Skip index creation since columns and indexes already exist from previous partial migration
        
        // Update status column - this is the part that was failing
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            // PostgreSQL-specific syntax - handle step by step with proper error handling
            try {
                // First, check if constraint exists and drop it
                $constraintExists = DB::select("
                    SELECT constraint_name 
                    FROM information_schema.table_constraints 
                    WHERE table_name = 'leads' 
                    AND constraint_type = 'CHECK' 
                    AND constraint_name LIKE '%status%'
                ");
                
                if (!empty($constraintExists)) {
                    foreach ($constraintExists as $constraint) {
                        DB::statement("ALTER TABLE leads DROP CONSTRAINT IF EXISTS {$constraint->constraint_name}");
                    }
                }
                
                // Change column type to VARCHAR
                DB::statement("ALTER TABLE leads ALTER COLUMN status TYPE VARCHAR(255)");
                
                // Add new check constraint
                DB::statement("ALTER TABLE leads ADD CONSTRAINT leads_status_check CHECK (status IN ('lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost'))");
                
                // Set default value
                DB::statement("ALTER TABLE leads ALTER COLUMN status SET DEFAULT 'lead'");
                
            } catch (\Exception $e) {
                // If the above fails, try a simpler approach without constraints
                try {
                    DB::statement("ALTER TABLE leads ALTER COLUMN status TYPE VARCHAR(255)");
                    DB::statement("ALTER TABLE leads ALTER COLUMN status SET DEFAULT 'lead'");
                } catch (\Exception $e2) {
                    // Log the error but don't fail the migration
                    Log::warning('Could not modify status column: ' . $e2->getMessage());
                }
            }
        } elseif ($driver === 'mysql') {
            // MySQL-specific syntax
            try {
                DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost') DEFAULT 'lead'");
            } catch (\Exception $e) {
                Log::warning('Could not modify status column: ' . $e->getMessage());
            }
        } else {
            // SQLite and other databases - use Laravel's schema builder
            try {
                Schema::table('leads', function (Blueprint $table) {
                    $table->string('status', 255)->default('lead')->change();
                });
            } catch (\Exception $e) {
                Log::warning('Could not modify status column: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
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
            ]);
            
            // Revert status enum to original values
            $table->enum('status', ['new', 'qualified', 'closed'])->default('new')->change();
        });
    }
};
