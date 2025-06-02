@section('title', __('orders.title'))

<x-layout>
    <x-slot:heading>{{ __('orders.heading') }}</x-slot:heading>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ __('orders.success') }}</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ __('orders.error') }}</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-2xl rounded-lg border border-gray-300 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 flex flex-row items-start justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('orders.subheading') }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('orders.description') }}</p>
                    </div>
                    <div class="pb-2 px-4 flex gap-8 justify-end">
                        <form method="GET" action="{{ route('orders.index') }}">
                            <label for="sort" class="block font-medium text-gray-700 mb-1">{{ __('orders.sort_by_price') }}</label>
                            <x-select name="sort" id="sort" onchange="this.form.submit()">
                                <option value="">{{ __('orders.no_sort') }}</option>
                                <option value="price_asc" @if(request('sort') === 'price_asc') selected @endif>{{ __('orders.price_low_high') }}</option>
                                <option value="price_desc" @if(request('sort') === 'price_desc') selected @endif>{{ __('orders.price_high_low') }}</option>
                                <option value="date_desc" @if(request('sort') === 'date_desc') selected @endif>{{ __('orders.date_new_old') }}</option>
                                <option value="date_asc" @if(request('sort') === 'date_asc') selected @endif>{{ __('orders.date_old_new') }}</option>
                            </x-select>
                            @if(request('filter'))
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                            @endif
                        </form>
                        <form method="GET" action="{{ route('orders.index') }}">
                            <label for="filter" class="block font-medium text-gray-700 mb-1">{{ __('orders.filter_by_category') }}</label>
                            <x-select name="filter" id="filter" onchange="this.form.submit()">
                                <option value="">{{ __('orders.no_filter') }}</option>
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

                @if($orders->isEmpty())
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0 text-center">
                        <p class="p-6 text-gray-500">{{ __('orders.no_orders') }}</p>
                    </div>
                @else
                    <div class="border-t border-gray-200">
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                <li class="p-4 sm:p-6 hover:bg-gray-50">
                                    <div class="flex items-center sm:items-start">
                                        <div class="flex-shrink-0 h-20 w-20 sm:h-24 sm:w-24 rounded-md overflow-hidden border border-gray-200">
                                            @if($order->advertisement->image_path)
                                                <img src="{{ Storage::disk('public')->url($order->advertisement->image_path) }}"
                                                     alt="{{ $order->advertisement->title }}"
                                                     class="h-full w-full object-cover object-center">
                                            @else
                                                <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-500 text-sm">{{ __('orders.no_image') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 sm:ml-6 flex-1">
                                            <div class="sm:flex sm:justify-between">
                                                <div>
                                                    <h4 class="text-lg font-semibold text-indigo-600 hover:text-indigo-800">
                                                        <a style="color: {{ $appSettings->button_color ?? '#4f46e5' }}" href="{{ route('advertisements.show', $order->advertisement->id) }}">
                                                            {{ $order->advertisement->title }}
                                                        </a>
                                                    </h4>
                                                    <p class="mt-1 text-sm text-gray-600 truncate max-w-md">{{ Str::limit($order->advertisement->description, 100) }}</p>
                                                    <p class="mt-1 text-sm text-gray-500">{{ __('orders.category') }}: {{ $order->advertisement->category->name ?? __('orders.not_applicable') }}</p>
                                                </div>
                                                <div class="mt-2 sm:mt-0 sm:ml-6 text-left sm:text-right">
                                                    <p class="text-lg font-medium text-gray-900">{{ __('orders.paid') }}: €{{ number_format($order->final_price, 2, ',', '.') }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ __('orders.seller') }}:
                                                        @if(!empty($order->seller) && !empty($order->seller->name))
                                                            <a href="{{ route('user-review.create', $order) }}" class="text-indigo-600 hover:underline">
                                                                {{ $order->seller->name }}
                                                            </a>
                                                        @else
                                                            {{ __('orders.unknown') }}
                                                        @endif
                                                    </p>
                                                    @if(Auth::user()->hasReviewedUser($order->seller))
                                                        <div class="flex items-center mt-1 @if(isset($order->seller)) sm:justify-end @endif">
                                                            <span class="text-sm text-gray-500 mr-1">{{ __('orders.rating') }}</span>
                                                            <span class="text-yellow-400">
                                                                @for ($s = 1; $s <= 5; $s++)
                                                                    <i class="{{ $s <= Auth::user()->getSellerReviewRating($order->seller) ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                                                @endfor
                                                            </span>
                                                            <span class="ml-1 text-xs text-gray-500">({{ Auth::user()->getSellerReviewRating($order->seller) }}/5)</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="mt-3 sm:flex sm:justify-between items-center">
                                                <p class="text-sm text-gray-500">{{ __('orders.order_date') }}: {{ $order->created_at->format('d/m/Y') }}</p>
                                                <div class="mt-2 sm:mt-0">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($order->status === 'completed') bg-green-100 text-green-800 @else bg-yellow-100 text-yellow-800 @endif">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if ($orders->hasPages())
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-layout>
