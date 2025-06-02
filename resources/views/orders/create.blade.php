<x-layout>
    <x-slot:heading>
        {{ __('order.confirm_purchase') }}: {{ $advertisement->title }}
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('order.store', $advertisement) }}" method="POST">
            @csrf
            <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-gray-300">
                <div class="md:flex">
                    {{-- Links: Product afbeelding & info --}}
                    <div class="md:w-1/2 p-6 lg:p-8">
                        <div class="aspect-square">
                            <img src="{{ asset('storage/' . $advertisement->image_path) }}"
                                 alt="{{ $advertisement->title }}"
                                 class="rounded-lg object-cover w-full h-full border border-gray-200">
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mt-6">{{ $advertisement->title }}</h2>
                        <p class="mt-1 text-lg text-gray-700">
                            {{ __('order.price') }}: €{{ number_format($advertisement->price, 2, ',', '.') }}
                        </p>
                        <div class="mt-4 text-sm text-gray-600">
                            <h3 class="font-semibold text-gray-800 mb-1">{{ __('order.description') }}:</h3>
                            <div class="max-w-none">
                                <p>{{ $advertisement->description }}</p>
                            </div>
                        </div>
                        <div class="mt-4 text-xs text-gray-500">
                            <p><strong>{{ __('order.seller') }}:</strong> {{ $advertisement->user->name ?? __('order.unknown') }}</p>
                            <p><strong>{{ __('order.posted_on') }}:</strong> {{ $advertisement->created_at->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    {{-- Rechts: Bevestiging en actie --}}
                    <div class="md:w-1/2 p-6 lg:p-8 bg-gray-50 border-t md:border-t-0 md:border-l border-gray-200 flex flex-col justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">{{ __('order.your_purchase') }}</h3>

                            {{-- Overzicht --}}
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h4 class="text-md font-semibold text-gray-800 mb-2">{{ __('order.summary') }}</h4>
                                <dl class="mt-2 space-y-1 text-sm text-gray-600">
                                    <div class="flex justify-between">
                                        <dt>{{ __('order.subtotal') }}</dt>
                                        <dd class="font-medium text-gray-900">€{{ number_format($advertisement->price, 2, ',', '.') }}</dd>
                                    </div>
                                    <div class="flex justify-between text-base font-medium text-gray-900 pt-2 border-t border-gray-100 mt-2">
                                        <dt>{{ __('order.total_due') }}</dt>
                                        <dd>€{{ number_format($advertisement->price, 2, ',', '.') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        {{-- Actieknoppen --}}
                        <div class="mt-8">
                            <button type="submit"
                                    style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}"
                                    class="w-full px-6 py-3 text-lg font-semibold text-white rounded-md hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                {{ __('order.confirm_purchase_button') }}
                            </button>
                            <a href="{{ route('advertisements.show', $advertisement) }}"
                               class="mt-3 block text-center text-sm text-indigo-600 hover:text-indigo-500">
                                {{ __('order.cancel') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-layout>
