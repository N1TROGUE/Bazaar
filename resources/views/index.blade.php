@section('title', 'De Bazaar')

<x-layout>
    <x-slot:heading>
        Welkom, {{ Auth::user()->name }}
    </x-slot:heading>
    
    <h1> </h1>
</x-layout>



