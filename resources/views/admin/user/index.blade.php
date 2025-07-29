@extends('layouts.admin.app')
@section('page-specific-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
@endsection
@section('title')
    User Management
@endsection

@section('content')
<div class="min-h-screen">
    <div class=" p-6 space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">User Management</h2>
            <a href="{{ route('admin.user.index') }}" class="bg-neutral-800 text-white px-4 py-2 rounded-md hover:bg-neutral-700">
                <i class="fas fa-plus mr-1 text-white"></i> Add User
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.user.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="email" class="block  font-medium">Email</label>
                    <input type="text" id="email" name="email" value="{{ request('email') }}"
                        placeholder="Filter by email"
                        class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label for="first_name" class="block  font-medium">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ request('first_name') }}"
                        placeholder="Filter by first name"
                        class="w-full mt-1 px-3 py-2 border rounded-md  focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label for="surname" class="block  font-medium">Surname</label>
                    <input type="text" id="surname" name="surname" value="{{ request('surname') }}"
                        placeholder="Filter by surname"
                        class="w-full mt-1 px-3 py-2 border rounded-md  focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label for="created_at" class="block  font-medium">Created At</label>
                    <input type="text" id="created_at" name="created_at" value="{{ request('created_at') }}"
                        placeholder="Filter by date"
                        class="datepicker w-full mt-1 px-3 py-2 border rounded-md  focus:outline-none focus:ring focus:border-blue-300">
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="submit" class="bg-black text-white px-4 py-2 rounded-md!">
                    <i class="fas fa-filter mr-1 text-white"></i> Filter
                </button>
                <a href="{{ route('admin.user.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
                    <i class="fas fa-sync-alt mr-1"></i> Reset
                </a>
            </div>
        </form>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-black text-left  font-semibold text-white">
                    <tr class="">
                        <th class="px-4 py-2 ">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class=" divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $user->id }}</td>
                            <td class="px-4 py-2">{{ $user->first_name }} {{ $user->surname }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->created_at }}</td>
                            <td class="px-4 py-2 space-x-1 flex gap-1">
                                <a href="{{ route('admin.user.index', $user->id) }}"
                                    class="" title="Edit">
                                     {!! config('icons.edit') !!}
                                </a>
                                <form action="" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="" title="Delete">
                                        {!! config('icons.trash') !!}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-4 py-2 text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        {{-- <div>
            {{ $users->appends(request()->query())->links() }}
        </div> --}}
<div class="flex justify-between items-center">
  @php
    $paginator = $users;
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
@endphp

@if ($paginator->hasPages())
    <div class="text-sm text-gray-600 text-center mb-2">
        Showing <span class=" font-semibold text-black">{{ ($current - 1) * $paginator->perPage() + 1 }} -
        {{ min($current * $paginator->perPage(), $paginator->total()) }}</span>  of
    <span class=" font-semibold text-black">{{ $paginator->total() }}</span> results
    </div>

    <div class="flex gap-2 justify-center">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 bg-gray-300 text-gray-600 rounded">Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 bg-black text-white rounded">Prev</a>
        @endif
        {{-- Page Numbers --}}
        @php
            $start = max(2, $current - 2);
            $end = min($last - 1, $current + 2);
        @endphp
        {{-- First Page --}}
        <a href="{{ $paginator->url(1) }}" class="px-3 py-1 border border-black text-black rounded {{ $current === 1 ? 'bg-black text-white' : '' }}">1</a>
        {{-- Dots before --}}
        @if ($start > 2)
            <span class="px-2">...</span>
        @endif
        {{-- Middle Pages --}}
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $current)
                <span class="px-3 py-1 bg-black text-white rounded">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}" class="px-3 py-1 border border-black text-black rounded">{{ $i }}</a>
            @endif
        @endfor
        {{-- Dots after --}}
        @if ($end < $last - 1)
            <span class="px-2">...</span>
        @endif
        {{-- Last Page --}}
        @if ($last > 1)
            <a href="{{ $paginator->url($last) }}" class="px-3 py-1 border border-black text-black rounded {{ $current === $last ? 'bg-black text-white' : '' }}">{{ $last }}</a>
        @endif

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 bg-black text-white rounded">Next</a>
        @else
            <span class="px-3 py-1 bg-gray-300 text-gray-600 rounded">Next</span>
        @endif
    </div>
@endif
        </div>
    </div>
</div>
@endsection

@section('page-specific-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            $('.datepicker').datepicker({
                orientation: "bottom",
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection
