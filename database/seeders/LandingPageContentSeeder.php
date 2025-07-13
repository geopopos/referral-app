<?php

namespace Database\Seeders;

use App\Models\LandingPageContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Hero Section
            [
                'key' => 'hero_title',
                'type' => 'text',
                'value' => 'Earn Monthly Revenue Referring Roofing & HVAC Contractors',
                'label' => 'Hero Title',
                'description' => 'Main headline on the landing page',
                'sort_order' => 10,
            ],
            [
                'key' => 'hero_subtitle',
                'type' => 'text',
                'value' => 'Earn 10% monthly recurring revenue — every month your referral stays with us.',
                'label' => 'Hero Subtitle',
                'description' => 'Subtitle text below the main headline',
                'sort_order' => 20,
            ],

            // Commission Details
            [
                'key' => 'commission_percentage',
                'type' => 'text',
                'value' => '10%',
                'label' => 'Commission Percentage',
                'description' => 'Monthly revenue share percentage',
                'sort_order' => 30,
            ],
            [
                'key' => 'quick_close_bonus',
                'type' => 'text',
                'value' => '$250',
                'label' => 'Quick Close Bonus',
                'description' => 'Bonus amount for quick closes',
                'sort_order' => 40,
            ],

            // Testimonial Videos
            [
                'key' => 'testimonial_video_1',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Testimonial Video 1',
                'description' => 'YouTube embed URL for first testimonial',
                'sort_order' => 50,
            ],
            [
                'key' => 'testimonial_name_1',
                'type' => 'text',
                'value' => 'Sarah Johnson',
                'label' => 'Testimonial Name 1',
                'description' => 'Name for first testimonial',
                'sort_order' => 51,
            ],
            [
                'key' => 'testimonial_title_1',
                'type' => 'text',
                'value' => 'Marketing Agency Owner',
                'label' => 'Testimonial Title 1',
                'description' => 'Title/role for first testimonial',
                'sort_order' => 52,
            ],

            [
                'key' => 'testimonial_video_2',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Testimonial Video 2',
                'description' => 'YouTube embed URL for second testimonial',
                'sort_order' => 60,
            ],
            [
                'key' => 'testimonial_name_2',
                'type' => 'text',
                'value' => 'Mike Rodriguez',
                'label' => 'Testimonial Name 2',
                'description' => 'Name for second testimonial',
                'sort_order' => 61,
            ],
            [
                'key' => 'testimonial_title_2',
                'type' => 'text',
                'value' => 'Industry Consultant',
                'label' => 'Testimonial Title 2',
                'description' => 'Title/role for second testimonial',
                'sort_order' => 62,
            ],

            [
                'key' => 'testimonial_video_3',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Testimonial Video 3',
                'description' => 'YouTube embed URL for third testimonial',
                'sort_order' => 70,
            ],
            [
                'key' => 'testimonial_name_3',
                'type' => 'text',
                'value' => 'Jennifer Chen',
                'label' => 'Testimonial Name 3',
                'description' => 'Name for third testimonial',
                'sort_order' => 71,
            ],
            [
                'key' => 'testimonial_title_3',
                'type' => 'text',
                'value' => 'Sales Professional',
                'label' => 'Testimonial Title 3',
                'description' => 'Title/role for third testimonial',
                'sort_order' => 72,
            ],

            // Earnings Example
            [
                'key' => 'example_contractor_fee',
                'type' => 'text',
                'value' => '$2,500',
                'label' => 'Example Contractor Monthly Fee',
                'description' => 'Monthly fee used in earnings example',
                'sort_order' => 80,
            ],
            [
                'key' => 'example_monthly_earning',
                'type' => 'text',
                'value' => '$250',
                'label' => 'Example Monthly Earning',
                'description' => 'Monthly earning from one contractor',
                'sort_order' => 81,
            ],
            [
                'key' => 'example_annual_potential',
                'type' => 'text',
                'value' => '$15,000+',
                'label' => 'Example Annual Potential',
                'description' => 'Annual earning potential',
                'sort_order' => 82,
            ],
        ];

        foreach ($contents as $content) {
            LandingPageContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }

        $this->command->info('Landing page content seeded successfully!');
    }
}
