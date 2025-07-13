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
        Schema::table('users', function (Blueprint $table) {
            // Core onboarding fields
            $table->string('website_url')->nullable();
            $table->string('paypal_email')->nullable();
            $table->enum('payment_method', ['paypal', 'ach'])->nullable();
            $table->integer('current_client_count')->nullable();
            $table->boolean('wants_advertising_help')->default(false);
            
            // Business information
            $table->string('business_type')->nullable();
            $table->integer('years_in_business')->nullable();
            $table->decimal('average_project_value', 10, 2)->nullable();
            $table->text('primary_services')->nullable();
            $table->text('biggest_challenge')->nullable();
            
            // Communication preferences
            $table->enum('preferred_communication', ['email', 'phone', 'slack', 'teams'])->nullable();
            $table->string('timezone')->nullable();
            $table->string('referral_source')->nullable();
            
            // Onboarding flow control
            $table->boolean('profile_completed')->default(false);
            $table->integer('onboarding_step')->default(1);
            $table->timestamp('onboarding_completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'website_url',
                'paypal_email',
                'payment_method',
                'current_client_count',
                'wants_advertising_help',
                'business_type',
                'years_in_business',
                'average_project_value',
                'primary_services',
                'biggest_challenge',
                'preferred_communication',
                'timezone',
                'referral_source',
                'profile_completed',
                'onboarding_step',
                'onboarding_completed_at',
            ]);
        });
    }
};
