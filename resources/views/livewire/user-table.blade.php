<div class=" p-4 min-h-screen bg-white shadow-xl rounded-xl space-y-4">
    {{-- Filter Section --}}
    <div class="flex flex-col lg:flex-row gap-2">
        <input type="text"
               wire:model.live.debounce.300ms="filterId"
               placeholder="Search by ID"
               class="border border-neutral-400 px-3 py-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
        <input type="text"
               wire:model.live.debounce.300ms="filterName"
               placeholder="Filter by Name"
               class="border border-neutral-400 px-3 py-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
        <input type="text"
               wire:model.live.debounce.300ms="filterEmail"
               placeholder="Filter by Email"
               class="border border-neutral-400 px-3 py-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
        <select wire:model.live="filterType"
                class="border border-neutral-400 text-neutral-500 px-3 py-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <option value="">All Types</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
            <option value="vendor">Vendor</option>
        </select>
        {{-- Clear filters button --}}
        <button wire:click="clearFilters"
                class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition-colors whitespace-nowrap cursor-pointer">
            Clear
        </button>
    </div>

    {{-- Active Filters Display --}}
    @if($filterId || $filterName || $filterEmail || $filterType)
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-sm text-gray-600">Active filters:</span>
            @if($filterId)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                    ID: {{ $filterId }}
                    <button wire:click="$set('filterId', '')" class="ml-1 text-blue-600 hover:text-blue-800">×</button>
                </span>
            @endif
            @if($filterName)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                    Name: {{ $filterName }}
                    <button wire:click="$set('filterName', '')" class="ml-1 text-green-600 hover:text-green-800">×</button>
                </span>
            @endif
            @if($filterEmail)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                    Email: {{ $filterEmail }}
                    <button wire:click="$set('filterEmail', '')" class="ml-1 text-purple-600 hover:text-purple-800">×</button>
                </span>
            @endif
            @if($filterType)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-800">
                    Type: {{ ucfirst($filterType) }}
                    <button wire:click="$set('filterType', '')" class="ml-1 text-orange-600 hover:text-orange-800">×</button>
                </span>
            @endif
        </div>
    @endif

    {{-- Results Summary --}}
    <div class="flex justify-between items-center text-sm text-gray-600">
        @if($users->total() > 0)
          <div class="text-sm text-gray-700">
                    Showing <span class="font-semibold text-gray-900">{{ $users->firstItem() }}</span> - 
                    <span class="font-semibold text-gray-900">{{ $users->lastItem() }}</span> of 
                    <span class="font-semibold text-gray-900">{{ $users->total() }}</span> users
                </div>
                @else
          <div class="text-sm text-gray-700">
                    No users found
                </div>
        @endif
        {{-- Per Page Selector --}}
        @if($users->total() > 10)
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm">Show:</label>
                <select class="font-semibold text-gray-900" wire:model.live="perPage" id="perPage" 
                        class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                    <option  value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm">per page</span>
            </div>
        @endif
    </div>

    {{-- Table Section --}}
    <div class="relative overflow-x-auto rounded-lg border border-gray-200">
        {{-- loading overlay --}}
        <div wire:loading.delay class=" bg-white absolute w-full h-full opacity-45"></div>
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-black text-white text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 font-semibold">ID</th>
                    <th class="px-4 py-3 font-semibold">Name</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Verified</th>
                    <th class="px-4 py-3 font-semibold">Phone</th>
                    <th class="px-4 py-3 font-semibold">Account Type</th>
                    <th class="px-4 py-3 font-semibold">Created</th>
                    <th class="px-4 py-3 font-semibold">Last Login</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $user->id }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Unverified
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $user->phone ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize
                            @if($user->type === 'admin') bg-red-100 text-red-800
                            @elseif($user->type === 'vendor') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($user->type === 'admin')
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            {{ $user->type }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $user->updated_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v1M7 8h10"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
                            <p class="text-gray-500 mb-4">
                                @if($filterId || $filterName || $filterEmail || $filterType)
                                    No users match your current filters.
                                @else
                                    There are no users in the system yet.
                                @endif
                            </p>
                            @if($filterId || $filterName || $filterEmail || $filterType)
                                <button wire:click="clearFilters" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Clear Filters
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Enhanced Pagination --}}
    @if($users->hasPages())
        <div class="bg-gray-50 px-4 py-3 rounded-lg">
            <div class="flex flex-col sm:flex-row justify-end items-center space-y-3 sm:space-y-0">
                {{-- Pagination Controls --}}
                <div class="flex items-center space-x-2">
                    {{-- Previous Button --}}
                    @if ($users->onFirstPage())
                        <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <button wire:click="previousPage" 
                                class="px-3 py-1 text-sm text-black bg-white border border-neutral-400 rounded hover:bg-blue-50 cursor-pointer">
                            Previous
                        </button>
                    @endif
                    
                    {{-- Page Numbers --}}
                    @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="px-3 py-1 text-sm font-semibold text-white bg-black rounded shadow">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" 
                                    class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 cursor-pointer">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                    
                    {{-- Next Button --}}
                    @if ($users->hasMorePages())
                        <button wire:click="nextPage" 
                                class="px-3 py-1 text-sm text-black bg-white border border-neutral-400 rounded hover:bg-blue-50 cursor-pointer">
                            Next
                        </button>
                    @else
                        <span class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>