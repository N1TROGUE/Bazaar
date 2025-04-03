@section('title', 'Login')

<x-auth-layout>
  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
      <h2 class="mt-5 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Maak een account aan</h2>
    </div>

    <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">

      <form class="space-y-6" action="{{ route('register') }}" method="POST">
        @csrf

        <div>
          <label for="name" class="block text-sm/6 font-medium text-gray-900">Naam<span style="color: red;"> *</span></label>
          <div class="mt-2">
              <input type="name" name="name" id="name" value="{{ old('name') }}" autocomplete="name" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
          </div>
        </div>
          
        <div>
          <label for="email" class="block text-sm/6 font-medium text-gray-900">E-mailadres<span style="color: red;"> *</span></label>
          <div class="mt-2">
            <input name="email" id="email" value="{{ old('email') }}" autocomplete="email" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
          </div>
        </div>

        <div>
          <label for="role" class="block text-sm/6 font-medium text-gray-900">
            Registreren als<span style="color: red;"> *</span>
          </label>
          <div class="mt-2">
            <select name="role" id="role" 
              class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
              <option value="" disabled selected>Geen adverteerder</option>
              <option value="particulier">Particuliere adverteerder</option>
              <option value="zakelijk">Zakelijke adverteerder</option>
            </select>
          </div>
        </div>        

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm/6 font-medium text-gray-900">Wacthwoord<span style="color: red;"> *</span></label>
          </div>
          <div class="mt-2">
            <input type="password" name="password" id="password" autocomplete="current-password" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900">Wacthwoord bevestigen<span style="color: red;"> *</span></label>
          </div>
          <div class="mt-2">
            <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
          </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Account aanmaken</button>
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

      <p class="mt-5 text-center text-sm/6 text-gray-500">
        Heb je al een account?
        <a href="{{ route('show.login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Ga naar inloggen</a>
      </p>
    </div>
  </div> 
</x-auth-layout>