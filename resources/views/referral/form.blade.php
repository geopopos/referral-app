<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['referral_hero_title']->value ?? 'You Were Referred' }} {{ $content['referral_hero_title_accent']->value ?? 'for a Reason' }} | Volume Up Agency</title>
    <meta name="description" content="{{ $content['referral_hero_subtitle']->value ?? 'Exclusive access to a trusted roofing lead generation system. See if you qualify for our proven lead generation program.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-inter text-volume-dark antialiased">
    <!-- Header -->
    <header class="bg-white shadow-soft py-6 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <img src="{{ asset('images/logo.webp') }}" alt="Volume Up Agency Logo" class="h-12 mx-auto">
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-volume-primary to-volume-secondary text-white py-16 sm:py-20 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="font-poppins font-bold text-4xl md:text-5xl mb-6 leading-tight">
                        {{ $content['referral_hero_title']->value ?? 'You Were Referred' }}<br>
                        <span class="text-yellow-300">{{ $content['referral_hero_title_accent']->value ?? 'for a Reason' }}</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 font-medium">
                        {{ $content['referral_hero_subtitle']->value ?? 'Exclusive access to a trusted roofing lead generation system.' }}
                    </p>
                    <a href="#qualification-form" class="inline-block bg-white text-volume-primary px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                        {{ $content['referral_hero_cta_text']->value ?? 'See If You Qualify' }}
                    </a>
                </div>
                <div class="bg-white/10 rounded-lg p-6">
                    <div class="aspect-video bg-black/20 rounded-lg flex items-center justify-center">
                        <iframe width="100%" height="100%" src="{{ $content['referral_hero_video_url']->value ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ' }}" title="{{ $content['referral_hero_video_caption']->value ?? 'What Makes Volume Up Different' }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-lg"></iframe>
                    </div>
                    <p class="text-center mt-4 text-sm opacity-90">{{ $content['referral_hero_video_caption']->value ?? 'What Makes Volume Up Different' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Do Section -->
    <section class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-16">
                {{ $content['process_section_title']->value ?? 'Just Show Up and Quote' }}
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-volume-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white font-bold text-2xl">1</span>
                    </div>
                    <h3 class="font-poppins font-semibold text-xl mb-4">{{ $content['process_step_1_title']->value ?? 'We Run Targeted Ads' }}</h3>
                    <p class="text-gray-600">{{ $content['process_step_1_desc']->value ?? 'Our ads target qualified homeowners in your service area who need roofing work.' }}</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-volume-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white font-bold text-2xl">2</span>
                    </div>
                    <h3 class="font-poppins font-semibold text-xl mb-4">{{ $content['process_step_2_title']->value ?? 'We Vet & Qualify' }}</h3>
                    <p class="text-gray-600">{{ $content['process_step_2_desc']->value ?? 'Our team pre-qualifies every lead to ensure they\'re ready to move forward.' }}</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-volume-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-white font-bold text-2xl">3</span>
                    </div>
                    <h3 class="font-poppins font-semibold text-xl mb-4">{{ $content['process_step_3_title']->value ?? 'Live Transfer to You' }}</h3>
                    <p class="text-gray-600">{{ $content['process_step_3_desc']->value ?? 'Qualified leads are transferred directly to your team, ready to schedule.' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 px-4 bg-volume-light">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-16">
                {{ $content['testimonials_section_title']->value ?? 'Hear From Contractors Using Volume Up' }}
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                        <div class="aspect-video bg-gray-200 flex items-center justify-center">
                            <iframe width="100%" height="100%" src="{{ $content['video_testimonial_' . $i . '_url']->value ?? 'https://www.youtube.com/embed/dQw4w9WgXcQ' }}" title="Contractor Testimonial {{ $i }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="p-6">
                            <h4 class="font-semibold text-lg mb-2">{{ $content['video_testimonial_' . $i . '_name']->value ?? 'Contractor Name' }}</h4>
                            <p class="text-gray-600 text-sm">{{ $content['video_testimonial_' . $i . '_company']->value ?? 'Company Name' }}</p>
                            <p class="text-sm mt-2 italic">"{{ $content['video_testimonial_' . $i . '_quote']->value ?? 'Great results with Volume Up' }}"</p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-16">
                {{ $content['reviews_section_title']->value ?? 'What Contractors Are Saying' }}
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="bg-volume-light p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <div class="flex text-yellow-400 text-lg">
                                ★★★★★
                            </div>
                            <span class="ml-2 text-sm text-gray-600">5.0</span>
                        </div>
                        <p class="text-gray-700 mb-4 italic">"{{ $content['review_' . $i . '_text']->value ?? 'Great experience with Volume Up. Highly recommend their services.' }}"</p>
                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-lg">{{ $content['review_' . $i . '_name']->value ?? 'Customer Name' }}</h4>
                            <p class="text-gray-600 text-sm">{{ $content['review_' . $i . '_company']->value ?? 'Company Name' }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ $content['review_' . $i . '_location']->value ?? 'Location' }}</p>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Overall Rating Summary -->
            <div class="mt-16 text-center">
                <div class="bg-gradient-to-r from-volume-primary to-volume-secondary text-white p-8 rounded-lg inline-block">
                    <div class="flex items-center justify-center mb-4">
                        <div class="text-4xl font-bold mr-4">{{ $content['overall_rating']->value ?? '4.9' }}</div>
                        <div>
                            <div class="flex text-yellow-300 text-2xl mb-1">★★★★★</div>
                            <div class="text-sm opacity-90">Based on {{ $content['total_reviews']->value ?? '247+' }} reviews</div>
                        </div>
                    </div>
                    <p class="text-lg font-medium">{{ $content['rating_tagline']->value ?? 'Trusted by contractors across Texas' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Proof Section -->
    <section class="py-20 px-4 bg-volume-light">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-16">
                {{ $content['proof_section_title']->value ?? 'Real Results, Real Proof' }}
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-volume-light p-6 rounded-lg">
                    <div class="bg-white h-48 rounded-lg mb-4 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <span class="text-gray-500">Campaign Performance Screenshot</span>
                    </div>
                    <h4 class="font-semibold text-lg mb-2">Campaign Stats</h4>
                    <p class="text-gray-600 text-sm">Live campaign performance showing qualified lead generation</p>
                </div>
                <div class="bg-volume-light p-6 rounded-lg">
                    <div class="bg-white h-48 rounded-lg mb-4 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <span class="text-gray-500">Booked Calendar Screenshot</span>
                    </div>
                    <h4 class="font-semibold text-lg mb-2">Booked Appointments</h4>
                    <p class="text-gray-600 text-sm">Real calendar showing scheduled appointments from our leads</p>
                </div>
                <div class="bg-volume-light p-6 rounded-lg md:col-span-2 lg:col-span-1">
                    <div class="bg-gradient-to-br from-volume-primary to-volume-secondary text-white p-6 rounded-lg h-48 flex flex-col justify-center">
                        <div class="text-3xl font-bold mb-2">{{ $content['proof_show_rate']->value ?? '85%' }}</div>
                        <div class="text-lg font-semibold mb-2">{{ $content['proof_show_rate_label']->value ?? 'Show Rate' }}</div>
                        <div class="text-sm opacity-90">{{ $content['proof_show_rate_desc']->value ?? 'Average appointment show rate for our qualified leads' }}</div>
                    </div>
                    <h4 class="font-semibold text-lg mb-2 mt-4">{{ $content['proof_quality_title']->value ?? 'Quality Guarantee' }}</h4>
                    <p class="text-gray-600 text-sm">{{ $content['proof_quality_desc']->value ?? 'Our leads show up because they\'re pre-qualified and ready' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Qualification Form Section -->
    <section id="qualification-form" class="py-20 px-4 bg-volume-light">
        <div class="max-w-2xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-8">
                {{ $content['qualification_title']->value ?? 'See If You Qualify' }}
            </h2>
            <p class="text-center text-gray-600 mb-12 text-lg">
                {{ $content['qualification_subtitle']->value ?? 'Not every contractor is a good fit. Let\'s see if our system can help scale your business.' }}
            </p>
            
            <form method="POST" action="{{ route('referral.store') }}" class="bg-white p-8 rounded-lg shadow-lg space-y-6">
                @csrf
                <input type="hidden" name="referral_code" value="{{ request('ref') }}">
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-transparent">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-transparent">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-transparent">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                    <input type="text" id="company" name="company" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-transparent">
                    @error('company')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="how_did_you_hear" class="block text-sm font-medium text-gray-700 mb-2">How did you hear about us?</label>
                    <select id="how_did_you_hear" name="how_did_you_hear" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-volume-primary focus:border-transparent">
                        <option value="">Select an option</option>
                        <option value="Referral Partner">Referral Partner</option>
                        <option value="Google Search">Google Search</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Industry Event">Industry Event</option>
                        <option value="Word of Mouth">Word of Mouth</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full bg-volume-primary text-white py-4 px-8 rounded-lg text-lg font-semibold hover:bg-volume-secondary transition-colors">
                    {{ $content['qualification_button_text']->value ?? 'Check My Qualification' }}
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-volume-secondary text-white py-12 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <img src="{{ asset('images/logo.webp') }}" alt="Volume Up Agency Logo" class="h-10 w-auto mx-auto mb-4 brightness-0 invert">
            <p class="text-gray-300 mb-6">{{ $content['footer_tagline']->value ?? 'Helping contractors scale with qualified leads' }}</p>
            <div class="text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Volume Up Agency. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth Scrolling Script -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Extract referral code from URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const referralCode = urlParams.get('ref');
            
            if (referralCode) {
                const hiddenInput = document.querySelector('input[name="referral_code"]');
                if (hiddenInput) {
                    hiddenInput.value = referralCode;
                }
            }
        });
    </script>
</body>
</html>
