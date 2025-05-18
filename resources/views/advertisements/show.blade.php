<x-layout>
    <x-slot:heading> {{ $advertisement->title }} </x-slot:heading>
    <div class="bg-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                {{-- Productafbeelding --}}
                <div class="relative">
                    <img src="{{ Storage::disk('public')->url($advertisement->image_path) }}"
                         alt="{{ $advertisement->title }}"
                         class="rounded-xl border-2 object-cover w-full aspect-square">
                    <div class="absolute top-3 right-3">
                        <form id="favorite-form" action="{{ route('advertisements.favorite', $advertisement) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="p-0 bg-transparent border-none cursor-pointer">
                                <i class="{{ Auth::user()->hasFavorited($advertisement) ? 'fa-solid text-yellow-400' : 'fa-regular text-gray-400' }} fa-star text-xl bg-white rounded-full p-2 shadow-md cursor-pointer"
                                   id="favorite-detail-icon">
                                </i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Productinformatie --}}
                <div class="flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $advertisement->title }}</h2>
                        <p class="mt-2 text-lg text-gray-700">€{{ number_format($advertisement->price, 2, ',', '.') }}</p>
                        <p class="mt-4 text-gray-600">{{ $advertisement->description }}</p>
                    </div>

                    {{-- Terugknop --}}
                    <div class="mt-6">
                        {{-- Optionele extra gegevens --}}
                        <div class="mt-8 space-y-2 text-sm text-gray-500">
                            <p><strong>Geplaatst door:</strong> {{ $advertisement->user->name ?? 'Onbekend' }}</p>
                            <p><strong>Geplaatst op:</strong> {{ $advertisement->created_at->format('d-m-Y') }}</p>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('advertisements.index') }}"
                               class="inline-block px-4 py-2 text-md font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition">
                                {{ $advertisement->ad_type === 'sale' ? 'Kopen' : 'Huren' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
