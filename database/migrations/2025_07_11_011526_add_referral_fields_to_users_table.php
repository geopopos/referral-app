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
            $table->enum('role', ['partner', 'admin'])->default('partner')->after('email_verified_at');
            $table->string('referral_code')->unique()->nullable()->after('role');
            $table->enum('payout_method', ['ach', 'paypal'])->nullable()->after('referral_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'referral_code', 'payout_method']);
        });
    }
};
