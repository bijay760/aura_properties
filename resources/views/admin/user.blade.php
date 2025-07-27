<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.stylesheet')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
</head>
<body>
    <div class=" flex gap-2">   @include('components.icons')
  @include('components.sidebar')  <div class=" text-black">
@foreach($users as $user)
    <tr>
      <td class="border px-4 py-2 text-black">{{ $user->name ?? 'No Name' }}</td>
      <td class="border px-4 py-2">{{ $user->email ?? 'No Email' }}</td>
    </tr>
@endforeach

  </div>
    </div>
 

</body>
</html>