@extends('layouts.admin.app')
@section('page-specific-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css"
          rel="stylesheet">
@endsection
@section('title')
    Add Role
@endsection

@section('content')
 <div class="min-h-screen">
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Add Role</h2>
           <a href="/admin/role" class="bg-neutral-800 text-white px-4 py-2 rounded-md hover:bg-neutral-700">
                    <i class="fas fa-arrow-left mr-1 text-white"></i> Back
                </a>
        </div>
<form class="w-full max-w-6xl mx-auto p-6 bg-white rounded-md shadow space-y-8">
  <!-- Role Name -->
  <div>
    <label for="role_name" class="block font-medium mb-1">Role Name</label>
    <input type="text" name="role_name" id="role_name" required
      class="w-full px-3 py-2 border border-neutral-400 rounded-md focus:outline-none focus:ring-2 focus:ring-neutral-500">
  </div>

    <button type="submit"
                            class="bg-black text-white px-4 py-2 rounded-md! hover:bg-neutral-700">
                        <i class="fas fa-save mr-1 text-white"></i>Create Role
                    </button>
</form>
     
    </div>

</div>
@endsection
