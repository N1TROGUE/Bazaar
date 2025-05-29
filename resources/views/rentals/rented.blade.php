@section('title', 'Verhuur')

<x-layout>
    <x-slot:heading>
        Gehuurde producten
    </x-slot:heading>

    @if($errors->any())
        <x-error-message>{{ $errors->first() }}</x-error-message>
    @endif

    @if($rentals->isEmpty())
        <p>Je hebt momenteel geen gehuurde producten.</p>
    @else
        <div class="space-y-4">
            <form method="GET" class="mb-6">
                    <div class="flex flex-wrap justify-between items-end gap-4">

                        {{-- Categorie dropdown --}}
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categorie</label>
                            <select name="category_id" id="category_id"
                                class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Alle categorieën --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if(request('category_id') == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Prijs sortering --}}
                        <div>
                            <label for="sort_price" class="block text-sm font-medium text-gray-700 mb-1">Sorteer op prijs</label>
                            <select name="sort_price" id="sort_price"
                                class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Geen sortering --</option>
                                <option value="asc" @if(request('sort_price') === 'asc') selected @endif>Laag naar hoog</option>
                                <option value="desc" @if(request('sort_price') === 'desc') selected @endif>Hoog naar laag</option>
                            </select>
                        </div>


                        {{-- Filter knop --}}
                        <div class="ml-auto">
                            <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            @foreach($rentals as $rental)
                @if($rental->status === 'active')
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end border border-gray-200 p-5 rounded-xl shadow bg-white gap-4">
                        <div class="flex-shrink-0 mr-4">
                            @if($rental->advertisement->image_path)
                                <img src="{{ asset('storage/' . $rental->advertisement->image_path) }}" alt="{{ $rental->advertisement->title }}" class="w-32 h-32 object-cover rounded-lg">
                            @else
                                <div class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded-lg text-gray-400">
                                    No image
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold mb-2">
                                <a href="{{ route('advertisements.show', $rental->advertisement->id) }}" class="text-indigo-600 hover:underline">
                                    {{ $rental->advertisement->title }}
                                </a>
                            </h2>
                            <p class="text-sm text-gray-700 mb-1">
                                Geleend op: <strong class="font-medium">{{ \Carbon\Carbon::parse($rental->rented_from)->format('d-m-Y') }}</strong>
                            </p>
                            <p class="text-sm text-gray-700">
                                Terugbrengen op: <strong class="font-medium">{{ \Carbon\Carbon::parse($rental->rented_until)->format('d-m-Y') }}</strong>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <form action="{{ route('rentals.return', $rental) }}" method="POST" class="mt-4 md:mt-0">
                                @csrf
                                <x-form-button class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-md shadow">Terugbrengen</x-form-button>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        @if ($rentals->hasPages())
            <div class="mt-4 px-6">
                {{ $rentals->links() }}
            </div>
        @endif
    @endif
</x-layout>
