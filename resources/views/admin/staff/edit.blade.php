@extends('layouts.admin.app')

@section('title')
    Edit Staff
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
            <form method="POST" action="{{ route('admin.staff.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block font-medium">First Name</label>
                        <input type="text" name="first_name" id="first_name"
                               value="{{ old('first_name', $user->first_name) }}" required
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                    <div>
                        <label for="surname" class="block font-medium">Surname</label>
                        <input type="text" name="surname" id="surname" value="{{ old('surname', $user->surname) }}"
                               required
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                    <div>
                        <label for="email" class="block font-medium">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
                    </div>
                    <div>
    <label for="role_id" class="block font-medium">Role</label>
    <select name="role_id" id="role_id" required
            class="w-full mt-1 px-3 py-2 border! border-neutral-400 rounded-md  focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-0 ">
        <option value="">-- Select Role --</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
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
                        <i class="fas fa-save mr-1 text-white"></i> Update Staff
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
