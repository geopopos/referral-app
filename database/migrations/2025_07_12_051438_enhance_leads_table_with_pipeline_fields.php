<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Add pipeline tracking fields
            $table->timestamp('appointment_scheduled_at')->nullable()->after('status');
            $table->timestamp('appointment_completed_at')->nullable()->after('appointment_scheduled_at');
            $table->decimal('offer_amount', 12, 2)->nullable()->after('appointment_completed_at');
            $table->timestamp('offer_made_at')->nullable()->after('offer_amount');
            $table->decimal('sale_amount', 12, 2)->nullable()->after('offer_made_at');
            $table->timestamp('sale_closed_at')->nullable()->after('sale_amount');
            
            // Add notes and tracking fields
            $table->text('appointment_notes')->nullable()->after('sale_closed_at');
            $table->text('offer_notes')->nullable()->after('appointment_notes');
            $table->text('sale_notes')->nullable()->after('offer_notes');
            $table->text('lost_reason')->nullable()->after('sale_notes');
            
            // Add commission override fields
            $table->decimal('commission_override_percentage', 5, 2)->nullable()->after('lost_reason');
            $table->decimal('commission_override_amount', 10, 2)->nullable()->after('commission_override_percentage');
            $table->text('commission_override_reason')->nullable()->after('commission_override_amount');
            
            // Add indexes for better performance
            $table->index('appointment_scheduled_at');
            $table->index('offer_made_at');
            $table->index('sale_closed_at');
        });
        
        // Update status enum using database-specific SQL
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            // PostgreSQL-specific syntax
            DB::statement("ALTER TABLE leads DROP CONSTRAINT IF EXISTS leads_status_check");
            DB::statement("ALTER TABLE leads ALTER COLUMN status TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE leads ADD CONSTRAINT leads_status_check CHECK (status IN ('lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost'))");
            DB::statement("ALTER TABLE leads ALTER COLUMN status SET DEFAULT 'lead'");
        } elseif ($driver === 'mysql') {
            // MySQL-specific syntax
            DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost') DEFAULT 'lead'");
        } else {
            // SQLite and other databases - use Laravel's schema builder
            // SQLite doesn't support enum constraints well, so we'll just change the column type
            Schema::table('leads', function (Blueprint $table) {
                $table->string('status', 255)->default('lead')->change();
            });
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
