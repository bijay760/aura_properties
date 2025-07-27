   <nav class="bg-neutral-800 p-4 text-white w-40 h-screen space-y-2">
      <p href="#" class="block p-2 hover:bg-gray-700 rounded">Dashboard</p>
       {{-- <a href="#" class="flex gap-2 p-2 hover:bg-gray-700 rounded"> {!! iconUser() !!}Profile</a> --}}
         <a href="/admin/users" class=" flex gap-2 p-2 hover:bg-gray-700 rounded">
             {!! iconUsers() !!}
Users</a>
    <a href="#" class=" flex gap-2 p-2 hover:bg-gray-700 rounded">
             {!! iconProperty() !!}
Property</a>
  <a href="#" class="flex gap-2 p-2 hover:bg-gray-700 rounded">{!! iconCategory() !!}Category</a>
      <a href="#" class="flex gap-2 p-2 hover:bg-gray-700 rounded">{!! iconSetting() !!}Settings</a>
    </nav>