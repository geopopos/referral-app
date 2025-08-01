<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-poppins font-bold text-xl text-volume-dark leading-tight">
                {{ __('Manage Partners') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="bg-volume-gray hover:bg-volume-dark text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors shadow-lg">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
                <!-- <a href="{{ route('admin.export.leads') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors shadow-lg">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a> -->
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-6 overflow-hidden">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <h3 class="font-poppins text-lg font-bold text-volume-dark mb-6">All partners ({{ $partners->total() }})</h3>
                    
                    @if($partners->count() > 0)
                        <div class="w-full overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-volume-light">
                                    <tr>
                                        <th class="w-1/6 px-3 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Lead Info</th>
                                        <th class="w-1/6 px-3 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Contact</th>
                                        <th class="w-1/12 px-3 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Date</th>
                                        <th class="w-1/4 px-3 py-3 text-left text-xs font-bold text-volume-dark uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($partners as $partner)
                                        <tr class="hover:bg-volume-light transition-colors">
                                            <td class="px-3 py-3">
                                                <div class="truncate">
                                                    <div class="text-sm font-semibold text-volume-dark truncate">{{ $partner->name }}</div>
                                                    <div class="text-xs text-volume-gray truncate">{{ $partner->company }}</div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="truncate">
                                                    <div class="text-sm text-volume-dark truncate">{{ $partner->email }}</div>
                                                    <div class="text-xs text-volume-gray truncate">{{ $partner->phone }}</div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="text-xs text-volume-gray">
                                                    <div class="font-medium">{{ $partner->created_at->format('M j') }}</div>
                                                    <div class="text-xs">{{ $partner->created_at->format('g:i A') }}</div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="flex flex-col space-y-1">
                                                    <a href="{{ route('admin.partners.show', $partner) }}" class="text-volume-primary hover:text-volume-secondary font-semibold transition-colors text-xs">
                                                        Edit
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $partners->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-volume-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-semibold text-volume-dark">No leads found</h3>
                            <p class="mt-2 text-sm text-volume-gray">Leads will appear here once partners start referring contractors.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetails(leadId) {
            const detailsRow = document.getElementById('details-' + leadId);
            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
            } else {
                detailsRow.classList.add('hidden');
            }
        }
    </script>
</x-admin-layout>
