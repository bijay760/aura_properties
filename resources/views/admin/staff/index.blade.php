@extends('layouts.admin.app')
@section('page-specific-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css"
          rel="stylesheet">
@endsection
@section('title')
    User Management
@endsection

@section('content')
    <div class="min-h-screen">
        <div class=" space-y-6">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Staff Management</h2>
                <a href="{{ route('admin.staff.create') }}"
                   class="bg-neutral-800 text-white px-4 py-2 rounded-md hover:bg-neutral-700">
                    <i class="fas fa-plus mr-1 text-white"></i> Add Staff
                </a>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.staff.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                       <div>
                        <label for="id" class="block  font-medium">Id</label>
                        <input type="text" id="id" name="id" value="{{ request('id') }}"
                               placeholder="Filter by id"
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                    <div>
                        <label for="email" class="block  font-medium">Email</label>
                        <input type="text" id="email" name="email" value="{{ request('email') }}"
                               placeholder="Filter by email"
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                    <div>
                        <label for="name" class="block  font-medium">Name</label>
                        <input type="text" id="name" name="name" value="{{ request('name') }}"
                               placeholder="Filter by name"
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                 
                    <div>
                        <label for="created_at" class="block  font-medium">Created At</label>
                        <input type="text" id="created_at" name="created_at" value="{{ request('created_at') }}"
                               placeholder="Filter by date"
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 "
                        >
                    </div>
                    <div>
    <label for="role" class="block font-medium">Role</label>
    <select name="role" id="role"
              class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
        <option value="">All Roles</option>
        @foreach($rolesList as $role)
            <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
</div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="submit" class="bg-black text-white px-4 py-2 rounded-md!">
                        <i class="fas fa-filter mr-1 text-white"></i> Filter
                    </button>
                    <a href="{{ route('admin.staff.index') }}"
                       class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
                        <i class="fas fa-sync-alt mr-1"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Staff Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-black text-left  font-semibold text-white">
                    <tr class="">
                        <th class="px-4 py-2 ">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                         <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Token</th>
                        <th class="px-4 py-2">Login</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody class=" divide-y divide-gray-100">
                    @forelse($staffs as $staff)
                        <tr class="border-b! border-gray-300 hover:bg-gray-50 transition ease-in-out duration-300">
                            <td class="px-4 py-2">{{ $staff->id }}</td>
                            <td class="px-4 py-2">{{ $staff->first_name }} {{ $staff->surname }}</td>
                            <td class="px-4 py-2">{{ $staff->email }}</td>
                             <td class="px-4 py-2">{{ $staff->role }}</td>
                            <td class="px-4 py-2">{{ $staff->created_at }}</td>
                           
              <td class="px-4 py-2">
    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $staff->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
        {{ $staff->email_verified_at ? 'Verified' : 'Not Verified' }}
    </span>
</td>
 <td class="px-4 py-2">{{ $staff->token }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($staff->updated_at)->diffForHumans() }}</td>


                            <td class="px-4 py-2 space-x-1 flex gap-1">
                                <a href="{{ route('admin.staff.edit', $staff->id) }}"
                                   class="" title="Edit">
                                    {!! config('icons.edit') !!}
                                </a>
                                <form action="{{ route('admin.staff.destroy',$staff->id)}}" method="POST"
                                      class="inline-block" onsubmit="return confirm('Are you sure?')">
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
                            <td colspan="5" class="text-center px-4 py-2 text-gray-500">No staff found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
<div class="flex justify-between items-center">
    @php
        $paginator = $staffs;
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
    @endphp

    @if ($paginator->hasPages())
        <div class="text-lg text-gray-600 text-center mb-2">
            Showing <span class="font-semibold text-black">{{ ($current - 1) * $paginator->perPage() + 1 }} -
                {{ min($current * $paginator->perPage(), $paginator->total()) }}</span> of
            <span class="font-semibold text-black">{{ $paginator->total() }}</span> results
        </div>

        <div class="flex gap-2 justify-center">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 bg-gray-300 text-gray-600 rounded">Prev</span>
            @else
                <a href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1 bg-black text-white rounded">Prev</a>
            @endif

            {{-- Page Numbers --}}
            @php
                $start = max(2, $current - 2);
                $end = min($last - 1, $current + 2);
            @endphp

            {{-- First Page --}}
            <a href="{{ $paginator->appends(request()->query())->url(1) }}"
               class="px-3 py-1 border border-black text-black rounded {{ $current === 1 ? 'bg-black text-white' : '' }}">1</a>

            {{-- Dots before --}}
            @if ($start > 2)
                <span class="px-2">...</span>
            @endif

            {{-- Middle Pages --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $current)
                    <span class="px-3 py-1 bg-black text-white rounded">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->appends(request()->query())->url($i) }}"
                       class="px-3 py-1 border border-black text-black rounded">{{ $i }}</a>
                @endif
            @endfor

            {{-- Dots after --}}
            @if ($end < $last - 1)
                <span class="px-2">...</span>
            @endif

            {{-- Last Page --}}
            @if ($last > 1)
                <a href="{{ $paginator->appends(request()->query())->url($last) }}"
                   class="px-3 py-1 border border-black text-black rounded {{ $current === $last ? 'bg-black text-white' : '' }}">{{ $last }}</a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->appends(request()->query())->nextPageUrl() }}"
                   class="px-3 py-1 bg-black text-white rounded">Next</a>
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
