<x-guest-layout>
    <!-- Header Section -->
    <div class="text-center mb-6">
        <h1 class="font-poppins text-2xl font-bold text-volume-dark mb-2">
            Join Our Partners
        </h1>
        <p class="text-volume-gray text-sm">
            Start earning commissions today
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Full Name')" class="text-volume-dark font-semibold text-sm" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input id="name" 
                    class="block w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-20 transition-all duration-200 bg-gray-50/50 focus:bg-white text-sm" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    placeholder="Enter your full name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email Address')" class="text-volume-dark font-semibold text-sm" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input id="email" 
                    class="block w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-20 transition-all duration-200 bg-gray-50/50 focus:bg-white text-sm" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autocomplete="username" 
                    placeholder="Enter your email address" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Password')" class="text-volume-dark font-semibold text-sm" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password" 
                    class="block w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-20 transition-all duration-200 bg-gray-50/50 focus:bg-white text-sm"
                    type="password"
                    name="password"
                    required 
                    autocomplete="new-password" 
                    placeholder="Create a secure password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-volume-dark font-semibold text-sm" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <x-text-input id="password_confirmation" 
                    class="block w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-volume-primary focus:ring-volume-primary focus:ring-2 focus:ring-opacity-20 transition-all duration-200 bg-gray-50/50 focus:bg-white text-sm"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Confirm your password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Terms Agreement -->
        <div class="bg-gradient-to-r from-volume-light to-blue-50/50 p-4 rounded-xl border border-volume-primary/20">
            <div class="flex items-start space-x-3">
                <input id="terms" type="checkbox" 
                    class="mt-0.5 h-4 w-4 rounded border-gray-300 text-volume-primary shadow-sm focus:ring-volume-primary focus:ring-2 focus:ring-opacity-20 transition-colors" 
                    name="terms" required>
                <label for="terms" class="text-xs text-volume-dark leading-relaxed">
                    I agree to the 
                    <a href="#" class="text-volume-primary hover:text-volume-secondary font-semibold hover:underline transition-colors">Terms of Service</a> 
                    and 
                    <a href="#" class="text-volume-primary hover:text-volume-secondary font-semibold hover:underline transition-colors">Privacy Policy</a>
                </label>
            </div>
        </div>

        <!-- Create Account Button -->
        <div class="pt-2">
            <button type="submit" 
                class="w-full bg-gradient-to-r from-volume-primary to-volume-secondary text-white py-3 px-6 rounded-xl font-semibold text-sm hover:from-volume-secondary hover:to-volume-primary focus:outline-none focus:ring-4 focus:ring-volume-primary/30 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('Create Partner Account') }}
                </span>
            </button>
        </div>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-3 bg-white text-volume-gray font-medium">Already have an account?</span>
            </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center justify-center w-full px-6 py-2.5 border-2 border-volume-primary text-volume-primary font-semibold rounded-xl hover:bg-volume-primary hover:text-white focus:outline-none focus:ring-4 focus:ring-volume-primary/30 transition-all duration-300 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Sign In Instead
            </a>
        </div>
    </form>

    <!-- Benefits Section -->
    <div class="mt-6 p-4 bg-gradient-to-r from-volume-primary to-volume-secondary rounded-xl text-white">
        <h3 class="font-poppins font-semibold text-sm mb-2">Partner Benefits</h3>
        <ul class="space-y-1 text-xs">
            <li class="flex items-center">
                <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                10% monthly recurring revenue
            </li>
            <li class="flex items-center">
                <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                $250 fast-close bonus
            </li>
            <li class="flex items-center">
                <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                Monthly payouts via ACH/PayPal
            </li>
        </ul>
    </div>
</x-guest-layout>
