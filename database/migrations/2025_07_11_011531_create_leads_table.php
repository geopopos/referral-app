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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('company');
            $table->text('how_heard_about_us')->nullable();
            $table->string('referral_code')->nullable();
            $table->enum('status', ['new', 'qualified', 'closed'])->default('new');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('referral_code')->references('referral_code')->on('users')->onDelete('set null');
            $table->index('referral_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
