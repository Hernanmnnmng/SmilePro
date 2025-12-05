{{-- resources/views/patients/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Patiënten Overzicht') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Patiënten</h3>
                        <a href="{{ route('patients.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Nieuwe patiënt
                        </a>
                    </div>

                    @if($patients->isEmpty())
                        <div class="text-yellow-700 bg-yellow-100 border border-yellow-300 px-4 py-2 rounded">
                            Er zijn momenteel geen patiënten beschikbaar.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Naam
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Geboortedatum
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Contactgegevens
                                        </th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Acties
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $patient->name }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $patient->birth_date?->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $patient->contact }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-right space-x-2">
                                                <a href="{{ route('patients.show', $patient) }}"
                                                   class="text-xs text-gray-700 dark:text-gray-300 underline">
                                                    Bekijken
                                                </a>
                                                <a href="{{ route('patients.edit', $patient) }}"
                                                   class="text-xs text-blue-600 dark:text-blue-400 underline">
                                                    Bewerken
                                                </a>
                                                <form action="{{ route('patients.destroy', $patient) }}"
                                                      method="POST"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Weet je zeker dat je deze patiënt wilt verwijderen?')"
                                                        class="text-xs text-red-600 dark:text-red-400 underline">
                                                        Verwijderen
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>