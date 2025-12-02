@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Factuur Details</h2>
        <div class="flex gap-2">
            <a href="{{ Auth::user()->role === 'management' ? route('invoices.all') : route('invoices.index') }}" class="text-blue-600 hover:text-blue-800 underline text-sm">← Terug</a>
            <a href="{{ route('invoices.download', $invoice->id) }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                Download PDF
            </a>
            @if(Auth::user()->role === 'management')
                <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" class="inline" onsubmit="return confirm('Weet je zeker dat je factuur {{ $invoice->invoice_number }} wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm">
                        Verwijder
                    </button>
                </form>
            @endif
        </div>
    </div>
    
    <div class="bg-white border rounded-lg p-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Factuurgegevens</h3>
                <p class="text-sm text-gray-600"><strong>Factuurnummer:</strong> {{ $invoice->invoice_number }}</p>
                <p class="text-sm text-gray-600"><strong>Factuurdatum:</strong> {{ $invoice->invoice_date->format('d-m-Y') }}</p>
                <p class="text-sm text-gray-600"><strong>Vervaldatum:</strong> {{ $invoice->due_date->format('d-m-Y') }}</p>
                <p class="text-sm text-gray-600">
                    <strong>Status:</strong> 
                    @php
                        $statusColors = [
                            'open' => 'bg-yellow-100 text-yellow-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'overdue' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusLabels = [
                            'open' => 'Open',
                            'paid' => 'Betaald',
                            'overdue' => 'Achterstallig',
                            'cancelled' => 'Geannuleerd',
                        ];
                    @endphp
                    <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                    </span>
                </p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Patiëntgegevens</h3>
                <p class="text-sm text-gray-600"><strong>Naam:</strong> {{ $invoice->patient->name }}</p>
                <p class="text-sm text-gray-600"><strong>E-mail:</strong> {{ $invoice->patient->email }}</p>
                @if($invoice->appointment)
                    <p class="text-sm text-gray-600"><strong>Afspraak:</strong> {{ $invoice->appointment->date->format('d-m-Y') }}</p>
                @endif
            </div>
        </div>
        
        @if($invoice->description)
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Beschrijving</h3>
                <p class="text-sm text-gray-600">{{ $invoice->description }}</p>
            </div>
        @endif
        
        @if($invoice->items && count($invoice->items) > 0)
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Factuurregels</h3>
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="py-2 px-4 border-b text-left text-sm font-semibold">Omschrijving</th>
                            <th class="py-2 px-4 border-b text-left text-sm font-semibold">Aantal</th>
                            <th class="py-2 px-4 border-b text-left text-sm font-semibold">Prijs</th>
                            <th class="py-2 px-4 border-b text-left text-sm font-semibold">Totaal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="py-2 px-4 border-b text-sm">{{ $item['description'] ?? '' }}</td>
                                <td class="py-2 px-4 border-b text-sm">{{ $item['quantity'] ?? 1 }}</td>
                                <td class="py-2 px-4 border-b text-sm">€ {{ number_format($item['price'] ?? 0, 2, ',', '.') }}</td>
                                <td class="py-2 px-4 border-b text-sm">€ {{ number_format(($item['quantity'] ?? 1) * ($item['price'] ?? 0), 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        
        <div class="border-t pt-4">
            <div class="flex justify-end">
                <div class="w-full md:w-64">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Subtotaal:</span>
                        <span class="text-sm font-semibold">€ {{ number_format($invoice->amount, 2, ',', '.') }}</span>
                    </div>
                    @if($invoice->vat_amount > 0)
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">BTW:</span>
                            <span class="text-sm font-semibold">€ {{ number_format($invoice->vat_amount, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-lg font-bold">Totaal:</span>
                        <span class="text-lg font-bold">€ {{ number_format($invoice->total_amount, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

