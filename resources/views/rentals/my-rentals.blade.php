@section('title', __('my-rentals.title'))

<x-layout>
    <x-slot:heading>
        {{ __('my-rentals.heading') }}
    </x-slot:heading>

    @if($rentals->isEmpty())
        <p>{{ __('my-rentals.empty') }}</p>
    @else
        <div class="space-y-4">
            <form method="GET" class="mb-6">
                <div class="flex flex-wrap justify-between items-end gap-4">

                    {{-- Categorie dropdown --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('my-rentals.category_label') }}
                        </label>
                        <select name="category_id" id="category_id"
                                class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('my-rentals.all_categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(request('category_id') == $category->id) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Prijs sortering --}}
                    <div>
                        <label for="sort_price" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('my-rentals.sort_price_label') }}
                        </label>
                        <select name="sort_price" id="sort_price"
                                class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('my-rentals.no_sort') }}</option>
                            <option value="asc" @if(request('sort_price') === 'asc') selected @endif>
                                {{ __('my-rentals.low_to_high') }}
                            </option>
                            <option value="desc" @if(request('sort_price') === 'desc') selected @endif>
                                {{ __('my-rentals.high_to_low') }}
                            </option>
                        </select>
                    </div>

                    {{-- Filter knop --}}
                    <div class="ml-auto">
                        <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('my-rentals.filter_button') }}
                        </button>
                    </div>
                </div>
            </form>

            @foreach($rentals as $rental)
                <div class="border p-4 rounded shadow">
                    <h2 class="text-lg font-semibold">
                        <a href="{{ route('advertisements.show', $rental->advertisement->id) }}"
                           class="text-blue-600 hover:underline">
                            {{ $rental->advertisement->title }}
                        </a>
                    </h2>
                    <p>{{ __('my-rentals.rented_from') }}:
                        <strong>{{ \Carbon\Carbon::parse($rental->rented_from)->format('d-m-Y') }}</strong></p>
                    <p>{{ __('my-rentals.rented_until') }}:
                        <strong>{{ \Carbon\Carbon::parse($rental->rented_until)->format('d-m-Y') }}</strong></p>
                </div>
            @endforeach
        </div>

        @if ($rentals->hasPages())
            <div class="mt-4 px-6">
                {{ $rentals->links() }}
            </div>
        @endif
    @endif
</x-layout>
