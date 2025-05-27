@section('title', 'Contracten')

<x-layout>
    <x-slot:heading>
        Exporteer (zakelijke) registraties
    </x-slot:heading>

    <div class="mt-6">
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse ($users as $user)
                    <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <a style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" 
                            href="{{ route('contracts.export.pdf', ['user' => $user->id]) }}" 
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Exporteer als PDF
                        </a>
                    </li>
                @empty
                    <li class="px-6 py-4 text-gray-500">Geen zakelijke gebruikers gevonden.</li>
                @endforelse

            </ul>
        </div>
        @if ($users->hasPages())
            <div class="mt-4 px-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
 

</x-layout>
