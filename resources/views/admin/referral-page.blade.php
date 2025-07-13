<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Referral Page Content Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Manage Referral Landing Page Content</h3>
                        <p class="text-sm text-gray-600">Update all content that appears on the referral landing page (/referral). Changes will be reflected immediately.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.referral-page.update') }}" class="space-y-6">
                        @csrf

                        @php
                            $sections = [
                                'Hero Section' => $contents->filter(fn($c) => str_starts_with($c->key, 'referral_hero')),
                                'Process Section' => $contents->filter(fn($c) => str_starts_with($c->key, 'process_')),
                                'Video Testimonials' => $contents->filter(fn($c) => str_starts_with($c->key, 'video_testimonial_') || str_starts_with($c->key, 'testimonials_')),
                                'Written Reviews' => $contents->filter(fn($c) => str_starts_with($c->key, 'review_') || str_starts_with($c->key, 'reviews_')),
                                'Overall Rating' => $contents->filter(fn($c) => str_starts_with($c->key, 'overall_') || str_starts_with($c->key, 'total_') || str_starts_with($c->key, 'rating_')),
                                'Proof Section' => $contents->filter(fn($c) => str_starts_with($c->key, 'proof_')),
                                'Qualification Form' => $contents->filter(fn($c) => str_starts_with($c->key, 'qualification_')),
                                'Footer' => $contents->filter(fn($c) => str_starts_with($c->key, 'footer_')),
                            ];
                        @endphp

                        @foreach ($sections as $sectionName => $sectionContents)
                            @if ($sectionContents->count() > 0)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">
                                        {{ $sectionName }}
                                    </h4>
                                    
                                    <div class="grid gap-4">
                                        @foreach ($sectionContents->sortBy('sort_order') as $content)
                                            <div class="bg-gray-50 p-4 rounded-md">
                                                <label for="content_{{ $content->key }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                    {{ $content->label }}
                                                </label>
                                                
                                                @if ($content->description)
                                                    <p class="text-xs text-gray-500 mb-2">{{ $content->description }}</p>
                                                @endif

                                                @if ($content->type === 'textarea')
                                                    <textarea 
                                                        id="content_{{ $content->key }}" 
                                                        name="content[{{ $content->key }}]" 
                                                        rows="3"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        required
                                                    >{{ $content->value }}</textarea>
                                                @elseif ($content->type === 'url')
                                                    <input 
                                                        type="url" 
                                                        id="content_{{ $content->key }}" 
                                                        name="content[{{ $content->key }}]" 
                                                        value="{{ $content->value }}"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        placeholder="https://www.youtube.com/embed/..."
                                                        required
                                                    >
                                                    @if (str_contains($content->key, 'video'))
                                                        <p class="text-xs text-gray-500 mt-1">Use YouTube embed URL format: https://www.youtube.com/embed/VIDEO_ID</p>
                                                    @endif
                                                @else
                                                    <input 
                                                        type="text" 
                                                        id="content_{{ $content->key }}" 
                                                        name="content[{{ $content->key }}]" 
                                                        value="{{ $content->value }}"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        required
                                                    >
                                                @endif

                                                @error("content.{$content->key}")
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                                ← Back to Admin Dashboard
                            </a>
                            
                            <div class="flex gap-4">
                                <a href="{{ route('referral.form', ['ref' => 'preview']) }}" target="_blank" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                    Preview Referral Page
                                </a>
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                    Update Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Content Management Tips -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Management Guide</h3>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">Hero Section</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Main headline and accent text</li>
                                <li>• Subtitle and CTA button</li>
                                <li>• Hero video URL and caption</li>
                            </ul>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-900 mb-2">Process Steps</h4>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• 3-step process titles</li>
                                <li>• Step descriptions</li>
                                <li>• Section title</li>
                            </ul>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-medium text-purple-900 mb-2">Video Testimonials</h4>
                            <ul class="text-sm text-purple-700 space-y-1">
                                <li>• 6 contractor video testimonials</li>
                                <li>• Names, companies, quotes</li>
                                <li>• YouTube embed URLs</li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-medium text-yellow-900 mb-2">Written Reviews</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• 6 detailed contractor reviews</li>
                                <li>• Names, companies, locations</li>
                                <li>• Review text and ratings</li>
                            </ul>
                        </div>

                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <h4 class="font-medium text-indigo-900 mb-2">Proof & Stats</h4>
                            <ul class="text-sm text-indigo-700 space-y-1">
                                <li>• Overall rating (4.9)</li>
                                <li>• Total reviews count</li>
                                <li>• Show rate percentage</li>
                            </ul>
                        </div>

                        <div class="bg-red-50 p-4 rounded-lg">
                            <h4 class="font-medium text-red-900 mb-2">Form Section</h4>
                            <ul class="text-sm text-red-700 space-y-1">
                                <li>• Qualification form title</li>
                                <li>• Form subtitle</li>
                                <li>• Button text</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Optimization Tips</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Keep headlines clear and benefit-focused</li>
                            <li>• Use specific numbers and statistics for credibility</li>
                            <li>• Ensure video URLs are YouTube embed format</li>
                            <li>• Test different headlines and CTAs for better conversion</li>
                            <li>• Keep testimonials authentic and specific</li>
                            <li>• Update reviews regularly to maintain freshness</li>
                            <li>• Use action-oriented language in CTAs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
