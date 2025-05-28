<x-layout>
    <x-slot:heading>Mijn Aankoopgeschiedenis</x-slot:heading>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Fout!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-2xl rounded-lg border border-gray-300 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 flex flex-row items-start justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Jouw aankopen
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Een overzicht van de items die je hebt gekocht.
                        </p>
                    </div>
                    <div class="px-4 pb-2">
                        <form method="GET" action="{{ route('orders.index') }}">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sorteer op prijs</label>
                            <x-select name="sort" id="sort" onchange="this.form.submit()">
                                <option value="">-- Geen sortering --</option>
                                <option value="price_asc" @if(request('sort') === 'price_asc') selected @endif>Prijs: Laag naar hoog</option>
                                <option value="price_desc" @if(request('sort') === 'price_desc') selected @endif>Prijs: Hoog naar laag</option>
                                <option value="date_desc" @if(request('sort') === 'date_desc') selected @endif>Datum: Nieuwste eerst</option>
                                <option value="date_asc" @if(request('sort') === 'date_asc') selected @endif>Datum: Oudste eerst</option>
                            </x-select>
                        </form>
                    </div>
                </div>
                @if($orders->isEmpty())
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0 text-center">
                        <p class="p-6 text-gray-500">Je hebt nog geen aankopen geplaatst.</p>
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
                                                    <span class="text-gray-500 text-sm">Geen afbeelding</span>
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
                                                    <p class="mt-1 text-sm text-gray-600 truncate max-w-md">
                                                        {{ Str::limit($order->advertisement->description, 100) }}
                                                    </p>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        Categorie: {{ $order->advertisement->category->name ?? 'N.v.t.' }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 sm:mt-0 sm:ml-6 text-left sm:text-right">
                                                    <p class="text-lg font-medium text-gray-900">
                                                        Betaald: €{{ number_format($order->final_price, 2, ',', '.') }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        Verkoper: {{ $order->seller->name ?? 'Onbekend' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-3 sm:flex sm:justify-between items-center">
                                                <p class="text-sm text-gray-500">
                                                    Besteldatum: {{ $order->created_at->format('d/m/Y') }}
                                                </p>
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
