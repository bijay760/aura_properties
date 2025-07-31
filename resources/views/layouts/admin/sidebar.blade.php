<div class="flex h-screen fixed">
  <!-- Sidebar -->
  <div class="bg-black text-white w-64 flex flex-col">
    <!-- Logo -->
    <div class="flex items-center px-4 py-4">
      <span class="text-xl font-bold">Logo</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 space-y-2 overflow-y-auto">
      <!-- Dashboard -->
      <a href="#" class="flex items-center p-2 rounded ">
       {!! config('icons.dashboard') !!}
        <span class="ml-3 text-white font-semibold ">Dashboard</span>
      </a>
       <a href="/admin/role" class="flex items-center p-2 rounded ">
       {!! config('icons.shield') !!}
        <span class="ml-3 text-white font-semibold ">Roles</span>
      </a>

      <!-- People Group -->
      <div>
        <div class="flex items-center p-2 rounded">
          {!! config('icons.people') !!}
          <span class="ml-3 font-semibold">People</span>
        </div>
        <div class="ml-10 space-y-1">
          <a href="/admin/user" class="block p-1 hover:underline">Staff</a>
          <a href="#" class="block p-1 hover:underline">Users</a>
        </div>
      </div>

      <!-- Property Group -->
      <div>
        <div class="flex items-center p-2 rounded">
           {!! config('icons.property') !!}
          <span class="ml-3 font-semibold">Property</span>
        </div>
        <div class="ml-10 space-y-1">
          <a href="#" class="block p-1 hover:underline">Land</a>
          <a href="#" class="block p-1 hover:underline">Apartment</a>
        </div>
      </div>

      <!-- Extra Item -->
      <div class="flex items-center p-2 rounded  cursor-pointer">
        {!! config('icons.user') !!}
        <span class="ml-3 font-semibold">Bijaya</span>
      </div>
    </nav>

    <!-- Copyright -->
    <div class="py-2 text-center font-semibold">
      <span>{{ date('Y') }} © </span>
      <a class=" text-white" href="#" target="_blank">Aura Property</a>
    </div>
  </div>

</div>
