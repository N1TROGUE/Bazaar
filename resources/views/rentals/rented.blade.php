@section('title', __('rentals.title'))

<x-layout>
    <x-slot:heading>
        {{ __('rentals.heading') }}
    </x-slot:heading>

    @if($errors->any())
        <x-error-message>{{ $errors->first() }}</x-error-message>
    @endif

    @if($rentals->isEmpty())
        <p>{{ __('rentals.none') }}</p>
    @else
        <div class="space-y-4">
            <form method="GET" class="mb-6">
                <div class="flex flex-wrap justify-between items-end gap-4">

                    {{-- Categorie dropdown --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('rentals.category') }}</label>
                        <select name="category_id" id="category_id"
                            class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('rentals.all_categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if(request('category_id') == $category->id) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Prijs sortering --}}
                    <div>
                        <label for="sort_price" class="block text-sm font-medium text-gray-700 mb-1">{{ __('rentals.sort_price') }}</label>
                        <select name="sort_price" id="sort_price"
                            class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('rentals.no_sort') }}</option>
                            <option value="asc" @if(request('sort_price') === 'asc') selected @endif>{{ __('rentals.low_high') }}</option>
                            <option value="desc" @if(request('sort_price') === 'desc') selected @endif>{{ __('rentals.high_low') }}</option>
                        </select>
                    </div>

                    {{-- Filter knop --}}
                    <div class="ml-auto">
                        <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit"
                                class="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('rentals.filter_button') }}
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
                                    {{ __('rentals.no_image') }}
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
                                {{ __('rentals.rented_from') }} <strong class="font-medium">{{ \Carbon\Carbon::parse($rental->rented_from)->format('d-m-Y') }}</strong>
                            </p>
                            <p class="text-sm text-gray-700">
                                {{ __('rentals.rented_until') }} <strong class="font-medium">{{ \Carbon\Carbon::parse($rental->rented_until)->format('d-m-Y') }}</strong>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('rentals.confirmReturn', $rental) }}"
                               style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}; color: white;"
                               class="inline-block px-4 py-2 rounded-md shadow text-sm font-medium hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4 md:mt-0">
                                {{ __('rentals.return_button') }}
                            </a>
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
