<x-layout>
    <x-slot:heading> {{ $advertisement->title }} </x-slot:heading>
    <div class="bg-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-9">
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

                    {{-- Productdetails --}}
                    <div class="mt-6 flex flex-row justify-between items-end">
                        <div>
                            {{-- Optionele extra gegevens --}}
                            <div class="mt-8 space-y-2 text-sm text-gray-500">
                                <p><strong>Geplaatst door:</strong> {{ $advertisement->user->name ?? 'Onbekend' }}</p>
                                <p><strong>Geplaatst op:</strong> {{ $advertisement->created_at->format('d-m-Y') }}</p>
                            </div>
                            <div class="mt-6">
                                <a style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}"  href="{{ $advertisement->ad_type === 'sale' ? back() : route('advertisements.rent', $advertisement) }}"
                                   class="inline-block px-4 py-2 text-md font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition">
                                    {{ $advertisement->ad_type === 'sale' ? 'Kopen' : 'Huren' }}
                                </a>
                            </div>
                        </div>
                        <div>
                            {!! $qrCodeImage !!}
                        </div>
                    </div>
                </div>
            </div>
            <div>
                {{-- Review form --}}
                <h3 class="text-2xl font-bold text-gray-900">Reviews</h3>
                <div class="max-w-xl flex flex-col gap-4 mt-2">
                    @if(! Auth::user()->hasReviewed($advertisement))
                        <form action="{{ route('advertisements.review', $advertisement) }}" method="POST">
                            @csrf
                            <h4 class="text-lg font-semibold mb-4">Plaats een review</h4>
                            <div class="relative mb-1.5">
                                <label for="rating" class="block mb-2 font-medium text-gray-700">Rating:</label>
                                <div class="relative">
                                    <select style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" id="rating" name="rating" class="block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm appearance-none">
                                        <option value="" selected>- Selecteer een getal -</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @if($errors->has('rating'))
                                    <x-form-error>{{ $errors->first('rating') }}</x-form-error>
                                @endif
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block mb-2 font-medium text-gray-700">Comment:</label>
                                <textarea style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" name="comment" id="comment" rows="4" class="rounded-md border border-gray-300 w-full p-4 focus:outline-none focus:ring-2 focus:ring-indigo-600"></textarea>
                                @if($errors->has('comment'))
                                    <x-form-error>{{ $errors->first('comment') }}</x-form-error>
                                @endif
                            </div>
                            <div>
                                <x-form-button class="w-full">Plaats review</x-form-button>
                            </div>
                        </form>
                    @endif

                    <hr class="border border-gray-300">

                    {{-- Reviews van anderen --}}
                    <div class="mt-2">
                        @if ($advertisement->reviews->isEmpty())
                            <p class="text-gray-500 italic">Er zijn nog geen reviews voor dit product.</p>
                        @else
                            <h4 class="text-lg font-semibold mb-4">Reviews van anderen</h4>
                            <div class="flex flex-col gap-4">
                                @foreach($advertisement->reviews as $review)
                                    <div class="p-4 rounded-lg border border-gray-300">
                                        <div class="font-semibold">{{ $review->user->name }}</div>
                                        <div class="flex items-center gap-2">
                                    <span class="text-yellow-400">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <i class="fa-solid fa-star"></i>
                                        @endfor
                                        @for ($i = $review->rating; $i < 5; $i++)
                                            <i class="fa-regular fa-star"></i>
                                        @endfor
                                    </span>
                                            <span class="text-sm text-gray-600">({{ $review->rating }}/5)</span>
                                        </div>
                                        <div class="mt-2 text-gray-700">{{ $review->comment }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
