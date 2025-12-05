<x-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
              {{ session('success') }}
            </div>
          @endif
          @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
              {{ $errors->first() }}
            </div>
          @endif

          <h1 class="text-2xl font-semibold mb-6">{{ $patient->name }}</h1>
          
          <div class="space-y-4 mb-6">
            <div>
              <span class="font-medium text-gray-700">Geboortedatum:</span>
              <span class="text-gray-900">{{ $patient->birth_date }}</span>
            </div>
            <div>
              <span class="font-medium text-gray-700">Contact:</span>
              <span class="text-gray-900">{{ $patient->contact }}</span>
            </div>
          </div>

          <div class="flex items-center gap-4">
            <a 
              href="{{ route('patients.edit', $patient) }}"
              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              Bewerken
            </a>
            
            <form method="POST" action="{{ route('patients.destroy', $patient) }}" class="inline">
              @csrf
              @method('DELETE')
              <button 
                type="submit" 
                onclick="return confirm('Weet je zeker dat je deze patiënt wilt verwijderen?')"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                Verwijderen
              </button>
            </form>
            
            <a 
              href="{{ route('patients.index') }}"
              class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
              Terug naar overzicht
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layout>
