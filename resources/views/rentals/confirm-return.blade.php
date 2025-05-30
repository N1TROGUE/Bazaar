<x-layout>
    <x-slot:heading>
        Retour Bevestigen: {{ $rental->advertisement->title }}
    </x-slot:heading>

    <div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-300">
            <div class="md:flex">
                {{-- Left Side: Product Image & Core Info --}}
                <div class="md:w-1/2 p-6 lg:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Product Details</h2>
                    @if($rental->advertisement->image_path)
                        <div class="aspect-square mb-4">
                            <img src="{{ asset('storage/' . $rental->advertisement->image_path) }}" alt="{{ $rental->advertisement->title }}" class="rounded-lg object-cover w-full h-full">
                        </div>
                    @else
                        <div class="aspect-square w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400 mb-4">
                            Geen afbeelding beschikbaar
                        </div>
                    @endif
                    <p class="text-lg text-gray-700"><span class="font-medium">Product:</span> {{ $rental->advertisement->title }}</p>
                    <p class="mt-1 text-sm text-gray-600"><span class="font-medium">Geleend op:</span> {{ $rental->rented_from->format('d-m-Y') }}</p>
                    <p class="mt-1 text-sm text-gray-600"><span class="font-medium">Terugbrengen uiterlijk op:</span> {{ $rental->rented_until->format('d-m-Y') }}</p>
                </div>

                {{-- Right Side: Return Form --}}
                <div class="md:w-1/2 p-6 lg:p-8 bg-gray-50 border-t md:border-t-0 md:border-l border-gray-200 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Retour Informatie</h3>

                        @if ($errors->any())
                            <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('rentals.return', $rental) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <div class="space-y-5">
                                    <div>
                                        <label for="return_image" class="block text-sm font-medium text-gray-700 mb-1">Upload afbeelding van geretourneerd item (optioneel)</label>
                                        <input type="file" name="return_image" id="return_image"
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG tot 2MB.</p>
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <button type="submit"
                                            style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}"
                                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                        Terugbrengen
                                    </button>
                                    <a href="{{ route('rented.show') }}" class="mt-3 block text-center text-sm text-indigo-600 hover:text-indigo-500">
                                        Annuleren
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash messages for general success/error after redirect --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-5 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif
</x-layout>
