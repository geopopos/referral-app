<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Landing Page Content Management') }}
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
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Manage Landing Page Content</h3>
                        <p class="text-sm text-gray-600">Update the content that appears on the home page. Changes will be reflected immediately.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.landing-page.update') }}" class="space-y-6">
                        @csrf

                        @foreach ($contents as $content)
                            <div class="border border-gray-200 rounded-lg p-4">
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

                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-800">
                                ← Back to Admin Dashboard
                            </a>
                            
                            <div class="flex gap-4">
                                <a href="{{ route('home') }}" target="_blank" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                    Preview Landing Page
                                </a>
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                    Update Content
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Content Preview Section -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Content Organization</h3>
                    
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-900 mb-2">Hero Section</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Hero Title</li>
                                <li>• Hero Subtitle</li>
                            </ul>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-900 mb-2">Commission Details</h4>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• Commission Percentage</li>
                                <li>• Quick Close Bonus</li>
                            </ul>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-medium text-purple-900 mb-2">Testimonials</h4>
                            <ul class="text-sm text-purple-700 space-y-1">
                                <li>• 3 Video Testimonials</li>
                                <li>• Names & Titles</li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-medium text-yellow-900 mb-2">Earnings Example</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Contractor Monthly Fee</li>
                                <li>• Monthly Earning</li>
                                <li>• Annual Potential</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Tips for Content Management</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Keep headlines clear and compelling</li>
                            <li>• Use specific numbers for credibility (10%, $250, etc.)</li>
                            <li>• Ensure video URLs are YouTube embed format</li>
                            <li>• Test changes by previewing the landing page</li>
                            <li>• Consider A/B testing different headlines</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
