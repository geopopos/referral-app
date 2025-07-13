<x-guest-layout>
    <!-- Header Section -->
    <div class="text-center mb-6">
        <h1 class="font-poppins text-2xl font-bold text-volume-dark mb-2">
            Welcome Back
        </h1>
        <p class="text-volume-gray text-sm">
            Sign in to your partner dashboard
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

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
                    autofocus 
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
                    autocomplete="current-password" 
                    placeholder="Enter your password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" 
                    class="h-4 w-4 rounded border-gray-300 text-volume-primary shadow-sm focus:ring-volume-primary focus:ring-2 focus:ring-opacity-20 transition-colors" 
                    name="remember">
                <label for="remember_me" class="ml-3 text-sm text-volume-gray font-medium">
                    {{ __('Remember me') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-sm text-volume-primary hover:text-volume-secondary font-medium transition-colors duration-200 hover:underline" 
                   href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Sign In Button -->
        <div class="pt-2">
            <button type="submit" 
                class="w-full bg-gradient-to-r from-volume-primary to-volume-secondary text-white py-3.5 px-6 rounded-xl font-semibold text-base hover:from-volume-secondary hover:to-volume-primary focus:outline-none focus:ring-4 focus:ring-volume-primary/30 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Sign In to Dashboard') }}
                </span>
            </button>
        </div>

        <!-- Divider -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-volume-gray font-medium">New to our platform?</span>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-sm text-volume-gray mb-3">
                Join our partner program and start earning commissions
            </p>
            <a href="{{ route('register') }}" 
               class="inline-flex items-center justify-center w-full px-6 py-3 border-2 border-volume-primary text-volume-primary font-semibold rounded-xl hover:bg-volume-primary hover:text-white focus:outline-none focus:ring-4 focus:ring-volume-primary/30 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Become a Partner
            </a>
        </div>
    </form>
</x-guest-layout>
