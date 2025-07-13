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
        Schema::create('webhook_settings', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->enum('auth_type', ['none', 'basic', 'bearer', 'custom'])->default('none');
            $table->text('auth_credentials')->nullable(); // Encrypted JSON
            $table->json('enabled_events')->default('[]'); // Array of event types
            $table->boolean('is_active')->default(true);
            $table->integer('max_retry_attempts')->default(3);
            $table->json('retry_delays')->default('[60, 300, 900]'); // Seconds: 1min, 5min, 15min
            $table->string('secret_key')->nullable(); // For HMAC signature
            $table->timestamp('last_successful_delivery')->nullable();
            $table->timestamp('last_failed_delivery')->nullable();
            $table->integer('consecutive_failures')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_settings');
    }
};
