@extends('layouts.admin.app')

@section('title')
    Add User
@endsection
@section('content')
<div class="min-h-screen">
    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Add New Staff</h2>
           <Button class="border border-black text-white px-4 py-2 rounded-md!">
                 <a href="{{ route('admin.user.index') }}" class="flex text-black">{!! config('icons.arrow-left') !!} Back</a>  
            </Button>
        </div>

       <form method="POST" action="{{ route('admin.user.store') }}" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="first_name" class="block font-medium">First Name</label>
            <input type="text" name="first_name" id="first_name" required
                   class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div>
            <label for="surname" class="block font-medium">Surname</label>
            <input type="text" name="surname" id="surname" required
                   class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div>
            <label for="email" class="block font-medium">Email</label>
            <input type="email" name="email" id="email" required
                   class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
      <div>
    <label for="password" class="block font-medium">Password</label>
    <div class="relative">
        <input type="password" name="password" id="password"
               class="w-full mt-1 px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300 pr-10" required>
        <span onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
            <i id="togglePasswordIcon" class="fas fa-eye text-gray-500"></i>
        </span>
    </div>
</div>
    </div>

    <div class="pt-4">
        <button type="submit"
                class="bg-black text-white px-4 py-2 rounded-md! hover:bg-neutral-700">
            <i class="fas fa-save mr-1 text-white"></i> Save Staff
        </button>
    </div>
</form>
    </div>
    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
</div>
@endsection
