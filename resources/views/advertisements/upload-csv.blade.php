@section('title', 'Contracten')

<x-layout>
    <x-slot:heading>
        Upload een CSV bestand
    </x-slot:heading>
    
    <form method="POST" action="{{ route('advertisements.uploadCSV') }}" enctype="multipart/form-data">
      @csrf
        <div class="space-y-12">
          <div class="border-b border-gray-900/10 pb-12">
            <!-- Successmelding -->
                    @if(session('success'))
                        <div class="p-4 mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg w-full sm:col-span-6">
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    @endif
                    
            <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
      
              <div class="col-span-full">
                <label for="cover-photo" class="block text-sm/6 font-medium text-gray-900">Upload CSV <span style="color: red;"> *</span></label>
                <div class="mt-2 flex justify-center w-1/2 rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                  <div class="text-center">
                    <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
                    </svg>
                    <div class="mt-4 flex text-sm/6 text-gray-600">
                      <label style="color: {{ $appSettings->button_color ?? '#4f46e5' }}" for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-indigo-500">
                        <span>Kies een bestand</span>
                        <input id="file-upload" name="file" type="file" class="sr-only">
                      </label>
                      <p class="pl-1">of drag and drop</p>
                    </div>
                    <p class="text-xs/5 text-gray-600">CSV tot 10MB</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        <!-- Validation errors -->
        @if ($errors->any())
        <ul class="px-4 py-2 bg-red-100">
          @foreach ($errors->all() as $error)
              <li class="my-2 text-red-500">{{ $error }}</li>
          @endforeach
        </ul>
        @endif

        </div>
      
        <div class="mt-6 flex items-center justify-end gap-x-6">
          <button type="button" class="text-sm/6 font-semibold text-gray-900">Annuleren</button>
          <button style="background-color: {{ $appSettings->button_color ?? '#4f46e5' }}" type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Uploaden</button>
        </div>

      </form>
      
</x-layout>



