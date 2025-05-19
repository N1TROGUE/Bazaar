@section('title', 'Mijn advertenties')

<x-layout>
    <x-slot:heading>
        Mijn advertenties
    </x-slot:heading>

    <div class="bg-white">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            @if($advertisements->isEmpty())
                <p class="text-gray-500">Je hebt nog geen advertenties geplaatst.</p>
            @else
                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    @foreach($advertisements as $ad)
                        <div class="group border-2 rounded-lg p-2">
                            <img src="{{ Storage::disk('public')->url($ad->image_path) }}"
                                 alt="{{ $ad->title }}"
                                 class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8">
                            
                            <h3 class="mt-4 text-sm text-gray-700">{{ $ad->title }}</h3>
                            <p class="text-lg font-medium text-gray-900">€{{ $ad->price }}</p>
                            
                            {{-- Expiration info --}}
                            <p class="text-sm mt-1">
                                <span class="font-medium text-gray-600">Loopt af op:</span>
                                <span class="{{ now()->gt($ad->expiration_date) ? 'text-red-600 font-semibold' : (now()->diffInDays($ad->expiration_date) <= 3 ? 'text-orange-500 font-medium' : 'text-green-600') }}">
                                    {{ \Carbon\Carbon::parse($ad->expiration_date)->format('d-m-Y H:i') }}
                                </span>
                            </p>

                            {{-- Status label --}}
                            @if(now()->gt($ad->expiration_date))
                                <span class="mt-2 inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Verlopen</span>
                            @elseif(now()->diffInDays($ad->expiration_date) <= 3)
                                <span class="mt-2 inline-block bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">Loopt bijna af</span>
                            @else
                                <span class="mt-2 inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Actief</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-layout>
