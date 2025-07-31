@extends('layouts.admin.app')

@section('title')
    Edit User
@endsection
@section('content')
    <div class="min-h-screen">
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Edit Staff</h2>
                <a href="/admin/user" class="bg-neutral-800 text-white px-4 py-2 rounded-md hover:bg-neutral-700">
                    <i class="fas fa-arrow-left mr-1 text-white"></i> Back
                </a>
            </div>
            <form method="POST" action="{{ route('admin.user.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block font-medium">Name</label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $user->name) }}" required
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                    <div>
                        <label for="email" class="block font-medium">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                    <div>
    <label for="type" class="block font-medium">Account Type</label>
    <select name="type" id="type" required
            class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
     <option value="">-- Select Type --</option>
<option value="1" {{ old('type', $user->type ?? '') == 1 ? 'selected' : '' }}>Buyer</option>
<option value="2" {{ old('type', $user->type ?? '') == 2 ? 'selected' : '' }}>Agent</option>
<option value="3" {{ old('type', $user->type ?? '') == 3 ? 'selected' : '' }}>Builder</option>
    </select>
</div>
                    <div>
                        <label for="password" class="block font-medium">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                   placeholder="Leave blank to keep current"
                                   class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                            <span onclick="togglePassword()"
                                  class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                            <i id="togglePasswordIcon" class="fas fa-eye text-gray-500"></i>
                        </span>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="bg-black text-white px-4 py-2 rounded-md! hover:bg-neutral-700">
                        <i class="fas fa-save mr-1 text-white"></i> Update User
                    </button>
                </div>
            </form>
        </div>
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
@endsection
