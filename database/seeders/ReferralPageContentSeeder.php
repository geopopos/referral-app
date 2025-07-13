<?php

namespace Database\Seeders;

use App\Models\LandingPageContent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReferralPageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Hero Section
            [
                'key' => 'referral_hero_title',
                'type' => 'text',
                'value' => 'You Were Referred',
                'label' => 'Hero Title',
                'description' => 'Main headline on the referral landing page',
                'sort_order' => 100,
            ],
            [
                'key' => 'referral_hero_title_accent',
                'type' => 'text',
                'value' => 'for a Reason',
                'label' => 'Hero Title Accent',
                'description' => 'Highlighted part of the hero title (appears in yellow)',
                'sort_order' => 101,
            ],
            [
                'key' => 'referral_hero_subtitle',
                'type' => 'text',
                'value' => 'Exclusive access to a trusted roofing lead generation system.',
                'label' => 'Hero Subtitle',
                'description' => 'Subtitle text below the main headline',
                'sort_order' => 102,
            ],
            [
                'key' => 'referral_hero_cta_text',
                'type' => 'text',
                'value' => 'See If You Qualify',
                'label' => 'Hero CTA Button Text',
                'description' => 'Text for the main call-to-action button',
                'sort_order' => 103,
            ],
            [
                'key' => 'referral_hero_video_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Hero Video URL',
                'description' => 'YouTube embed URL for the hero section video',
                'sort_order' => 104,
            ],
            [
                'key' => 'referral_hero_video_caption',
                'type' => 'text',
                'value' => 'What Makes Volume Up Different',
                'label' => 'Hero Video Caption',
                'description' => 'Caption text below the hero video',
                'sort_order' => 105,
            ],

            // Process Section
            [
                'key' => 'process_section_title',
                'type' => 'text',
                'value' => 'Just Show Up and Quote',
                'label' => 'Process Section Title',
                'description' => 'Main title for the 3-step process section',
                'sort_order' => 200,
            ],
            [
                'key' => 'process_step_1_title',
                'type' => 'text',
                'value' => 'We Run Targeted Ads',
                'label' => 'Process Step 1 Title',
                'description' => 'Title for the first process step',
                'sort_order' => 201,
            ],
            [
                'key' => 'process_step_1_desc',
                'type' => 'textarea',
                'value' => 'Our ads target qualified homeowners in your service area who need roofing work.',
                'label' => 'Process Step 1 Description',
                'description' => 'Description for the first process step',
                'sort_order' => 202,
            ],
            [
                'key' => 'process_step_2_title',
                'type' => 'text',
                'value' => 'We Vet & Qualify',
                'label' => 'Process Step 2 Title',
                'description' => 'Title for the second process step',
                'sort_order' => 203,
            ],
            [
                'key' => 'process_step_2_desc',
                'type' => 'textarea',
                'value' => 'Our team pre-qualifies every lead to ensure they\'re ready to move forward.',
                'label' => 'Process Step 2 Description',
                'description' => 'Description for the second process step',
                'sort_order' => 204,
            ],
            [
                'key' => 'process_step_3_title',
                'type' => 'text',
                'value' => 'Live Transfer to You',
                'label' => 'Process Step 3 Title',
                'description' => 'Title for the third process step',
                'sort_order' => 205,
            ],
            [
                'key' => 'process_step_3_desc',
                'type' => 'textarea',
                'value' => 'Qualified leads are transferred directly to your team, ready to schedule.',
                'label' => 'Process Step 3 Description',
                'description' => 'Description for the third process step',
                'sort_order' => 206,
            ],

            // Video Testimonials Section
            [
                'key' => 'testimonials_section_title',
                'type' => 'text',
                'value' => 'Hear From Contractors Using Volume Up',
                'label' => 'Testimonials Section Title',
                'description' => 'Main title for the video testimonials section',
                'sort_order' => 300,
            ],

            // Video Testimonial 1
            [
                'key' => 'video_testimonial_1_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Video Testimonial 1 URL',
                'description' => 'YouTube embed URL for first video testimonial',
                'sort_order' => 301,
            ],
            [
                'key' => 'video_testimonial_1_name',
                'type' => 'text',
                'value' => 'Tom Wilson',
                'label' => 'Video Testimonial 1 Name',
                'description' => 'Name for first video testimonial',
                'sort_order' => 302,
            ],
            [
                'key' => 'video_testimonial_1_company',
                'type' => 'text',
                'value' => 'Wilson Roofing Co.',
                'label' => 'Video Testimonial 1 Company',
                'description' => 'Company for first video testimonial',
                'sort_order' => 303,
            ],
            [
                'key' => 'video_testimonial_1_quote',
                'type' => 'text',
                'value' => 'Doubled our revenue in 6 months',
                'label' => 'Video Testimonial 1 Quote',
                'description' => 'Quote for first video testimonial',
                'sort_order' => 304,
            ],

            // Video Testimonial 2
            [
                'key' => 'video_testimonial_2_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Video Testimonial 2 URL',
                'description' => 'YouTube embed URL for second video testimonial',
                'sort_order' => 305,
            ],
            [
                'key' => 'video_testimonial_2_name',
                'type' => 'text',
                'value' => 'Maria Garcia',
                'label' => 'Video Testimonial 2 Name',
                'description' => 'Name for second video testimonial',
                'sort_order' => 306,
            ],
            [
                'key' => 'video_testimonial_2_company',
                'type' => 'text',
                'value' => 'Elite Roofing Solutions',
                'label' => 'Video Testimonial 2 Company',
                'description' => 'Company for second video testimonial',
                'sort_order' => 307,
            ],
            [
                'key' => 'video_testimonial_2_quote',
                'type' => 'text',
                'value' => 'Best leads we\'ve ever received',
                'label' => 'Video Testimonial 2 Quote',
                'description' => 'Quote for second video testimonial',
                'sort_order' => 308,
            ],

            // Video Testimonial 3
            [
                'key' => 'video_testimonial_3_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Video Testimonial 3 URL',
                'description' => 'YouTube embed URL for third video testimonial',
                'sort_order' => 309,
            ],
            [
                'key' => 'video_testimonial_3_name',
                'type' => 'text',
                'value' => 'David Chen',
                'label' => 'Video Testimonial 3 Name',
                'description' => 'Name for third video testimonial',
                'sort_order' => 310,
            ],
            [
                'key' => 'video_testimonial_3_company',
                'type' => 'text',
                'value' => 'Premier Roofing & HVAC',
                'label' => 'Video Testimonial 3 Company',
                'description' => 'Company for third video testimonial',
                'sort_order' => 311,
            ],
            [
                'key' => 'video_testimonial_3_quote',
                'type' => 'text',
                'value' => 'Finally, leads that actually close',
                'label' => 'Video Testimonial 3 Quote',
                'description' => 'Quote for third video testimonial',
                'sort_order' => 312,
            ],

            // Video Testimonial 4
            [
                'key' => 'video_testimonial_4_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Video Testimonial 4 URL',
                'description' => 'YouTube embed URL for fourth video testimonial',
                'sort_order' => 313,
            ],
            [
                'key' => 'video_testimonial_4_name',
                'type' => 'text',
                'value' => 'Steve Martinez',
                'label' => 'Video Testimonial 4 Name',
                'description' => 'Name for fourth video testimonial',
                'sort_order' => 314,
            ],
            [
                'key' => 'video_testimonial_4_company',
                'type' => 'text',
                'value' => 'Apex Roofing Services',
                'label' => 'Video Testimonial 4 Company',
                'description' => 'Company for fourth video testimonial',
                'sort_order' => 315,
            ],
            [
                'key' => 'video_testimonial_4_quote',
                'type' => 'text',
                'value' => 'Scaled from 2 to 8 crews',
                'label' => 'Video Testimonial 4 Quote',
                'description' => 'Quote for fourth video testimonial',
                'sort_order' => 316,
            ],

            // Video Testimonial 5
            [
                'key' => 'video_testimonial_5_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Video Testimonial 5 URL',
                'description' => 'YouTube embed URL for fifth video testimonial',
                'sort_order' => 317,
            ],
            [
                'key' => 'video_testimonial_5_name',
                'type' => 'text',
                'value' => 'Jennifer Adams',
                'label' => 'Video Testimonial 5 Name',
                'description' => 'Name for fifth video testimonial',
                'sort_order' => 318,
            ],
            [
                'key' => 'video_testimonial_5_company',
                'type' => 'text',
                'value' => 'Reliable Roofing Inc.',
                'label' => 'Video Testimonial 5 Company',
                'description' => 'Company for fifth video testimonial',
                'sort_order' => 319,
            ],
            [
                'key' => 'video_testimonial_5_quote',
                'type' => 'text',
                'value' => 'ROI exceeded expectations',
                'label' => 'Video Testimonial 5 Quote',
                'description' => 'Quote for fifth video testimonial',
                'sort_order' => 320,
            ],

            // Video Testimonial 6
            [
                'key' => 'video_testimonial_6_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'label' => 'Video Testimonial 6 URL',
                'description' => 'YouTube embed URL for sixth video testimonial',
                'sort_order' => 321,
            ],
            [
                'key' => 'video_testimonial_6_name',
                'type' => 'text',
                'value' => 'Mike Thompson',
                'label' => 'Video Testimonial 6 Name',
                'description' => 'Name for sixth video testimonial',
                'sort_order' => 322,
            ],
            [
                'key' => 'video_testimonial_6_company',
                'type' => 'text',
                'value' => 'Thompson Roofing Co.',
                'label' => 'Video Testimonial 6 Company',
                'description' => 'Company for sixth video testimonial',
                'sort_order' => 323,
            ],
            [
                'key' => 'video_testimonial_6_quote',
                'type' => 'text',
                'value' => 'Game-changer for our business',
                'label' => 'Video Testimonial 6 Quote',
                'description' => 'Quote for sixth video testimonial',
                'sort_order' => 324,
            ],

            // Reviews Section
            [
                'key' => 'reviews_section_title',
                'type' => 'text',
                'value' => 'What Contractors Are Saying',
                'label' => 'Reviews Section Title',
                'description' => 'Main title for the written reviews section',
                'sort_order' => 400,
            ],

            // Review 1
            [
                'key' => 'review_1_name',
                'type' => 'text',
                'value' => 'Michael Rodriguez',
                'label' => 'Review 1 Name',
                'description' => 'Name for first review',
                'sort_order' => 401,
            ],
            [
                'key' => 'review_1_company',
                'type' => 'text',
                'value' => 'Rodriguez Roofing LLC',
                'label' => 'Review 1 Company',
                'description' => 'Company for first review',
                'sort_order' => 402,
            ],
            [
                'key' => 'review_1_location',
                'type' => 'text',
                'value' => 'Houston, TX',
                'label' => 'Review 1 Location',
                'description' => 'Location for first review',
                'sort_order' => 403,
            ],
            [
                'key' => 'review_1_text',
                'type' => 'textarea',
                'value' => 'Volume Up completely transformed our business. We went from struggling to find quality leads to having more appointments than we can handle. The leads are pre-qualified and actually show up!',
                'label' => 'Review 1 Text',
                'description' => 'Review text for first review',
                'sort_order' => 404,
            ],

            // Review 2
            [
                'key' => 'review_2_name',
                'type' => 'text',
                'value' => 'Sarah Johnson',
                'label' => 'Review 2 Name',
                'description' => 'Name for second review',
                'sort_order' => 405,
            ],
            [
                'key' => 'review_2_company',
                'type' => 'text',
                'value' => 'Elite Roofing Solutions',
                'label' => 'Review 2 Company',
                'description' => 'Company for second review',
                'sort_order' => 406,
            ],
            [
                'key' => 'review_2_location',
                'type' => 'text',
                'value' => 'Phoenix, AZ',
                'label' => 'Review 2 Location',
                'description' => 'Location for second review',
                'sort_order' => 407,
            ],
            [
                'key' => 'review_2_text',
                'type' => 'textarea',
                'value' => 'Best investment we\'ve made for our roofing company. The ROI is incredible - we\'re closing 3x more jobs than before. Their team handles everything, we just show up and quote.',
                'label' => 'Review 2 Text',
                'description' => 'Review text for second review',
                'sort_order' => 408,
            ],

            // Review 3
            [
                'key' => 'review_3_name',
                'type' => 'text',
                'value' => 'James Wilson',
                'label' => 'Review 3 Name',
                'description' => 'Name for third review',
                'sort_order' => 409,
            ],
            [
                'key' => 'review_3_company',
                'type' => 'text',
                'value' => 'Wilson Roofing & Construction',
                'label' => 'Review 3 Company',
                'description' => 'Company for third review',
                'sort_order' => 410,
            ],
            [
                'key' => 'review_3_location',
                'type' => 'text',
                'value' => 'Dallas, TX',
                'label' => 'Review 3 Location',
                'description' => 'Location for third review',
                'sort_order' => 411,
            ],
            [
                'key' => 'review_3_text',
                'type' => 'textarea',
                'value' => 'Finally found a lead generation company that actually delivers. No more wasted time on tire kickers. Every lead they send us is ready to move forward with their roofing project.',
                'label' => 'Review 3 Text',
                'description' => 'Review text for third review',
                'sort_order' => 412,
            ],

            // Review 4
            [
                'key' => 'review_4_name',
                'type' => 'text',
                'value' => 'Carlos Martinez',
                'label' => 'Review 4 Name',
                'description' => 'Name for fourth review',
                'sort_order' => 413,
            ],
            [
                'key' => 'review_4_company',
                'type' => 'text',
                'value' => 'Apex Roofing Services',
                'label' => 'Review 4 Company',
                'description' => 'Company for fourth review',
                'sort_order' => 414,
            ],
            [
                'key' => 'review_4_location',
                'type' => 'text',
                'value' => 'San Antonio, TX',
                'label' => 'Review 4 Location',
                'description' => 'Location for fourth review',
                'sort_order' => 415,
            ],
            [
                'key' => 'review_4_text',
                'type' => 'textarea',
                'value' => 'Volume Up helped us scale from a 2-person operation to 12 employees in just 8 months. Their lead quality is unmatched - we\'re booked solid for the next 3 months!',
                'label' => 'Review 4 Text',
                'description' => 'Review text for fourth review',
                'sort_order' => 416,
            ],

            // Review 5
            [
                'key' => 'review_5_name',
                'type' => 'text',
                'value' => 'Lisa Chen',
                'label' => 'Review 5 Name',
                'description' => 'Name for fifth review',
                'sort_order' => 417,
            ],
            [
                'key' => 'review_5_company',
                'type' => 'text',
                'value' => 'Premier Roofing Co.',
                'label' => 'Review 5 Company',
                'description' => 'Company for fifth review',
                'sort_order' => 418,
            ],
            [
                'key' => 'review_5_location',
                'type' => 'text',
                'value' => 'Austin, TX',
                'label' => 'Review 5 Location',
                'description' => 'Location for fifth review',
                'sort_order' => 419,
            ],
            [
                'key' => 'review_5_text',
                'type' => 'textarea',
                'value' => 'The support team is amazing and the leads are exactly what they promised. We\'ve increased our monthly revenue by 250% since partnering with Volume Up. Highly recommend!',
                'label' => 'Review 5 Text',
                'description' => 'Review text for fifth review',
                'sort_order' => 420,
            ],

            // Review 6
            [
                'key' => 'review_6_name',
                'type' => 'text',
                'value' => 'Robert Thompson',
                'label' => 'Review 6 Name',
                'description' => 'Name for sixth review',
                'sort_order' => 421,
            ],
            [
                'key' => 'review_6_company',
                'type' => 'text',
                'value' => 'Thompson Roofing Inc.',
                'label' => 'Review 6 Company',
                'description' => 'Company for sixth review',
                'sort_order' => 422,
            ],
            [
                'key' => 'review_6_location',
                'type' => 'text',
                'value' => 'Fort Worth, TX',
                'label' => 'Review 6 Location',
                'description' => 'Location for sixth review',
                'sort_order' => 423,
            ],
            [
                'key' => 'review_6_text',
                'type' => 'textarea',
                'value' => 'Game changer for our business! No more cold calling or door knocking. Volume Up delivers warm, qualified leads directly to us. Our close rate has never been higher.',
                'label' => 'Review 6 Text',
                'description' => 'Review text for sixth review',
                'sort_order' => 424,
            ],

            // Overall Rating Summary
            [
                'key' => 'overall_rating',
                'type' => 'text',
                'value' => '4.9',
                'label' => 'Overall Rating',
                'description' => 'Overall star rating (e.g., 4.9)',
                'sort_order' => 450,
            ],
            [
                'key' => 'total_reviews',
                'type' => 'text',
                'value' => '247+',
                'label' => 'Total Reviews',
                'description' => 'Total number of reviews (e.g., 247+)',
                'sort_order' => 451,
            ],
            [
                'key' => 'rating_tagline',
                'type' => 'text',
                'value' => 'Trusted by contractors across Texas',
                'label' => 'Rating Tagline',
                'description' => 'Tagline below the overall rating',
                'sort_order' => 452,
            ],

            // Proof Section
            [
                'key' => 'proof_section_title',
                'type' => 'text',
                'value' => 'Real Results, Real Proof',
                'label' => 'Proof Section Title',
                'description' => 'Main title for the proof section',
                'sort_order' => 500,
            ],
            [
                'key' => 'proof_show_rate',
                'type' => 'text',
                'value' => '85%',
                'label' => 'Show Rate Percentage',
                'description' => 'Appointment show rate percentage',
                'sort_order' => 501,
            ],
            [
                'key' => 'proof_show_rate_label',
                'type' => 'text',
                'value' => 'Show Rate',
                'label' => 'Show Rate Label',
                'description' => 'Label for the show rate statistic',
                'sort_order' => 502,
            ],
            [
                'key' => 'proof_show_rate_desc',
                'type' => 'text',
                'value' => 'Average appointment show rate for our qualified leads',
                'label' => 'Show Rate Description',
                'description' => 'Description for the show rate statistic',
                'sort_order' => 503,
            ],
            [
                'key' => 'proof_quality_title',
                'type' => 'text',
                'value' => 'Quality Guarantee',
                'label' => 'Quality Guarantee Title',
                'description' => 'Title for the quality guarantee section',
                'sort_order' => 504,
            ],
            [
                'key' => 'proof_quality_desc',
                'type' => 'text',
                'value' => 'Our leads show up because they\'re pre-qualified and ready',
                'label' => 'Quality Guarantee Description',
                'description' => 'Description for the quality guarantee',
                'sort_order' => 505,
            ],

            // Qualification Form Section
            [
                'key' => 'qualification_title',
                'type' => 'text',
                'value' => 'See If You Qualify',
                'label' => 'Qualification Form Title',
                'description' => 'Main title for the qualification form section',
                'sort_order' => 600,
            ],
            [
                'key' => 'qualification_subtitle',
                'type' => 'text',
                'value' => 'Not every contractor is a good fit. Let\'s see if our system can help scale your business.',
                'label' => 'Qualification Form Subtitle',
                'description' => 'Subtitle for the qualification form section',
                'sort_order' => 601,
            ],
            [
                'key' => 'qualification_button_text',
                'type' => 'text',
                'value' => 'Check My Qualification',
                'label' => 'Qualification Form Button Text',
                'description' => 'Text for the form submit button',
                'sort_order' => 602,
            ],

            // Footer
            [
                'key' => 'footer_tagline',
                'type' => 'text',
                'value' => 'Helping contractors scale with qualified leads',
                'label' => 'Footer Tagline',
                'description' => 'Tagline text in the footer',
                'sort_order' => 700,
            ],
        ];

        foreach ($contents as $content) {
            LandingPageContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }

        $this->command->info('Referral page content seeded successfully!');
    }
}
