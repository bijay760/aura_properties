<!DOCTYPE html>
<html lang="en">
<head>
    @livewireStyles
    @include('admin.stylesheet')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
    <div class="flex gap-2 min-h-screen">
        @include('components.icons')
        @include('components.sidebar')
        <div class="flex-1">
            <livewire:user-table />
        </div>
    </div>
    @livewireScripts
</body>
</html>
