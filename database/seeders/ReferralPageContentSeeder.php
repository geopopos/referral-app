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
                'value' => 'https://www.youtube.com/embed/3r18Ejq_7So',
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
                'value' => 'https://www.youtube.com/embed/3ftXWL7cYV4',
                'label' => 'Video Testimonial 1 URL',
                'description' => 'YouTube embed URL for first video testimonial',
                'sort_order' => 301,
            ],
            [
                'key' => 'video_testimonial_1_name',
                'type' => 'text',
                'value' => 'Roberto',
                'label' => 'Video Testimonial 1 Name',
                'description' => 'Name for first video testimonial',
                'sort_order' => 302,
            ],
            [
                'key' => 'video_testimonial_1_company',
                'type' => 'text',
                'value' => 'Honesty Roofing Miami',
                'label' => 'Video Testimonial 1 Company',
                'description' => 'Company for first video testimonial',
                'sort_order' => 303,
            ],
            [
                'key' => 'video_testimonial_1_quote',
                'type' => 'text',
                'value' => 'We\'ve tested with different agencies and this is the best result we\'ve gotten',
                'label' => 'Video Testimonial 1 Quote',
                'description' => 'Quote for first video testimonial',
                'sort_order' => 304,
            ],

            // Video Testimonial 2
            [
                'key' => 'video_testimonial_2_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/MMh_TQV4_8g',
                'label' => 'Video Testimonial 2 URL',
                'description' => 'YouTube embed URL for second video testimonial',
                'sort_order' => 305,
            ],
            [
                'key' => 'video_testimonial_2_name',
                'type' => 'text',
                'value' => 'Jesse Bergen',
                'label' => 'Video Testimonial 2 Name',
                'description' => 'Name for second video testimonial',
                'sort_order' => 306,
            ],
            [
                'key' => 'video_testimonial_2_company',
                'type' => 'text',
                'value' => 'Roofing Contractor, Alberta Canada',
                'label' => 'Video Testimonial 2 Company',
                'description' => 'Company for second video testimonial',
                'sort_order' => 307,
            ],
            [
                'key' => 'video_testimonial_2_quote',
                'type' => 'text',
                'value' => 'Did about 54k off of them but the potential is astronomical',
                'label' => 'Video Testimonial 2 Quote',
                'description' => 'Quote for second video testimonial',
                'sort_order' => 308,
            ],

            // Video Testimonial 3
            [
                'key' => 'video_testimonial_3_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/PxK_OwuYVfA',
                'label' => 'Video Testimonial 3 URL',
                'description' => 'YouTube embed URL for third video testimonial',
                'sort_order' => 309,
            ],
            [
                'key' => 'video_testimonial_3_name',
                'type' => 'text',
                'value' => 'Dwayne Diekhoff',
                'label' => 'Video Testimonial 3 Name',
                'description' => 'Name for third video testimonial',
                'sort_order' => 310,
            ],
            [
                'key' => 'video_testimonial_3_company',
                'type' => 'text',
                'value' => 'Quality Home Services, Terre Haute Indiana',
                'label' => 'Video Testimonial 3 Company',
                'description' => 'Company for third video testimonial',
                'sort_order' => 311,
            ],
            [
                'key' => 'video_testimonial_3_quote',
                'type' => 'text',
                'value' => 'Revenue jumped from 50k to 121k - Volume Up is definitely worth it',
                'label' => 'Video Testimonial 3 Quote',
                'description' => 'Quote for third video testimonial',
                'sort_order' => 312,
            ],

            // Video Testimonial 4
            [
                'key' => 'video_testimonial_4_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/c9GHOXfuCss',
                'label' => 'Video Testimonial 4 URL',
                'description' => 'YouTube embed URL for fourth video testimonial',
                'sort_order' => 313,
            ],
            [
                'key' => 'video_testimonial_4_name',
                'type' => 'text',
                'value' => 'Umair',
                'label' => 'Video Testimonial 4 Name',
                'description' => 'Name for fourth video testimonial',
                'sort_order' => 314,
            ],
            [
                'key' => 'video_testimonial_4_company',
                'type' => 'text',
                'value' => '99 Developers, Calgary Alberta',
                'label' => 'Video Testimonial 4 Company',
                'description' => 'Company for fourth video testimonial',
                'sort_order' => 315,
            ],
            [
                'key' => 'video_testimonial_4_quote',
                'type' => 'text',
                'value' => 'Got first lead within 12 hours, closed 3 out of 4 meetings',
                'label' => 'Video Testimonial 4 Quote',
                'description' => 'Quote for fourth video testimonial',
                'sort_order' => 316,
            ],

            // Video Testimonial 5
            [
                'key' => 'video_testimonial_5_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/TErwYTUlRmg',
                'label' => 'Video Testimonial 5 URL',
                'description' => 'YouTube embed URL for fifth video testimonial',
                'sort_order' => 317,
            ],
            [
                'key' => 'video_testimonial_5_name',
                'type' => 'text',
                'value' => 'Vee Velagic',
                'label' => 'Video Testimonial 5 Name',
                'description' => 'Name for fifth video testimonial',
                'sort_order' => 318,
            ],
            [
                'key' => 'video_testimonial_5_company',
                'type' => 'text',
                'value' => 'Honest Abe Roofing Orlando',
                'label' => 'Video Testimonial 5 Company',
                'description' => 'Company for fifth video testimonial',
                'sort_order' => 319,
            ],
            [
                'key' => 'video_testimonial_5_quote',
                'type' => 'text',
                'value' => 'Had record months in November/December - if it\'s not broken don\'t fix it',
                'label' => 'Video Testimonial 5 Quote',
                'description' => 'Quote for fifth video testimonial',
                'sort_order' => 320,
            ],

            // Video Testimonial 6
            [
                'key' => 'video_testimonial_6_url',
                'type' => 'url',
                'value' => 'https://www.youtube.com/embed/B3YZay4w-mY',
                'label' => 'Video Testimonial 6 URL',
                'description' => 'YouTube embed URL for sixth video testimonial',
                'sort_order' => 321,
            ],
            [
                'key' => 'video_testimonial_6_name',
                'type' => 'text',
                'value' => 'Bobby Wilson',
                'label' => 'Video Testimonial 6 Name',
                'description' => 'Name for sixth video testimonial',
                'sort_order' => 322,
            ],
            [
                'key' => 'video_testimonial_6_company',
                'type' => 'text',
                'value' => 'Jeranco Roofing',
                'label' => 'Video Testimonial 6 Company',
                'description' => 'Company for sixth video testimonial',
                'sort_order' => 323,
            ],
            [
                'key' => 'video_testimonial_6_quote',
                'type' => 'text',
                'value' => 'Over 60 lead sets so far - results are astronomical compared to other companies',
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

            // Review 1 - Brian Arleth
            [
                'key' => 'review_1_name',
                'type' => 'text',
                'value' => 'Brian Arleth',
                'label' => 'Review 1 Name',
                'description' => 'Name for first review',
                'sort_order' => 401,
            ],
            [
                'key' => 'review_1_company',
                'type' => 'text',
                'value' => 'Volume Up Agency Partner',
                'label' => 'Review 1 Company',
                'description' => 'Company for first review',
                'sort_order' => 402,
            ],
            [
                'key' => 'review_1_location',
                'type' => 'text',
                'value' => 'Business Partner',
                'label' => 'Review 1 Location',
                'description' => 'Location for first review',
                'sort_order' => 403,
            ],
            [
                'key' => 'review_1_text',
                'type' => 'textarea',
                'value' => 'We have been using Volume Up Agency in our business for generating leads for the last year. Their expertise in the targeted digital space is unmatched from anything we have seen before. Their drive for innovation and their ability to extract massive amounts of data and make real time adjustments to our accounts to better target the intended market is just incredible. This has been a valuable partnership for our business.',
                'label' => 'Review 1 Text',
                'description' => 'Review text for first review',
                'sort_order' => 404,
            ],

            // Review 2 - Certified Quality Roofing
            [
                'key' => 'review_2_name',
                'type' => 'text',
                'value' => 'Certified Quality Roofing',
                'label' => 'Review 2 Name',
                'description' => 'Name for second review',
                'sort_order' => 405,
            ],
            [
                'key' => 'review_2_company',
                'type' => 'text',
                'value' => 'Certified Quality Roofing',
                'label' => 'Review 2 Company',
                'description' => 'Company for second review',
                'sort_order' => 406,
            ],
            [
                'key' => 'review_2_location',
                'type' => 'text',
                'value' => 'Roofing Company',
                'label' => 'Review 2 Location',
                'description' => 'Location for second review',
                'sort_order' => 407,
            ],
            [
                'key' => 'review_2_text',
                'type' => 'textarea',
                'value' => 'Working with Volume Up Agency has been an absolute pleasure. They\'re incredibly attentive, providing clear communication and support every step of the way. Most importantly, their transparency and the quality of their analytics have been invaluable in helping us make informed decisions. Highly recommend their services!',
                'label' => 'Review 2 Text',
                'description' => 'Review text for second review',
                'sort_order' => 408,
            ],

            // Review 3 - Clay Cain
            [
                'key' => 'review_3_name',
                'type' => 'text',
                'value' => 'Clay Cain',
                'label' => 'Review 3 Name',
                'description' => 'Name for third review',
                'sort_order' => 409,
            ],
            [
                'key' => 'review_3_company',
                'type' => 'text',
                'value' => 'Roofing Contractor',
                'label' => 'Review 3 Company',
                'description' => 'Company for third review',
                'sort_order' => 410,
            ],
            [
                'key' => 'review_3_location',
                'type' => 'text',
                'value' => 'Business Owner',
                'label' => 'Review 3 Location',
                'description' => 'Location for third review',
                'sort_order' => 411,
            ],
            [
                'key' => 'review_3_text',
                'type' => 'textarea',
                'value' => 'Would not be the successful business we are without them. Daily communication, weekly meetings and updates. Always adjusting to the given circumstances and keeping the business coming.',
                'label' => 'Review 3 Text',
                'description' => 'Review text for third review',
                'sort_order' => 412,
            ],

            // Review 4 - Thalia Guzman
            [
                'key' => 'review_4_name',
                'type' => 'text',
                'value' => 'Thalia Guzman',
                'label' => 'Review 4 Name',
                'description' => 'Name for fourth review',
                'sort_order' => 413,
            ],
            [
                'key' => 'review_4_company',
                'type' => 'text',
                'value' => 'Small Startup Company',
                'label' => 'Review 4 Company',
                'description' => 'Company for fourth review',
                'sort_order' => 414,
            ],
            [
                'key' => 'review_4_location',
                'type' => 'text',
                'value' => 'Business Owner',
                'label' => 'Review 4 Location',
                'description' => 'Location for fourth review',
                'sort_order' => 415,
            ],
            [
                'key' => 'review_4_text',
                'type' => 'textarea',
                'value' => 'This is the first digital marketing team that actually got the job done. George is your go-to person. We\'re a small company, barely starting up. However, it\'s the first company that actually got us results with his advertising strategy. In just 4 sales, we have generated revenue of $20k in the first two weeks! We only spent $700.40, with 4 sales equating to $175.10 per sale, basically a 2914.83% return on ad spend!',
                'label' => 'Review 4 Text',
                'description' => 'Review text for fourth review',
                'sort_order' => 416,
            ],

            // Review 5 - Gerald Shears
            [
                'key' => 'review_5_name',
                'type' => 'text',
                'value' => 'Gerald Shears',
                'label' => 'Review 5 Name',
                'description' => 'Name for fifth review',
                'sort_order' => 417,
            ],
            [
                'key' => 'review_5_company',
                'type' => 'text',
                'value' => 'Roofing Contractor',
                'label' => 'Review 5 Company',
                'description' => 'Company for fifth review',
                'sort_order' => 418,
            ],
            [
                'key' => 'review_5_location',
                'type' => 'text',
                'value' => 'Business Owner',
                'label' => 'Review 5 Location',
                'description' => 'Location for fifth review',
                'sort_order' => 419,
            ],
            [
                'key' => 'review_5_text',
                'type' => 'textarea',
                'value' => 'I am extremely impressed with how much knowledge volume up agency has. Georgie explained so many things that other agencies failed to do. Volume up made it easy for me to navigate my google page with ease. I\'ve finally found an agency that does what they say they will. Thank you George and the volume up team god bless.',
                'label' => 'Review 5 Text',
                'description' => 'Review text for fifth review',
                'sort_order' => 420,
            ],

            // Review 6 - Jesse Berggren
            [
                'key' => 'review_6_name',
                'type' => 'text',
                'value' => 'Jesse Berggren',
                'label' => 'Review 6 Name',
                'description' => 'Name for sixth review',
                'sort_order' => 421,
            ],
            [
                'key' => 'review_6_company',
                'type' => 'text',
                'value' => 'J.G.B Contractors Ltd.',
                'label' => 'Review 6 Company',
                'description' => 'Company for sixth review',
                'sort_order' => 422,
            ],
            [
                'key' => 'review_6_location',
                'type' => 'text',
                'value' => 'Edmonton, Alberta',
                'label' => 'Review 6 Location',
                'description' => 'Location for sixth review',
                'sort_order' => 423,
            ],
            [
                'key' => 'review_6_text',
                'type' => 'textarea',
                'value' => 'I own a smaller roofing company in Edmonton, Alberta. Was looking to expand and turn a new page in 2021. Unfortunately fell on some hard time. Volume up was my last shot at keeping my business alive and thriving. I was hesitant at first, however I went for it and invested the last bit of money I had, and it paid off exponentially. My first month they supplied me with 250-300 leads. George the CEO is awesome. Very informative and communicates excellently.',
                'label' => 'Review 6 Text',
                'description' => 'Review text for sixth review',
                'sort_order' => 424,
            ],

            // Overall Rating Summary
            [
                'key' => 'overall_rating',
                'type' => 'text',
                'value' => '5.0',
                'label' => 'Overall Rating',
                'description' => 'Overall star rating (e.g., 5)',
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
                'value' => 'Trusted by contractors nationwide',
                'label' => 'Rating Tagline',
                'description' => 'Tagline below the overall rating',
                'sort_order' => 452,
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
