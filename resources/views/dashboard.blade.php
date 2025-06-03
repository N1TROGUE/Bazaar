@section('title', 'De Bazaar')

<x-layout>
    <x-slot:heading>
        @if(isset($company))
            Advertenties van {{ $company->name }}
        @else
            Welkom, {{ Auth::user()->name }}
        @endif
    </x-slot:heading>

    <div class="bg-white">
        @if (session('success'))
            <x-success-message>{{ session('success') }}</x-success-message>
        @endif

        <div class="flex flex-row justify-between">
            {{-- Image Section for Company Landing Page --}}
            @if($components->contains('component_type', 'dashboard_image') && isset($dashboardImage))
                <div class="max-w-xs sm:px-4 lg:max-w-xs">
                    <img src="{{ Storage::disk('public')->url($dashboardImage) }}" alt="Dashboard Image" class="w-40 h-40 p-2 object-cover rounded-xl" />
                </div>
            @endif

            {{-- Welcome Message Section for Company Landing Page --}}
            @if($components->contains('component_type', 'welcome_message'))
                <div class="max-w-2xl px-4 sm:px-6 lg:max-w-4xl lg:px-8">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl shadow-lg p-8 flex items-center gap-6">
                        <div>
                            <h2 class="text-3xl font-extrabold text-white mb-2">Welkom {{ $company->name }}!</h2>
                            <p class="text-lg text-indigo-100">
                                Ontdek onze nieuwste aanbiedingen, favoriete producten en meer. We zijn blij dat je er bent!
                            </p>
                        </div>
                        <svg class="w-20 h-20 text-white/30 hidden md:block" fill="none" viewBox="0 0 64 64">
                            <circle cx="32" cy="32" r="32" fill="currentColor" />
                            <text x="50%" y="50%" text-anchor="middle" fill="#fff" font-size="28" font-family="Arial" dy=".3em">👋</text>
                        </svg>
                    </div>
                </div>
            @endif
        </div>

        @if($components->contains('component_type', 'advertisements'))
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">

                {{-- Filter and sort advertisements --}}
                <div class="flex flex-row justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900">Laatste Advertenties</h2>
                    <div class="pb-8 flex gap-8 justify-end">
                        <form method="GET" action="{{ isset($company) ? route('company.landing', $company->slug) : route('index') }}">
                            <label for="sort" class="block font-medium text-gray-700 mb-1">Sorteer op prijs</label>
                            <x-select name="sort" id="sort" onchange="this.form.submit()">
                                <option value="">-- Geen sortering --</option>
                                <option value="price_asc" @if(request('sort') === 'price_asc') selected @endif>Prijs: Laag naar hoog</option>
                                <option value="price_desc" @if(request('sort') === 'price_desc') selected @endif>Prijs: Hoog naar laag</option>
                                <option value="date_desc" @if(request('sort') === 'date_desc') selected @endif>Datum: Nieuwste eerst</option>
                                <option value="date_asc" @if(request('sort') === 'date_asc') selected @endif>Datum: Oudste eerst</option>
                            </x-select>

                            @if(request('filter'))
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                            @endif
                        </form>

                        <form method="GET" action="{{ isset($company) ? route('company.landing', $company->slug) : route('index') }}">
                            <label for="filter" class="block font-medium text-gray-700 mb-1">Filter op categorie</label>

                            <x-select name="filter" id="filter" onchange="this.form.submit()">
                                <option value="">-- Geen filteroptie --</option>
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

                {{-- Display advertisements --}}
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    @foreach($advertisements as $advertisement)
                        <a href="{{ route('advertisements.show', $advertisement) }}" class="group">
                            <img src="{{ Storage::disk('public')->url($advertisement->image_path) }}" alt="{{ $advertisement->title }}" class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8 border-2">
                            <h3 class="mt-4 text-sm text-gray-700">{{ $advertisement->title }}</h3>
                            <p class="mt-1 text-lg font-medium text-gray-900">€{{ $advertisement->price }}</p>
                        </a>
                    @endforeach

                    @if ($advertisements->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $advertisements->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Favorite advertisements section --}}
        @if($components->contains('component_type', 'favorites'))
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                @if (Auth::check())
                    <h2 class="text-2xl font-bold text-gray-900">Mijn Favorieten</h2>

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
                        <p class="mt-6 text-gray-500">Je hebt nog geen favorieten.</p>
                    @endif
                @endif
            </div>
        @endif

    </div>
</x-layout>
