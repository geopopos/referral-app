<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You - Volume Up Agency</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter antialiased bg-volume-light">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-volume-primary to-volume-secondary rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="font-poppins text-3xl font-bold text-volume-dark">Thank You!</h1>
            <h2 class="mt-4 text-xl font-semibold text-volume-gray">Your submission has been received</h2>
            <p class="mt-4 text-volume-gray leading-relaxed">
                We've received your information and our team will be in touch within 24 hours to discuss how Volume Up Agency can help scale your business.
            </p>
        </div>

        <!-- What Happens Next Section -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-xl sm:px-10 border border-gray-100">
                <div class="text-center space-y-4">
                    <h3 class="font-poppins text-lg font-semibold text-volume-dark">What happens next?</h3>
                    
                    <div class="space-y-4 text-left">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-volume-primary text-white text-sm font-semibold shadow-medium">1</div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-volume-gray font-medium">Our team will review your submission</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-volume-primary text-white text-sm font-semibold shadow-medium">2</div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-volume-gray font-medium">Schedule your consultation using the calendar below</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-volume-primary text-white text-sm font-semibold shadow-medium">3</div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-volume-gray font-medium">We'll create a custom growth strategy for your business</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Calendar Section -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-xl sm:px-10 border border-gray-100">
                <div class="text-center mb-6">
                    <h3 class="font-poppins text-2xl font-bold text-volume-dark">Schedule Your Strategy Call</h3>
                    <p class="mt-2 text-volume-gray leading-relaxed">
                        Book a free 30-minute strategy call with our team to discuss how we can help grow your business.
                    </p>
                </div>
                
                <div class="w-full">
                    <iframe 
                        src="https://link.volumeup-agency.com/widget/booking/PAQyFkwqYfaLcHK5zKwr" 
                        style="width: 100%; border: none; overflow: hidden; min-height: 600px;" 
                        scrolling="no" 
                        id="jMlp9ARqltGcxEfYnXA3_1752253126892"
                        class="rounded-xl"
                    ></iframe>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="mt-8 text-center">
            <p class="text-sm text-volume-gray mb-4 font-medium">
                Have questions in the meantime?
            </p>
            <div class="space-y-2">
                <p class="text-sm text-volume-gray">
                    Email: <a href="mailto:hello@volumeupagency.com" class="text-volume-primary hover:text-volume-secondary font-semibold transition-colors">hello@volumeupagency.com</a>
                </p>
                <p class="text-sm text-volume-gray">
                    Phone: <a href="tel:+1234567890" class="text-volume-primary hover:text-volume-secondary font-semibold transition-colors">(123) 456-7890</a>
                </p>
            </div>
        </div>

        <!-- Return to Homepage Button -->
        <div class="mt-8 text-center">
            <a 
                href="{{ route('home') }}" 
                class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-volume-primary to-volume-secondary hover:from-volume-secondary hover:to-volume-primary focus:outline-none focus:ring-4 focus:ring-volume-primary/30 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Return to Homepage
            </a>
        </div>
    </div>

    <!-- Include the booking widget script -->
    <script src="https://link.volumeup-agency.com/js/form_embed.js" type="text/javascript"></script>
</body>
</html>
