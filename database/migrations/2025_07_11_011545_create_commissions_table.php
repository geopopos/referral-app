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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['rev_share', 'bonus'])->default('rev_share');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['lead_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
