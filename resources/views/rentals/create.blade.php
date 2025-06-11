<x-layout>
    <x-slot:heading>
        {{ __('rent.confirm_rental_request') }}: {{ $advertisement->title }}
    </x-slot:heading>

    <div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('advertisements.rent.store', $advertisement) }}" method="POST">
            @csrf
            <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-300">
                <div class="md:flex">
                    {{-- Links: Product afbeelding & info --}}
                    <div class="md:w-1/2 p-6 lg:p-8">
                        <div class="aspect-square">
                            <img src="{{ Storage::disk('public')->url($advertisement->image_path) }}"
                                 alt="{{ $advertisement->title }}"
                                 class="rounded-lg object-cover w-full h-full">
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mt-6">{{ $advertisement->title }}</h2>
                        <p class="mt-1 text-lg text-gray-700">
                            {{ __('rent.price_per_day') }}: €{{ number_format($advertisement->price, 2, ',', '.') }}
                        </p>
                        <div class="mt-4 text-sm text-gray-600">
                            <h3 class="font-semibold text-gray-800 mb-1">{{ __('rent.description') }}:</h3>
                            <div class="max-w-none">
                                <p>{{ $advertisement->description }}</p>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-gray-500">
                            <p><strong>{{ __('rent.posted_by') }}:</strong> {{ $advertisement->user->name ?? __('rent.unknown') }}</p>
                            <p><strong>{{ __('rent.posted_on') }}:</strong> {{ $advertisement->created_at->format('d-m-Y') }}</p>
                            <p><strong>{{ __('rent.expiration_date') }}:</strong> {{ $advertisement->expiration_date->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    {{-- Rechts: Huur details & bevestiging --}}
                    <div class="md:w-1/2 p-6 lg:p-8 bg-gray-50 border-l border-gray-200 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">{{ __('rent.your_rental_request') }}</h3>

                            {{-- Huurperiode --}}
                            <div class="space-y-4">
                                <div>
                                    <label for="rented_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('rent.start_date') }}</label>
                                    <input type="date" name="rented_from" id="rented_from"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('rented_from') border-red-500 @enderror"
                                           value="{{ old('rented_from', now()->toDateString()) }}" min="{{ now()->toDateString() }}">
                                    @error('rented_from')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="rented_until" class="block text-sm font-medium text-gray-700 mb-1">{{ __('rent.end_date') }}</label>
                                    <input type="date" name="rented_until" id="rented_until"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('rented_until') border-red-500 @enderror"
                                           value="{{ old('rented_until') }}" min="{{ now()->toDateString() }}">
                                    @error('rented_until')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @if(session('error_rent'))
                                <p class="text-sm text-red-600 mb-3">{{ session('error_rent') }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Actieknoppen --}}
                        <div class="mt-8">
                            {{-- Overzicht --}}
                            <div class="mt-8 mb-6 pt-6 border-t border-gray-200">
                                <h4 class="text-md font-semibold text-gray-800">{{ __('rent.summary') }}</h4>
                                <dl class="mt-2 space-y-1 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <dt>{{ __('rent.product') }}:</dt>
                                        <dd class="font-medium text-gray-800">{{ $advertisement->title }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt>{{ __('rent.rental_price') }}:</dt>
                                        <dd class="font-medium text-gray-800">€{{ number_format($advertisement->price, 2, ',', '.') }}</dd>
                                    </div>
                                </dl>
                            </div>
                            <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit"
                                    class="w-full px-6 py-3 text-lg font-semibold text-white rounded-md hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                {{ __('rent.confirm_rental_button') }}
                            </button>
                            <a style="color: {{ $appSettings->button_color ?? '#4f46e5' }}" href="{{ url()->previous() }}"
                               class="mt-3 block text-center text-sm hover:underline">
                                {{ __('rent.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Flash berichten --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-5 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-5 right-5 bg-red-500 text-white py-2 px-4 rounded-lg shadow-md">
            {{ session('error') }}
        </div>
    @endif

</x-layout>
