@section('title', 'De Bazaar')

<x-layout>
    <x-slot:heading>
        Welkom, {{ Auth::user()->name }}
    </x-slot:heading>

    <div class="bg-white">
        @if (session('success'))
            <x-success-message>{{ session('success') }}</x-success-message>
        @endif
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
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
    </div>

</x-layout>

