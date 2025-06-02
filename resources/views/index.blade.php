@section('title', __('bazaar.title'))

<x-layout>
    <x-slot:heading>
        @if(isset($company))
            {{ __('bazaar.ads_from', ['company' => $company->name]) }}
        @else
            {{ __('bazaar.welcome_user', ['name' => Auth::user()->name]) }}
        @endif
    </x-slot:heading>

    <div class="bg-white">
        @if (session('success'))
            <x-success-message>{{ session('success') }}</x-success-message>
        @endif

        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <div class="flex flex-row justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('bazaar.latest_ads') }}</h2>

                <div class="pb-8 flex gap-8 justify-end">
                    {{-- Sorteer op prijs --}}
                    <form method="GET" action="{{ isset($company) ? route('company.landing', $company->slug) : route('index') }}">
                        <label for="sort" class="block font-medium text-gray-700 mb-1">{{ __('bazaar.sort_price') }}</label>
                        <x-select name="sort" id="sort" onchange="this.form.submit()">
                            <option value="">{{ __('bazaar.no_sort') }}</option>
                            <option value="price_asc" @if(request('sort') === 'price_asc') selected @endif>{{ __('bazaar.price_low_high') }}</option>
                            <option value="price_desc" @if(request('sort') === 'price_desc') selected @endif>{{ __('bazaar.price_high_low') }}</option>
                            <option value="date_desc" @if(request('sort') === 'date_desc') selected @endif>{{ __('bazaar.date_new_old') }}</option>
                            <option value="date_asc" @if(request('sort') === 'date_asc') selected @endif>{{ __('bazaar.date_old_new') }}</option>
                        </x-select>

                        @if(request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}">
                        @endif
                    </form>

                    {{-- Filter op categorie --}}
                    <form method="GET" action="{{ isset($company) ? route('company.landing', $company->slug) : route('index') }}">
                        <label for="filter" class="block font-medium text-gray-700 mb-1">{{ __('bazaar.filter_category') }}</label>

                        <x-select name="filter" id="filter" onchange="this.form.submit()">
                            <option value="">{{ __('bazaar.no_filter') }}</option>
                            @foreach($advertisementCategories as $advertisementCategorie)
                                <option value="{{ $advertisementCategorie->name }}" @if(request('filter') === $advertisementCategorie->name) selected @endif>{{ $advertisementCategorie->name }}</option>
                            @endforeach
                        </x-select>

                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                    </form>
                </div>
            </div>

            {{-- Advertenties grid --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                @foreach($advertisements as $advertisement)
                    <a href="{{ route('advertisements.show', $advertisement) }}" class="group">
                        <img src="{{ Storage::disk('public')->url($advertisement->image_path) }}" alt="{{ $advertisement->title }}" class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8 border-2">
                        <h3 class="mt-4 text-sm text-gray-700">{{ $advertisement->title }}</h3>
                        <p class="mt-1 text-lg font-medium text-gray-900">€{{ $advertisement->price }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Paginatie --}}
        @if ($advertisements->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $advertisements->links() }}
            </div>
        @endif

        {{-- Favorieten --}}
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            @if (Auth::check())
                <h2 class="text-2xl font-bold text-gray-900">{{ __('bazaar.my_favorites') }}</h2>

                @if ($favoriteAdvertisements->isNotEmpty())
                    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @foreach($favoriteAdvertisements as $advertisement)
                            <a href="{{ route('advertisements.show', $advertisement) }}" class="group">
                                <img src="{{ Storage::disk('public')->url($advertisement->image_path) }}" alt="{{ $advertisement->title }}" class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8 border-2">
                                <h3 class="mt-4 text-sm text-gray-700">{{ $advertisement->title }}</h3>
                                <p class="mt-1 text-lg font-medium text-gray-900">€{{ $advertisement->price }}</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mt-6 text-gray-500">{{ __('bazaar.no_favorites') }}</p>
                @endif
            @endif
        </div>
    </div>
</x-layout>
