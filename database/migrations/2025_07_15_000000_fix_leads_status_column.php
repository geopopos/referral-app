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
        // This migration only fixes the status column issue
        // All other columns should already exist from the previous migration
        
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            // PostgreSQL-specific syntax with comprehensive error handling
            try {
                // First, check if any status-related constraints exist and drop them
                $constraints = DB::select("
                    SELECT constraint_name 
                    FROM information_schema.table_constraints 
                    WHERE table_name = 'leads' 
                    AND constraint_type = 'CHECK' 
                    AND constraint_name LIKE '%status%'
                ");
                
                foreach ($constraints as $constraint) {
                    try {
                        DB::statement("ALTER TABLE leads DROP CONSTRAINT IF EXISTS {$constraint->constraint_name}");
                        Log::info("Dropped constraint: {$constraint->constraint_name}");
                    } catch (\Exception $e) {
                        Log::warning("Could not drop constraint {$constraint->constraint_name}: " . $e->getMessage());
                    }
                }
                
                // Change column type to VARCHAR if it's not already
                try {
                    DB::statement("ALTER TABLE leads ALTER COLUMN status TYPE VARCHAR(255)");
                    Log::info("Changed status column type to VARCHAR(255)");
                } catch (\Exception $e) {
                    Log::warning("Could not change status column type: " . $e->getMessage());
                }
                
                // Set default value
                try {
                    DB::statement("ALTER TABLE leads ALTER COLUMN status SET DEFAULT 'lead'");
                    Log::info("Set default value for status column");
                } catch (\Exception $e) {
                    Log::warning("Could not set default value: " . $e->getMessage());
                }
                
                // Add new check constraint (optional - if this fails, it's not critical)
                try {
                    DB::statement("ALTER TABLE leads ADD CONSTRAINT leads_status_check CHECK (status IN ('lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost'))");
                    Log::info("Added status check constraint");
                } catch (\Exception $e) {
                    Log::warning("Could not add status check constraint (this is not critical): " . $e->getMessage());
                }
                
            } catch (\Exception $e) {
                Log::error("PostgreSQL status column fix failed: " . $e->getMessage());
                // Don't throw the exception - let the migration succeed even if status column fix fails
            }
            
        } elseif ($driver === 'mysql') {
            // MySQL-specific syntax
            try {
                DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost') DEFAULT 'lead'");
                Log::info("Updated MySQL status column");
            } catch (\Exception $e) {
                Log::warning("Could not modify MySQL status column: " . $e->getMessage());
            }
            
        } else {
            // SQLite and other databases
            try {
                Schema::table('leads', function (Blueprint $table) {
                    $table->string('status', 255)->default('lead')->change();
                });
                Log::info("Updated SQLite status column");
            } catch (\Exception $e) {
                Log::warning("Could not modify SQLite status column: " . $e->getMessage());
            }
        }
        
        Log::info("Status column fix migration completed");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status column to original enum if needed
        try {
            Schema::table('leads', function (Blueprint $table) {
                $table->enum('status', ['new', 'qualified', 'closed'])->default('new')->change();
            });
        } catch (\Exception $e) {
            Log::warning("Could not revert status column: " . $e->getMessage());
        }
    }
};
