<?php

namespace Database\Seeders;

use App\Models\CommissionSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommissionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create a default one
        $adminUser = User::where('is_admin', true)->first();
        
        if (!$adminUser) {
            // If no admin exists, use the first user or create a system user
            $adminUser = User::first();
            if (!$adminUser) {
                $adminUser = User::create([
                    'name' => 'System Admin',
                    'email' => 'admin@system.local',
                    'password' => bcrypt('password'),
                    'is_admin' => true,
                    'referral_code' => 'ADMIN001',
                ]);
            }
        }

        // Create default commission setting
        CommissionSetting::create([
            'commission_percentage' => 10.00, // 10%
            'quick_close_bonus' => 250.00, // $250
            'quick_close_days' => 7, // 7 days
            'is_active' => true,
            'description' => 'Default commission structure: 10% commission with $250 quick close bonus for deals closed within 7 days.',
            'created_by' => $adminUser->id,
        ]);
    }
}
