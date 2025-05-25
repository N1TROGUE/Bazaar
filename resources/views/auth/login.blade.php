@section('title', 'Inloggen')

<x-auth-layout>
  <div class="mt-10 flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="{{ $appSettings->logo_path ?? 'https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500' }}" alt="Your Company">

      <h2 class="mt-5 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Welkom bij De Bazaar</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="#" method="POST">
        @csrf

        <div>
          <label for="email" class="block text-sm/6 font-medium text-gray-900">E-mailadres</label>
          <div class="mt-2">
            <input style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" name="email" id="email" value="{{ old('email') }}" autocomplete="email" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm/6 font-medium text-gray-900">Wachtwoord</label>
            <div class="text-sm">
              <a style="color: {{ $appSettings->button_color ?? '#4f46e5' }}" href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Wachtwoord vergeten?</a>
            </div>
          </div>
          <div class="mt-2">
            <input style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" type="password" name="password" id="password" autocomplete="current-password" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
          </div>
        </div>

        <div>
          <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Inloggen</button>
        </div>

        <!-- Validation errors -->
        @if ($errors->any())
        <ul class="px-4 py-2 bg-red-100">
          @foreach ($errors->all() as $error)
              <li class="my-2 text-red-500">{{ $error }}</li>
          @endforeach
        </ul>
        @endif

      </form>

      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Nog geen account?
        <a style="color: {{ $appSettings->button_color ?? '#4f46e5' }}" href="{{ route('show.register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Maak een account aan</a>
      </p>
    </div>

  </div> 
</x-auth-layout>