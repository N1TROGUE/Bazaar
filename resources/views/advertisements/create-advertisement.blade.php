@section('title', 'Plaats advertentie')

<x-layout>
    <x-slot:heading>
        Plaats een advertentie
    </x-slot:heading>

    <form method="POST" action="{{ route('advertisements.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <!-- Successmelding -->
                    @if(session('success'))
                        <div class="p-4 mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg w-full sm:col-span-6">
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <!-- Titel -->
                    <div class="sm:col-span-3">
                        <label for="title" class="block text-sm/6 font-medium text-gray-900">Titel<span style="color: red;"> *</span></label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" type="text" name="title" id="title" placeholder="Advertentietitel"
                                       class="block w-1/2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
                            </div>
                        </div>
                    </div>

                    <!-- Beschrijving -->
                    <div class="sm:col-span-3">
                        <label for="description" class="block text-sm/6 font-medium text-gray-900">Beschrijving<span style="color: red;"> *</span></label>
                        <div class="mt-2">
                            <textarea style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" name="description" id="description" rows="4"
                                      class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6"
                                      placeholder="Omschrijf je product of dienst..."></textarea>
                        </div>
                    </div>

                    <!-- Categorie -->
                    <div class="sm:col-span-3">
                        <label for="advertisement_category_id" class="block text-sm/6 font-medium text-gray-900">Categorie<span style="color: red;"> *</span></label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <select style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" name="advertisement_category_id" id="advertisement_category_id"
                                        class="block w-1/2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
                                    <option value="">-- Kies een categorie --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('advertisement_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ ucfirst($category->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Type advertentie -->
                    <div class="sm:col-span-3">
                        <label for="ad_type" class="block text-sm/6 font-medium text-gray-900">Type advertentie<span style="color: red;"> *</span></label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <select style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" name="ad_type" id="ad_type"
                                        class="block w-1/2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
                                    <option value="">-- Kies type --</option>
                                    <option value="sale">Te koop</option>
                                    <option value="rental">Te huur</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Prijs -->
                    <div class="sm:col-span-3">
                        <label for="price" class="block text-sm/6 font-medium text-gray-900">Prijs (€)<span style="color: red;"> *</span></label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" type="number" name="price" id="price" step="0.01"
                                       class="block w-1/2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6"
                                       placeholder="Bijv. 49.99">
                            </div>
                        </div>
                    </div>

                    <!-- Afbeelding -->
                    <div class="col-span-full">
                        <label for="image_path" class="block text-sm/6 font-medium text-gray-900">Afbeelding uploaden<span style="color: red;"> *</span></label>
                        <div class="mt-2 flex justify-center w-1/2 rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                            <div class="text-center">
                                <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M1.5 6a2.25...Z" clip-rule="evenodd" />
                                </svg>
                                <div class="mt-4 flex text-sm/6 text-gray-600">
                                    <label for="image_path"
                                           class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 hover:text-indigo-500">
                                        <span style="color: {{ $appSettings->button_color ?? '#4f46e5' }}">Kies een bestand</span>
                                        <input id="image_path" name="image_path" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">of drag & drop</p>
                                </div>
                                <p class="text-xs/5 text-gray-600">PNG/JPG tot 5MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Minimale huurduur -->
                    <div class="sm:col-span-3">
                        <label for="rental_min_duration_hours" class="block text-sm/6 font-medium text-gray-900">Min. huur (uren) <span style="color: red;"> in te vullen bij verhuur</span></label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" type="number" name="rental_min_duration_hours" id="rental_min_duration_hours"
                                       class="block w-1/2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6"
                                       placeholder="Bijv. 2">
                            </div>
                        </div>
                    </div>

                    <!-- Maximale huurduur -->
                    <div class="sm:col-span-3">
                        <label for="rental_max_duration_hours" class="block text-sm/6 font-medium text-gray-900">Max. huur (uren) <span style="color: red;"> in te vullen bij verhuur</span></label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" type="number" name="rental_max_duration_hours" id="rental_max_duration_hours"
                                       class="block w-1/2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6"
                                       placeholder="Bijv. 24">
                            </div>
                        </div>
                    </div>

                    <!-- Vervaldatum -->
                    <div class="sm:col-span-3">
                        <label for="expiration_date" class="block text-sm/6 font-medium text-gray-900">Vervalt op <span style="color: red;"> *</span></label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                <input style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" type="datetime-local" name="expiration_date" id="expiration_date"
                                       class="block w-1/2 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm/6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validatie fouten -->
            @if ($errors->any())
                <ul class="px-4 py-2 bg-red-100 rounded">
                    @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Knoppen -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="{{ route('index') }}" class="text-sm font-semibold text-gray-900">Annuleren</a>
            <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Plaatsen
            </button>
        </div>
    </form>
</x-layout>
