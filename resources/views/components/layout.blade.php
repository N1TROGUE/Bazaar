<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
<div class="min-h-full">
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <img class="size-8"
                             src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                             alt="Your Company">
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <x-nav-link href="{{ route('index') }}" :active="request()->routeIs('index')">Bazaar</x-nav-link>
                            <x-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.index')">Bestelgeschiedenis</x-nav-link>

                            <!-- Alleen zichtbaar voor adverteerders -->
                            @if(Auth::check() && Auth::user()->isAdvertiser())
                                <!-- Controleer of de gebruiker een adverteerder is -->
                                <x-nav-link href="{{ route('advertisements.create') }}"
                                            :active="request()->routeIs('advertisements.create')">Plaats advertentie
                                </x-nav-link>
                                <x-nav-link href="{{ route('advertisements.my') }}"
                                            :active="request()->routeIs('advertisements.my')">Mijn advertenties
                                </x-nav-link>
                            @endif

                            <!-- Alleen zichtbaar voor admins -->
                            @if(Auth::check() && Auth::user()->isAdmin())
                                <!-- Controleer of de gebruiker een admin is -->
                                <x-nav-link href="{{ route('upload.contract') }}"
                                            :active="request()->routeIs('upload.contract')">Upload contract
                                </x-nav-link>
                                <x-nav-link href="{{ route('export.registration') }}"
                                            :active="request()->routeIs('export.registration')">Exporteer registratie
                                </x-nav-link>
                            @endif

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
