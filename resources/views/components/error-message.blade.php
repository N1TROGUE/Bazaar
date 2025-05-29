<div {{ $attributes->merge(['class' => 'p-4 mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg w-full sm:col-span-6']) }}>
    <p class="font-medium">{{ $slot }}</p>
</div>
