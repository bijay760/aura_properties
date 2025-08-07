@extends('layouts.admin.app')

@section('title')
    Add User
@endsection
@section('content')
    <div class="min-h-screen">
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Add New Property</h2>
                <a href="/admin/property" class="bg-neutral-800 text-white px-4 py-2 rounded-md hover:bg-neutral-700">
                    <i class="fas fa-arrow-left mr-1 text-white"></i> Back
                </a>
            </div>

            {{-- <form method="POST" action="{{ route('admin.categories.store') }}"  class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block font-medium">Name</label>
                        <input type="text" name="name" id="name" required
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                      <div>
    <label for="status" class="block font-medium">Status</label>
   <select name="status" id="status"
    class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0">
    <option value="">All</option>
    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
</select>
</div>
                   
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="bg-black text-white px-4 py-2 rounded-md! hover:bg-neutral-700">
                        <i class="fas fa-save mr-1 text-white"></i> Save Category
                    </button>
                </div>
            </form> --}}
        </div>
    </div>
@endsection
