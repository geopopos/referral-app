<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earn Monthly Revenue Referring Roofing & HVAC Contractors | Volume Up Agency</title>
    <meta name="description" content="Join the Volume Up Partner Program and earn 10% monthly recurring revenue for every contractor you refer. Plus $250 bonus for quick closes.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="font-inter text-volume-dark antialiased">
    <!-- Header -->
    <header class="bg-white shadow-soft py-4 px-4 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto">
            <nav class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/logo.webp') }}" alt="Volume Up Agency" class="h-10 w-auto">
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" 
                       class="text-gray-700 hover:text-volume-primary px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-volume-primary text-white hover:bg-volume-secondary px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200 shadow-medium">
                        Get Started
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button type="button" 
                            class="text-gray-700 hover:text-volume-primary focus:outline-none focus:text-volume-primary transition-colors duration-200"
                            onclick="toggleMobileMenu()"
                            aria-label="Toggle mobile menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </nav>
            
            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-200 animate-slide-down">
                <div class="flex flex-col space-y-3 pt-4">
                    <a href="{{ route('login') }}" 
                       class="text-gray-700 hover:text-volume-primary px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 text-center">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-volume-primary text-white hover:bg-volume-secondary px-6 py-2 rounded-lg text-sm font-semibold transition-colors duration-200 shadow-medium text-center">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-volume-primary to-volume-secondary text-white py-16 sm:py-20 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="font-poppins font-bold text-4xl md:text-6xl mb-6 leading-tight">
                Earn Monthly Revenue Referring<br>
                <span class="text-yellow-300">Roofing & HVAC Contractors</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto font-medium">
                Earn 10% monthly recurring revenue — every month your referral stays with us.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#partner-cta" class="bg-white text-volume-primary px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-100 transition-all duration-200 shadow-medium">
                    Become a Partner
                </a>
                <a href="#testimonials" class="border-2 border-white text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white hover:text-volume-primary transition-all duration-200">
                    Watch How It Works
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 sm:py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-12 sm:mb-16">
                How It Works
            </h2>
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <div class="text-center group">
                    <div class="w-20 h-20 bg-volume-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-medium group-hover:scale-105 transition-transform duration-200">
                        <span class="text-white font-bold text-2xl">1</span>
                    </div>
                    <h3 class="font-poppins font-semibold text-xl mb-4 text-volume-dark">Refer a Contractor</h3>
                    <p class="text-volume-gray leading-relaxed">Connect us with roofing or HVAC contractors who need more qualified leads.</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 bg-volume-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-medium group-hover:scale-105 transition-transform duration-200">
                        <span class="text-white font-bold text-2xl">2</span>
                    </div>
                    <h3 class="font-poppins font-semibold text-xl mb-4 text-volume-dark">We Close & Onboard</h3>
                    <p class="text-volume-gray leading-relaxed">Our team handles the sales process and gets them set up with our lead generation system.</p>
                </div>
                <div class="text-center group">
                    <div class="w-20 h-20 bg-volume-primary rounded-full flex items-center justify-center mx-auto mb-6 shadow-medium group-hover:scale-105 transition-transform duration-200">
                        <span class="text-white font-bold text-2xl">3</span>
                    </div>
                    <h3 class="font-poppins font-semibold text-xl mb-4 text-volume-dark">You Earn Monthly</h3>
                    <p class="text-volume-gray leading-relaxed">Receive 10% of their monthly retainer every month they stay with us.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Commission Details Section -->
    <section class="py-16 sm:py-20 px-4 bg-volume-light">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-12 sm:mb-16 text-volume-dark">
                Commission & Bonus Details
            </h2>
            <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-medium text-center hover:shadow-strong transition-shadow duration-300">
                    <div class="text-4xl sm:text-5xl font-bold text-volume-primary mb-4">10%</div>
                    <h3 class="font-poppins font-semibold text-xl mb-4 text-volume-dark">Monthly Revenue Share</h3>
                    <p class="text-volume-gray leading-relaxed">Earn 10% of every monthly retainer payment from your referred contractors.</p>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-medium text-center hover:shadow-strong transition-shadow duration-300">
                    <div class="text-4xl sm:text-5xl font-bold text-volume-primary mb-4">$250</div>
                    <h3 class="font-poppins font-semibold text-xl mb-4 text-volume-dark">Quick Close Bonus</h3>
                    <p class="text-volume-gray leading-relaxed">Get an extra $250 bonus if your referral closes within 7 days.</p>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-medium text-center hover:shadow-strong transition-shadow duration-300">
                    <div class="text-4xl sm:text-5xl font-bold text-volume-primary mb-4">Monthly</div>
                    <h3 class="font-poppins font-semibold text-xl mb-4 text-volume-dark">Reliable Payouts</h3>
                    <p class="text-volume-gray leading-relaxed">Paid monthly via ACH or PayPal. Set it and forget it income.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Earnings Calculator Section -->
    <section class="py-16 sm:py-20 px-4 bg-white">
        <div class="max-w-4xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-12 sm:mb-16 text-volume-dark">
                Calculate Your Earnings Potential
            </h2>
            
            <!-- Calculator Form -->
            <div class="bg-gradient-to-r from-volume-primary to-volume-secondary text-white p-6 sm:p-8 rounded-2xl shadow-strong mb-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Input Controls -->
                    <div class="space-y-6">
                        <h3 class="font-poppins font-semibold text-2xl mb-6">Your Inputs:</h3>
                        
                        <!-- Referrals per month -->
                        <div>
                            <label for="referrals-slider" class="block text-sm font-medium mb-2">
                                Referrals per month: <span id="referrals-display" class="font-bold">5</span>
                            </label>
                            <input type="range" id="referrals-slider" min="1" max="20" value="5" 
                                   class="w-full h-2 bg-white/20 rounded-lg appearance-none cursor-pointer slider">
                        </div>
                        
                        <!-- Average retainer -->
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Average monthly retainer:
                            </label>
                            <div class="w-full p-3 rounded-lg bg-white text-volume-dark font-semibold text-center">
                                $3,000/month
                            </div>
                        </div>
                    </div>
                    
                    <!-- Results Display -->
                    <div class="space-y-4">
                        <h3 class="font-poppins font-semibold text-2xl mb-6">Your Potential Earnings:</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-90">Expected closes (80% rate):</span>
                                <span id="expected-closes" class="font-bold text-lg">4.0</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-90">Monthly commission:</span>
                                <span id="monthly-commission" class="font-bold text-lg">$1,200</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-90">Quick close bonuses:</span>
                                <span id="quick-close-bonuses" class="font-bold text-lg">$250</span>
                            </div>
                            
                            <div class="border-t border-white/30 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">Total monthly earnings:</span>
                                    <span id="total-monthly" class="font-bold text-2xl text-yellow-300">$1,450</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="font-semibold">Annual potential:</span>
                                <span id="annual-potential" class="font-bold text-xl text-yellow-300">$17,400</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Assumptions -->
                <div class="mt-6 pt-6 border-t border-white/30">
                    <p class="text-sm opacity-90 text-center">
                        <strong>Assumptions:</strong> 80% close rate • ~20% quick close rate • {{ $calculatorData['commission_percentage'] }}% commission • ${{ number_format($calculatorData['quick_close_bonus'], 0) }} quick close bonus
                    </p>
                </div>
            </div>
            
            <!-- Motivational Message -->
            <div class="text-center">
                <div class="text-5xl font-bold mb-4">💰</div>
                <p class="text-lg text-volume-gray">Passive income that grows with every successful referral</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 sm:py-20 px-4 bg-volume-light">
        <div class="max-w-6xl mx-auto">
            <h2 class="font-poppins font-bold text-3xl md:text-4xl text-center mb-12 sm:mb-16 text-volume-dark">
                Hear From Our Partners
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="bg-white rounded-2xl overflow-hidden shadow-medium hover:shadow-strong transition-shadow duration-300">
                    <div class="aspect-video bg-gray-200 flex items-center justify-center">
                        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Partner Testimonial 1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-t-2xl"></iframe>
                    </div>
                    <div class="p-6">
                        <h4 class="font-semibold text-lg mb-2 text-volume-dark">Sarah Johnson</h4>
                        <p class="text-volume-gray text-sm">Marketing Agency Owner</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-medium hover:shadow-strong transition-shadow duration-300">
                    <div class="aspect-video bg-gray-200 flex items-center justify-center">
                        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Partner Testimonial 2" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-t-2xl"></iframe>
                    </div>
                    <div class="p-6">
                        <h4 class="font-semibold text-lg mb-2 text-volume-dark">Mike Rodriguez</h4>
                        <p class="text-volume-gray text-sm">Industry Consultant</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-medium hover:shadow-strong transition-shadow duration-300 md:col-span-2 lg:col-span-1">
                    <div class="aspect-video bg-gray-200 flex items-center justify-center">
                        <iframe width="100%" height="100%" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Partner Testimonial 3" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-t-2xl"></iframe>
                    </div>
                    <div class="p-6">
                        <h4 class="font-semibold text-lg mb-2 text-volume-dark">Jennifer Chen</h4>
                        <p class="text-volume-gray text-sm">Sales Professional</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Call-to-Action Section -->
    <section id="partner-cta" class="py-16 sm:py-20 px-4 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="font-poppins font-bold text-3xl md:text-5xl text-center mb-8 text-volume-dark">
                Ready to Start Earning?
            </h2>
            <p class="text-xl text-volume-gray mb-12 max-w-2xl mx-auto leading-relaxed">
                Join hundreds of partners already earning monthly recurring revenue. 
                It takes less than 2 minutes to get started.
            </p>
            
            <div class="bg-gradient-to-r from-volume-primary to-volume-secondary p-6 sm:p-8 rounded-2xl text-white mb-12 shadow-strong">
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2">2 min</div>
                        <div class="text-sm opacity-90">Quick Setup</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2">10%</div>
                        <div class="text-sm opacity-90">Monthly Revenue</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-2">$250</div>
                        <div class="text-sm opacity-90">Quick Close Bonus</div>
                    </div>
                </div>
                <p class="text-lg opacity-95">
                    Start referring contractors today and build your passive income stream
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-volume-primary text-white px-10 py-4 rounded-xl text-xl font-semibold hover:bg-volume-secondary focus:bg-volume-secondary focus:outline-none focus:ring-2 focus:ring-volume-primary focus:ring-opacity-50 transition-all duration-200 shadow-medium">
                    Become a Partner Now
                </a>
                <a href="{{ route('login') }}" class="border-2 border-volume-primary text-volume-primary px-10 py-4 rounded-xl text-xl font-semibold hover:bg-volume-primary hover:text-white focus:bg-volume-primary focus:text-white focus:outline-none focus:ring-2 focus:ring-volume-primary focus:ring-opacity-50 transition-all duration-200">
                    Partner Login
                </a>
            </div>
            
            <p class="text-sm text-volume-gray mt-6">
                No setup fees • No monthly costs • Cancel anytime
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-volume-secondary text-white py-12 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <img src="{{ asset('images/logo.webp') }}" alt="Volume Up Agency" class="h-10 w-auto mx-auto mb-4 brightness-0 invert">
            <p class="text-gray-300 mb-6">Helping contractors scale with qualified leads</p>
            <div class="text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Volume Up Agency. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle function
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

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

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.add('hidden');
            });
        });

        // Earnings Calculator
        document.addEventListener('DOMContentLoaded', function() {
            // Calculator settings from Laravel
            const calculatorSettings = {
                commissionPercentage: {{ $calculatorData['commission_percentage'] }},
                quickCloseBonus: {{ $calculatorData['quick_close_bonus'] }},
                closeRate: 0.8, // 80%
                quickCloseRate: 0.2 // 20% of closes are quick closes
            };

            // Get calculator elements
            const referralsSlider = document.getElementById('referrals-slider');
            const referralsDisplay = document.getElementById('referrals-display');
            const expectedClosesEl = document.getElementById('expected-closes');
            const monthlyCommissionEl = document.getElementById('monthly-commission');
            const quickCloseBonusesEl = document.getElementById('quick-close-bonuses');
            const totalMonthlyEl = document.getElementById('total-monthly');
            const annualPotentialEl = document.getElementById('annual-potential');

            // Fixed monthly retainer value
            const monthlyRetainer = 3000;

            // Format currency
            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }

            // Calculate earnings
            function calculateEarnings() {
                const referralsPerMonth = parseInt(referralsSlider.value);
                
                // Calculate expected closes (80% close rate)
                const expectedCloses = referralsPerMonth * calculatorSettings.closeRate;
                
                // Calculate monthly commission (10% of retainer × closes)
                const monthlyCommission = expectedCloses * monthlyRetainer * (calculatorSettings.commissionPercentage / 100);
                
                // Calculate quick close bonuses (20% of closes get quick close bonus)
                const quickCloses = expectedCloses * calculatorSettings.quickCloseRate;
                const quickCloseBonuses = quickCloses * calculatorSettings.quickCloseBonus;
                
                // Calculate totals
                const totalMonthly = monthlyCommission + quickCloseBonuses;
                const annualPotential = totalMonthly * 12;

                // Update display
                referralsDisplay.textContent = referralsPerMonth;
                expectedClosesEl.textContent = expectedCloses.toFixed(1);
                monthlyCommissionEl.textContent = formatCurrency(monthlyCommission);
                quickCloseBonusesEl.textContent = formatCurrency(quickCloseBonuses);
                totalMonthlyEl.textContent = formatCurrency(totalMonthly);
                annualPotentialEl.textContent = formatCurrency(annualPotential);
            }

            // Add event listeners
            referralsSlider.addEventListener('input', calculateEarnings);

            // Initial calculation
            calculateEarnings();
        });
    </script>
</body>
</html>
