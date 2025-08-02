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
          <a href="/admin/staff" class="block p-1 hover:underline">Staff</a>
          <a href="/admin/user" class="block p-1 hover:underline">Users</a>
        </div>
      </div>

      <!-- Property Group -->
      <div>
        <div class="flex items-center p-2 rounded">
           {!! config('icons.property') !!}
          <a href="/admin/property" class="ml-3 font-semibold">Property</a>
        </div>
      </div>
      <div>
        <div class="flex items-center p-2 rounded">
           {!! config('icons.layer') !!}
          <a href="/admin/categories" class="ml-3 font-semibold">Categories</a>
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
  <!-- Toast container -->
<div id="toast" class="fixed top-4 right-4 bg-gray-800 text-white px-4 py-2 rounded shadow-md opacity-0 pointer-events-none transition-opacity duration-300 z-50"></div>
<script>
  function showToast(message, duration = 3000) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.remove('opacity-0', 'pointer-events-none');
    toast.classList.add('opacity-100');

    setTimeout(() => {
      toast.classList.add('opacity-0', 'pointer-events-none');
      toast.classList.remove('opacity-100');
    }, duration);
  }

  // Show Laravel flash messages as toast on page load
  window.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
      showToast(@json(session('success')));
    @elseif(session('error'))
      showToast(@json(session('error')));
    @endif
  });
</script>

</div>
