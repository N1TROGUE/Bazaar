<x-layout>
    <x-slot:heading> {{ $advertisement->title }} </x-slot:heading>
    <div class="bg-white pb-16">

        @error('amount')
            <x-error-message>{{ $message }}</x-error-message>
        @enderror

        <div class="flex flex-row justify-evenly pt-16">
            <div class="max-w-4xl pr-4 sm:pr-6 lg:pr-8">
                <div class="flex flex-col gap-9">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        {{-- Productafbeelding --}}
                        <div class="relative">
                            <img src="{{ Storage::disk('public')->url($advertisement->image_path) }}"
                                 alt="{{ $advertisement->title }}"
                                 class="rounded-xl border-2 object-cover w-full aspect-square">
                            <div class="absolute top-3 right-3">
                                <form id="favorite-form" action="{{ route('advertisements.favorite', $advertisement) }}"
                                      method="POST" style="display:inline;">
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
                                <p class="mt-2 text-lg text-gray-700">
                                    €{{ number_format($advertisement->price, 2, ',', '.') }}</p>
                                <p class="mt-4 text-gray-600">{{ $advertisement->description }}</p>
                            </div>

                            {{-- Productdetails --}}
                            <div class="mt-6 flex flex-row justify-between items-end">
                                <div>
                                    {{-- Optionele extra gegevens --}}
                                    <div class="mt-8 space-y-2 text-sm text-gray-500">
                                        <p><strong>Geplaatst
                                                door:</strong> {{ $advertisement->user->name ?? 'Onbekend' }}</p>
                                        <p><strong>Geplaatst
                                                op:</strong> {{ $advertisement->created_at->format('d-m-Y') }}</p>
                                    </div>
                                    <div class="mt-6">
                                        <a style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}"
                                           href="{{ $advertisement->ad_type === 'sale' ? route('order.create', $advertisement) : route('advertisements.rent', $advertisement) }}"
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
                    <div class="flex flex-row justify-between">
                        <div class="grow pr-20">
                            {{-- Review form --}}
                            <h3 class="text-2xl font-bold text-gray-900">Reviews</h3>
                            <div class="flex flex-col gap-4 mt-2">
                                @if(! Auth::user()->hasReviewed($advertisement))
                                    <form action="{{ route('advertisements.review', $advertisement) }}" method="POST">
                                        @csrf
                                        <h4 class="text-lg font-semibold mb-4">Plaats een review</h4>
                                        <div class="relative">
                                            <label for="rating" class="block mb-2 font-medium text-gray-700">Rating:</label>
                                            <div class="relative mb-1.5">
                                                <select
                                                    style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};"
                                                    id="rating" name="rating"
                                                    class="block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm appearance-none">
                                                    <option value="" selected>- Selecteer een getal -</option>
                                                    <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5</option>
                                                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4</option>
                                                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3</option>
                                                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2</option>
                                                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1</option>
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                                         fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd"
                                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                                              clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            @if($errors->has('rating'))
                                                <x-form-error>{{ $errors->first('rating') }}</x-form-error>
                                            @endif
                                        </div>
                                        <div class="mb-4">
                                            <label for="comment"
                                                   class="block mb-2 font-medium text-gray-700">Comment:</label>
                                            <textarea
                                                style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};"
                                                name="comment" id="comment" rows="4"
                                                class="rounded-md border border-gray-300 w-full p-4 focus:outline-none focus:ring-2 focus:ring-indigo-600"></textarea>
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
                        @if($advertisement->ad_type === 'sale')
                            {{-- Bieden (Auction) Section --}}
                            <div class="border border-gray-300 rounded-lg p-4 mb-auto">
                                <h2 class="text-2xl font-bold text-gray-900">Bieden</h2>
                                <div>
                                    {{-- Active Advertisement --}}
                                    @if ($advertisement->status === 'active')
                                        @php
                                            $highestBid = $advertisement->highestBid();
                                            $currentDisplayPrice = $highestBid->amount ?? $advertisement->price;
                                            $minimumNextBid = $advertisement->getMinimumNextBid();
                                        @endphp

                                        {{-- Current Price / Highest Bid --}}
                                        <p class="text-gray-600 mb-1">
                                            {{ $highestBid ? 'Huidig hoogste bod:' : 'Startprijs:' }}

                                            <strong>€{{ number_format($currentDisplayPrice, 2, ',', '.') }}</strong>

                                            @if($highestBid && $highestBid->bidder)
                                                <span class="text-xs">(door {{ $highestBid->bidder_id === Auth::id() ? 'jou' : Str::mask($highestBid->bidder->name, '*', 1, -1) }})</span>
                                            @endif
                                        </p>

                                        {{-- Auction End Time --}}
                                        <p class="text-gray-600 mb-3">
                                            Veiling eindigt: <strong>{{ $advertisement->expiration_date->format('d-m-Y H:i') }}</strong>
                                        </p>

                                        {{-- Bid Form --}}
                                        @if (Auth::id() !== $advertisement->user_id)
                                            <form action="{{ route('bid.store', $advertisement) }}" method="POST" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <label for="bid_amount" class="block font-medium text-gray-700">
                                                        Jouw bod (min. €{{ number_format($minimumNextBid, 2, ',', '.') }})
                                                    </label>
                                                    <div class="mt-1 flex rounded-md shadow-sm">
                                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">€</span>
                                                        <input type="number" name="amount" id="bid_amount" step="0.01"
                                                               value="{{ old('amount', number_format($minimumNextBid, 2, '.', '')) }}"
                                                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 @error('amount') border-red-500 @enderror">
                                                    </div>
                                                </div>
                                                <button type="submit"
                                                        style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}; color: {{ $appSettings->button_text_color ?? '#ffffff' }}"
                                                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm font-medium hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Plaats Bod
                                                </button>
                                            </form>
                                            {{-- Owner Cannot Bid --}}
                                        @else
                                            <p class="mt-3 text-gray-500">Je kunt niet op je eigen advertentie bieden.</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
