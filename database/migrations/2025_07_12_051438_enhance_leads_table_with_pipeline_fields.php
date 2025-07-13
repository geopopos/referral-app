<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Update status enum to include new pipeline stages
            $table->enum('status', ['lead', 'appointment_scheduled', 'appointment_completed', 'offer_made', 'sale', 'lost'])->default('lead')->change();
            
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
