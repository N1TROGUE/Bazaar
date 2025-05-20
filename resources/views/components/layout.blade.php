<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
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
               <x-nav-link href="{{ route('index') }}" :active="request()->routeIs('index')">Bazaar</x-nav-link>
               
               <!-- Alleen zichtbaar voor adverteerders -->
               @if(Auth::check() && Auth::user()->isAdvertiser())  <!-- Controleer of de gebruiker een adverteerder is -->
                <x-nav-link href="{{ route('advertisements.create') }}" :active="request()->routeIs('advertisements.create')">Plaats advertentie</x-nav-link>
                <x-nav-link href="{{ route('advertisements.show') }}" :active="request()->routeIs('advertisements.show')">Mijn advertenties</x-nav-link>
               @endif

               <!-- Alleen zichtbaar voor admins -->
              @if(Auth::check() && Auth::user()->isAdmin())  <!-- Controleer of de gebruiker een admin is -->
                  <x-nav-link href="{{ route('upload.contract') }}" :active="request()->routeIs('upload.contract')">Upload contract</x-nav-link>
                  <x-nav-link href="{{ route('export.registration') }}" :active="request()->routeIs('export.registration')">Exporteer registratie</x-nav-link>
              @endif

            </div>
        </div>                      
        </div> 
        <div class="hidden md:block">
          <!-- Instellingen icoon -->
          <div class="ml-4 flex items-center md:ml-6">
            @auth
            <a href="" class="text-white hover:text-gray-300 mr-4" title="Instellingen">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894a1.125 1.125 0 001.671.772l.737-.426a1.125 1.125 0 011.45.186l.773.774c.39.389.44 1.002.186 1.45l-.426.737a1.125 1.125 0 00.772 1.67l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149a1.125 1.125 0 00-.772 1.671l.426.737c.254.448.204 1.06-.186 1.45l-.773.773a1.125 1.125 0 01-1.449.186l-.738-.426a1.125 1.125 0 00-1.67.772l-.15.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.149-.894a1.125 1.125 0 00-1.671-.772l-.737.426c-.448.254-1.06.204-1.45-.186l-.773-.773a1.125 1.125 0 01-.186-1.449l.426-.738a1.125 1.125 0 00-.772-1.67l-.894-.15c-.542-.09-.94-.56-.94-1.11v-1.094c0-.55.398-1.019.94-1.11l.894-.149c.36-.06.66-.3.772-.64.113-.34.028-.705-.186-.992l-.426-.738a1.125 1.125 0 01.186-1.45l.773-.773a1.125 1.125 0 011.45-.186l.737.426a1.125 1.125 0 001.671-.772l.149-.894z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </a>
            
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
