@extends('layouts.admin.app')
@section('page-specific-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css"
          rel="stylesheet">
@endsection
@section('title')
    Roles Management
@endsection

@section('content')
 <div class="min-h-screen">
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Roles Management</h2>
            <a href="{{ route('admin.user.create') }}"
              class="bg-neutral-800 text-white px-4 py-2 rounded-md hover:bg-neutral-700">
                <i class="fas fa-plus mr-1 text-white"></i> Add Role
            </a>
        </div>
        <table class="min-w-full">
                    <thead class="bg-black text-left  font-semibold text-white">
                    <tr class="">
                    <th class=" px-4 py-2 text-left">ID</th>
                    <th class=" px-4 py-2 text-left">Role Name</th>
                    <th class=" px-4 py-2 text-left">Description</th>
                    <th class=" px-4 py-2 text-left">Created At</th>
                </tr>
            </thead>
            <tbody class=" divide-y divide-gray-100">
                @foreach($roles as $role)
                    <tr class="border-b! border-gray-300 hover:bg-gray-50 transition ease-in-out duration-300">
                        <td class=" px-4 py-2">{{ $role->id }}</td>
                        <td class=" px-4 py-2">{{ $role->name }}</td>
                        <td class=" px-4 py-2">{{ $role->description }}</td>
                        <td class=" px-4 py-2">{{ $role->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
