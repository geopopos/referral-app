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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_setting_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // lead.created, lead.updated, etc.
            $table->string('webhook_id')->unique(); // Unique delivery ID
            $table->string('url');
            $table->json('payload'); // The webhook payload sent
            $table->json('headers')->nullable(); // Request headers sent
            $table->integer('http_status')->nullable(); // Response status code
            $table->text('response_body')->nullable(); // Response body
            $table->json('response_headers')->nullable(); // Response headers
            $table->enum('status', ['pending', 'success', 'failed', 'retrying'])->default('pending');
            $table->integer('attempt_number')->default(1);
            $table->integer('max_attempts')->default(3);
            $table->timestamp('next_retry_at')->nullable();
            $table->text('error_message')->nullable();
            $table->decimal('response_time', 8, 3)->nullable(); // Response time in seconds
            $table->timestamps();
            
            $table->index(['event_type', 'status']);
            $table->index(['webhook_setting_id', 'created_at']);
            $table->index('next_retry_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
