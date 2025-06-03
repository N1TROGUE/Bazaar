@section('title', 'Instellingen')

<x-layout>
    <x-slot:heading>
        Stel uw thema in
    </x-slot:heading>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

                @if(session('success'))
                    <div class="p-4 mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg w-full sm:col-span-6">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <!-- Logo upload -->
                    <div class="col-span-full">
                        <label for="logo" class="block text-sm/6 font-medium text-gray-900">Upload uw logo</label>

                        <div class="mt-2 flex justify-center w-1/2 rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                            <div class="text-center">
                                <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25..." clip-rule="evenodd" />
                                </svg>
                                <div class="mt-4 flex text-sm/6 text-gray-600">
                                    <label style="color: {{ $appSettings->button_color ?? '#4f46e5' }}" for="logo" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 hover:text-indigo-500">
                                        <span>Kies een bestand</span>
                                        <input id="logo" name="logo" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">of drag and drop</p>
                                </div>
                                <p class="text-xs/5 text-gray-600">PNG of JPG tot 5MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- Navigatiekleur --}}
                    <div class="sm:col-span-4">
                        <label for="nav_color" class="block text-sm/6 font-medium text-gray-900">Navigatiebalk kleur</label>
                        <div class="mt-2">
                            <input type="color" id="nav_color" name="nav_color"
                                value="{{ old('nav_color', $settings->nav_color ?? '#1f2937') }}"
                                class="w-16 h-10 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    {{-- Buttonkleur --}}
                    <div class="sm:col-span-4">
                        <label for="button_color" class="block text-sm/6 font-medium text-gray-900">Button kleur</label>
                        <div class="mt-2">
                            <input type="color" id="button_color" name="button_color"
                                value="{{ old('button_color', $settings->button_color ?? '#4f46e5') }}"
                                class="w-16 h-10 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    {{-- Landingspagina URL --}}
                    <div class="sm:col-span-4">
                        <x-form-label for="company_slug">Custom URL:</x-form-label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span
                                class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                {{ url('/company/') }}/
                            </span>
                            <input type="text" name="company_slug" id="company_slug"
                                   value="{{ old('company_slug', Auth::user()->slug) }}"
                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 @error('company_slug') border-red-500 @enderror"
                                   placeholder="jouw-bedrijfsnaam">
                        </div>

                        @error('company_slug')
                        <x-form-error>{{ $message }}</x-form-error>
                        @enderror

                        <p class="mt-2 text-xs text-gray-500">Gebruik alleen letters, cijfers, streepjes (-) en underscores (_). Dit wordt onderdeel van je unieke URL.</p>
                    </div>

                    <div class="sm:col-span-4">
                        <hr class="my-2">
                        <h2 class="text-xl font-bold text-gray-900">Landingspagina componenten</h2>
                    </div>

                    {{-- Afbeelding Landingspagina --}}
                    <div class="sm:col-span-4">
                        <x-form-label for="dashboard_image">Afbeelding landingspagina</x-form-label>

                        @if($dashboardImage)
                            <div class="mt-2 mb-2">
                                <p class="text-xs text-gray-600 mb-1">Huidige afbeelding:</p>
                                <img src="{{ Storage::disk('public')->url($dashboardImage) }}" alt="Huidige afbeelding" class="max-h-40 rounded-md border border-gray-200 p-4">
                            </div>
                        @endif

                        <div class="mt-2 flex items-center gap-x-3">
                            <input type="file" id="dashboard_image" name="landing_page_settings[dashboard_image][file]"
                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-l-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100">
                        </div>

                        <p class="mt-1 text-xs text-gray-500">Upload een afbeelding voor de bovenkant van uw landingspagina (bijv. PNG, JPG tot 5MB).</p>

                        @error('landing_page_settings.dashboard_image.file')
                            <x-form-error>{{ $message }}</x-form-error>
                        @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <h3 class="text-md font-semibold mb-2">Landingpage Components</h3>
                        <div class="flex flex-col gap-4">
                            @foreach($landingPageComponents as $component)
                                <label for="{{ $component->component_type }}_enabled" class="flex items-center p-4 bg-white border rounded-lg shadow-sm cursor-pointer transition hover:shadow-md hover:border-indigo-400">
                                    <input
                                        type="checkbox"
                                        id="{{ $component->component_type }}_enabled"
                                        name="{{ $component->component_type }}_enabled"
                                        value="1"
                                        {{ $component->is_active ? 'checked' : '' }}
                                        class="form-checkbox h-5 w-5 text-indigo-600 border-gray-300 rounded mr-4 transition">
                                    <span class="text-sm text-gray-800 font-medium">
                                        {{ ucfirst(str_replace('_', ' ', $component->component_type)) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <ul class="px-4 py-2 bg-red-100">
                    @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm/6 font-semibold text-gray-900">Annuleren</button>
            <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:opacity-90 focus-visible:outline-2 focus-visible:outline-offset-2">Opslaan</button>
        </div>


    </form>
</x-layout>
