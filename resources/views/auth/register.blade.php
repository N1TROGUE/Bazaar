<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registratie</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <!--<img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">-->
    <h2 class="mt-20 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Maak een account aan</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    @csrf

    <form class="space-y-6" action="#" method="POST">
      
      <div>
         <label for="name" class="block text-sm/6 font-medium text-gray-900">Naam<span style="color: red;"> *</span></label>
         <div class="mt-2">
            <input type="name" name="name" id="name" value="{{ old('name') }}" autocomplete="name" required class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
         </div>
      </div>
        
      <div>
        <label for="email" class="block text-sm/6 font-medium text-gray-900">E-mailadres<span style="color: red;"> *</span></label>
        <div class="mt-2">
          <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email" required class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm/6 font-medium text-gray-900">Wacthwoord<span style="color: red;"> *</span></label>
        </div>
        <div class="mt-2">
          <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900">Wacthwoord bevestigen<span style="color: red;"> *</span></label>
        </div>
        <div class="mt-2">
          <input type="password" name="password_comfirmation" id="password_comfirmation" required class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:border-transparent focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
        </div>
      </div>

      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Account aanmaken</button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm/6 text-gray-500">
      Heb je al een account?
      <a href="{{ route('show.login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Ga naar inloggen</a>
    </p>
  </div>
</div> 
</body>
</html>