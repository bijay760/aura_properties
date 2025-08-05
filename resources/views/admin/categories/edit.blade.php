@extends('layouts.admin.app')

@section('title')
    Update Categories
@endsection

@section('content')
    <div class="min-h-screen">
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Update Categories</h2>
                <a href="/admin/categories" class="bg-neutral-800 text-white px-4 py-2 rounded-md hover:bg-neutral-700">
                    <i class="fas fa-arrow-left mr-1 text-white"></i> Back
                </a>
            </div>

            
          <form method="POST" action="{{ route('admin.categories.update') }}" class="space-y-6">
    @csrf
    @method('PUT')
       <div class="grid grid-cols-3 gap-4 mb-2 font-semibold  pb-2">
        <div>Name</div>
        <div>Status</div>
        <div>Sort Order</div>
    </div>

    @foreach($categories as $category)
        <div class="grid grid-cols-3 gap-6 mb-5 items-center">
            <input type="text" name="categories[{{ $category->id }}][name]" value="{{ old('categories.' . $category->id . '.name', $category->name) }}"
                   class="px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 " placeholder="Name">

           @php
    $status = old('categories.' . $category->id . '.status', $category->status);
    $selectClass = $status == 1 ? 'text-green-600' : 'text-red-600';
@endphp

<select name="categories[{{ $category->id }}][status]" 
        class=" px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 {{ $selectClass }}" 
        onchange="this.className = this.value == 1 ? 'px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0  text-green-600' : 'px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0  text-red-600'">
    <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
    <option value="0" {{ $status == 0 ? 'selected' : '' }}>Inactive</option>
</select>

            <input type="number" name="categories[{{ $category->id }}][order]" value="{{ old('categories.' . $category->id . '.order', $category->sort_order) }}"
                   class="px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 " placeholder="Order">
        </div>
    @endforeach

    <div class="pt-4">
        <button type="submit" class="bg-black text-white px-4 py-2 rounded-md! hover:bg-neutral-700">
            <i class="fas fa-save mr-1 text-white"></i> Update Categories
        </button>
    </div>
</form>
        </div>
    </div>

@endsection
