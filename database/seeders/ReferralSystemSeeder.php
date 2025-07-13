<?php

namespace Database\Seeders;

use App\Models\Commission;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReferralSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@volumeupagency.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'referral_code' => null,
                'payout_method' => null,
            ]
        );

        // Create 3 partner users
        $partners = [];
        
        $partners[] = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Smith',
                'password' => Hash::make('password'),
                'role' => 'partner',
                'referral_code' => 'john123',
                'payout_method' => 'ach',
            ]
        );

        $partners[] = User::firstOrCreate(
            ['email' => 'sarah@example.com'],
            [
                'name' => 'Sarah Johnson',
                'password' => Hash::make('password'),
                'role' => 'partner',
                'referral_code' => 'sarah456',
                'payout_method' => 'paypal',
            ]
        );

        $partners[] = User::firstOrCreate(
            ['email' => 'mike@example.com'],
            [
                'name' => 'Mike Davis',
                'password' => Hash::make('password'),
                'role' => 'partner',
                'referral_code' => 'mike789',
                'payout_method' => 'ach',
            ]
        );

        // Create leads for each partner
        foreach ($partners as $partner) {
            // Create 2-3 leads per partner
            $leadCount = rand(2, 3);
            
            for ($i = 0; $i < $leadCount; $i++) {
                $lead = Lead::factory()->withReferrer($partner)->create();
                
                // Create commission for each lead
                Commission::create([
                    'user_id' => $partner->id,
                    'lead_id' => $lead->id,
                    'type' => 'rev_share',
                    'amount' => rand(250, 750), // Random commission between $250-$750
                    'status' => $this->getRandomCommissionStatus(),
                    'paid_at' => $this->getRandomCommissionStatus() === 'paid' ? now()->subDays(rand(1, 30)) : null,
                ]);
            }
            
            // Add a bonus commission for one partner
            if ($partner->referral_code === 'john123') {
                $bonusLead = $partner->leads->first();
                Commission::create([
                    'user_id' => $partner->id,
                    'lead_id' => $bonusLead->id,
                    'type' => 'bonus',
                    'amount' => 1000, // Fast-close bonus
                    'status' => 'approved',
                    'paid_at' => null,
                ]);
            }
        }

        // Create some direct leads (no referrer)
        Lead::factory()->count(2)->create([
            'referral_code' => null,
        ]);

        $this->command->info('Referral system seeded successfully!');
        $this->command->info('Admin login: admin@volumeupagency.com / password');
        $this->command->info('Partner logins:');
        $this->command->info('- john@example.com / password (referral code: john123)');
        $this->command->info('- sarah@example.com / password (referral code: sarah456)');
        $this->command->info('- mike@example.com / password (referral code: mike789)');
    }

    /**
     * Get a random commission status with weighted distribution.
     */
    private function getRandomCommissionStatus(): string
    {
        $statuses = ['pending', 'approved', 'paid'];
        $weights = [30, 40, 30]; // 30% pending, 40% approved, 30% paid
        
        $random = rand(1, 100);
        $cumulative = 0;
        
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $statuses[$index];
            }
        }
        
        return 'pending';
    }
}
