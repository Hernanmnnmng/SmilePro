@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold mb-6">Mijn Facturen</h2>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    
    @if($invoices->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Factuurnummer</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Datum</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Vervaldatum</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Bedrag</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Status</th>
                        <th class="py-2 px-2 sm:px-4 border-b text-left text-xs sm:text-sm font-semibold">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-2 sm:px-4 border-b text-sm">{{ $invoice->invoice_number }}</td>
                            <td class="py-2 px-2 sm:px-4 border-b text-sm">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                            <td class="py-2 px-2 sm:px-4 border-b text-sm">{{ $invoice->due_date->format('d-m-Y') }}</td>
                            <td class="py-2 px-2 sm:px-4 border-b text-sm font-semibold">€ {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                            <td class="py-2 px-2 sm:px-4 border-b">
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
                            </td>
                            <td class="py-2 px-2 sm:px-4 border-b">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm text-center whitespace-nowrap">
                                        Bekijk
                                    </a>
                                    <a href="{{ route('invoices.download', $invoice->id) }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm text-center whitespace-nowrap">
                                        Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-sm text-gray-600">
            <p>Totaal aantal facturen: <strong>{{ $invoices->count() }}</strong></p>
        </div>
    @else
        <div class="bg-gray-100 border border-gray-300 rounded p-6 text-center">
            <p class="text-gray-600 text-lg">Er zijn geen facturen beschikbaar</p>
            <p class="text-gray-500 text-sm mt-2">U heeft nog geen facturen ontvangen.</p>
        </div>
    @endif
</div>
@endsection




