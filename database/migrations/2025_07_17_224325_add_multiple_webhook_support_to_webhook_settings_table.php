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
        Schema::table('webhook_settings', function (Blueprint $table) {
            $table->string('name')->default('Default Webhook')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->integer('priority')->default(0)->after('description');
            
            // Add index for better performance when querying multiple active webhooks
            $table->index(['is_active', 'priority']);
        });

        // Update existing webhook settings with proper names
        $existingWebhooks = DB::table('webhook_settings')->get();
        foreach ($existingWebhooks as $index => $webhook) {
            DB::table('webhook_settings')
                ->where('id', $webhook->id)
                ->update([
                    'name' => $index === 0 ? 'Legacy Webhook Configuration' : 'Webhook Configuration ' . ($index + 1),
                    'description' => 'Migrated from single webhook setup',
                    'priority' => $index
                ]);
        }

        // Add unique constraint after updating existing records
        Schema::table('webhook_settings', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webhook_settings', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropIndex(['is_active', 'priority']);
            $table->dropColumn(['name', 'description', 'priority']);
        });
    }
};
