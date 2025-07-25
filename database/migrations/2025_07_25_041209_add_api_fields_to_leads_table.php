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
            // Add referrer relationship field
            $table->unsignedBigInteger('referrer_id')->nullable()->after('referral_code');
            
            // Add general fields
            $table->text('notes')->nullable()->after('referrer_id');
            $table->string('source')->nullable()->after('notes');
            
            // Add UTM tracking fields
            $table->string('utm_source')->nullable()->after('source');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');
            
            // Add pipeline stage field
            $table->string('pipeline_stage')->nullable()->after('utm_content');
            
            // Add date fields for pipeline tracking
            $table->date('appointment_date')->nullable()->after('pipeline_stage');
            $table->date('proposal_sent_date')->nullable()->after('appointment_date');
            $table->date('close_date')->nullable()->after('proposal_sent_date');
            
            // Add financial field
            $table->decimal('monthly_retainer', 10, 2)->nullable()->after('close_date');
            
            // Add foreign key constraint for referrer_id
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['referrer_id']);
            
            // Drop all added columns
            $table->dropColumn([
                'referrer_id',
                'notes',
                'source',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'pipeline_stage',
                'appointment_date',
                'proposal_sent_date',
                'close_date',
                'monthly_retainer'
            ]);
        });
    }
};
