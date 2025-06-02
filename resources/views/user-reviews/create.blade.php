<x-layout>
    <x-slot:heading>
        @lang('review.title', ['seller' => $order->seller->name])
    </x-slot:heading>

    <div class="py-12 max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-300">
            <div class="p-6 sm:p-8">
                <h4 class="text-lg font-semibold mb-4">@lang('review.heading', ['seller' => $order->seller->name])</h4>
                <form action="{{ route('user-review.store', $order) }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        {{-- Rating --}}
                        <div>
                            <label for="rating" class="block mb-2 font-medium text-gray-700">@lang('review.rating_label')*</label>
                            <div class="relative mb-1.5">
                                <select style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" id="rating" name="rating"
                                        class="block w-full rounded-md border border-gray-300 py-2 pl-3 pr-10 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm appearance-none">
                                    <option value="">@lang('review.rating_select')</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            @error('rating')
                                <x-form-error>{{ $message }}</x-form-error>
                            @enderror
                        </div>

                        {{-- Comment --}}
                        <div class="mb-4">
                            <label for="comment" class="block mb-2 font-medium text-gray-700">@lang('review.comment_label')</label>
                            <textarea style="--tw-ring-color: {{ $appSettings->button_color ?? '#4f46e5' }};" name="comment" id="comment" rows="4"
                                      class="rounded-md border border-gray-300 w-full p-4 focus:outline-none focus:ring-2 focus:ring-indigo-600">{{ old('comment') }}</textarea>
                            @error('comment')
                                <x-form-error>{{ $message }}</x-form-error>
                            @enderror
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <x-form-button class="w-full">@lang('review.submit_button')</x-form-button>
                            <a href="{{ route('orders.index') }}" class="mt-3 block text-center text-sm text-indigo-600 hover:text-indigo-500">
                                @lang('review.cancel')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
