<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="min-h-full">
  <nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="shrink-0">
            <img class="size-8" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
                <x-nav-link href="/" :active="request()->is('/')">Bazaar</x-nav-link>
                <x-nav-link href="/" :active="request()->is('')">Verhuur</x-nav-link>
                <x-nav-link href="/" :active="request()->is('')">Veilingen</x-nav-link> 
                <x-nav-link href="/" :active="request()->is('')">Verkoop</x-nav-link>                
            </div>
          </div>
        </div> 
        <div class="hidden md:block">
          <div class="ml-4 flex items-center md:ml-6">
            @auth
            <form action="{{ route('logout') }}" method="POST" class="relative ml-3 flex items-center">
              @csrf
              <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-500">
                  Uitloggen
              </button>
            </form>

            <div class="relative ml-3">
              <div>
                <div class="text-base/5 font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-400">{{ Auth::user()->email }}</div>
              </div>
            </div>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </nav>

  <header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h3 class="text-2xl font-bold tracking-tight text-gray-900">{{ $heading }}</h3>
    </div>
  </header>
  <main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      {{ $slot }}
    </div>
  </main>
</div>
</body>
</html>