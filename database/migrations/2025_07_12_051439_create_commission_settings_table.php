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
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('commission_percentage', 5, 2)->default(10.00); // Default 10%
            $table->decimal('quick_close_bonus', 10, 2)->default(250.00); // Default $250
            $table->integer('quick_close_days')->default(7); // Default 7 days
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Index for performance
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_settings');
    }
};
