@section('title', __('my-advertisements.title'))

<x-layout>
    <x-slot:heading>
        {{ __('my-advertisements.heading') }}
    </x-slot:heading>

    <div class="space-y-12">
        <div class="pb-12">
            <form method="GET" class="mb-6">
                <div class="flex flex-wrap justify-between items-end gap-4">

                    {{-- Categorie dropdown --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('my-advertisements.category_label') }}
                        </label>
                        <select name="category_id" id="category_id"
                                class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('my-advertisements.all_categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if(request('category_id') == $category->id) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Prijs sortering --}}
                    <div>
                        <label for="sort_price" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('my-advertisements.sort_price_label') }}
                        </label>
                        <select name="sort_price" id="sort_price"
                                class="w-56 rounded-lg border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">{{ __('my-advertisements.no_sort') }}</option>
                            <option value="asc" @if(request('sort_price') === 'asc') selected @endif>
                                {{ __('my-advertisements.low_to_high') }}
                            </option>
                            <option value="desc" @if(request('sort_price') === 'desc') selected @endif>
                                {{ __('my-advertisements.high_to_low') }}
                            </option>
                        </select>
                    </div>

                    {{-- Filter knop --}}
                    <div class="ml-auto">
                        <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('my-advertisements.filter_button') }}
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($advertisements as $advertisement)
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-3 flex flex-col justify-between">
                        <div>
                            <img src="{{ Storage::disk('public')->url($advertisement->image_path) }}"
                                 alt="{{ $advertisement->title }}"
                                 class="rounded-md object-cover aspect-square w-full mb-4" />
                            <h3 class="text-sm font-medium text-gray-900">{{ $advertisement->title }}</h3>
                            <p class="text-base text-gray-700">€{{ number_format($advertisement->price, 2, ',', '.') }}</p>
                            
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('my-advertisements.expires_on') }}:
                                <span class="{{ now()->gt($advertisement->expiration_date) ? 'text-red-600 font-semibold' : (now()->diffInDays($advertisement->expiration_date) <= 3 ? 'text-orange-500' : 'text-green-600') }}">
                                    {{ \Carbon\Carbon::parse($advertisement->expiration_date)->format('d-m-Y') }}
                                </span>
                            </p>

                            {{-- Status label --}}
                            @if(now()->gt($advertisement->expiration_date))
                                <span class="inline-block mt-1 text-xs font-semibold text-red-800 bg-red-100 px-2 py-0.5 rounded-full">
                                    {{ __('my-advertisements.expired') }}
                                </span>
                            @elseif(now()->diffInDays($advertisement->expiration_date) <= 3)
                                <span class="inline-block mt-1 text-xs font-semibold text-orange-800 bg-orange-100 px-2 py-0.5 rounded-full">
                                    {{ __('my-advertisements.expiring_soon') }}
                                </span>
                            @else
                                <span class="inline-block mt-1 text-xs font-semibold text-green-800 bg-green-100 px-2 py-0.5 rounded-full">
                                    {{ __('my-advertisements.active') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 text-sm sm:col-span-6">
                        {{ __('my-advertisements.empty') }}
                    </p>
                @endforelse
            </div>

            @if ($advertisements->hasPages())
                <div class="mt-4 px-6">
                    {{ $advertisements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layout>
