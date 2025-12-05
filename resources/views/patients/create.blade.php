<x-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <h1 class="text-2xl font-semibold mb-6">Patiënt aanmaken</h1>
          
          @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
              {{ $errors->first() }}
            </div>
          @endif

          <form method="POST" action="{{ route('patients.store') }}" class="space-y-6">
            @csrf
            
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Naam</label>
              <input 
                type="text" 
                name="name" 
                id="name"
                value="{{ old('name') }}" 
                required
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
            </div>

            <div>
              <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Geboortedatum</label>
              <input 
                type="date" 
                name="birth_date" 
                id="birth_date"
                value="{{ old('birth_date') }}" 
                required
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
            </div>

            <div>
              <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Contact</label>
              <input 
                type="text" 
                name="contact" 
                id="contact"
                value="{{ old('contact') }}"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
            </div>

            <div class="flex items-center gap-4">
              <button 
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                Opslaan
              </button>
              <a 
                href="{{ route('patients.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                Annuleren
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-layout>
