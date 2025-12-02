<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factuur {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            flex: 1;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section div {
            margin-bottom: 5px;
        }
        .total-amount {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SmilePro Tandartspraktijk</h1>
        <p>Factuur</p>
    </div>
    
    <div class="invoice-info">
        <div class="info-box">
            <h3>Factuurgegevens</h3>
            <p><strong>Factuurnummer:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Factuurdatum:</strong> {{ $invoice->invoice_date->format('d-m-Y') }}</p>
            <p><strong>Vervaldatum:</strong> {{ $invoice->due_date->format('d-m-Y') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
        </div>
        <div class="info-box">
            <h3>Patiëntgegevens</h3>
            <p><strong>Naam:</strong> {{ $invoice->patient->name }}</p>
            <p><strong>E-mail:</strong> {{ $invoice->patient->email }}</p>
            @if($invoice->appointment)
                <p><strong>Afspraak:</strong> {{ $invoice->appointment->date->format('d-m-Y') }}</p>
            @endif
        </div>
    </div>
    
    @if($invoice->description)
        <div style="margin-bottom: 20px;">
            <h3>Beschrijving</h3>
            <p>{{ $invoice->description }}</p>
        </div>
    @endif
    
    @if($invoice->items && count($invoice->items) > 0)
        <table>
            <thead>
                <tr>
                    <th>Omschrijving</th>
                    <th>Aantal</th>
                    <th>Prijs</th>
                    <th>Totaal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item['description'] ?? '' }}</td>
                        <td>{{ $item['quantity'] ?? 1 }}</td>
                        <td>€ {{ number_format($item['price'] ?? 0, 2, ',', '.') }}</td>
                        <td>€ {{ number_format(($item['quantity'] ?? 1) * ($item['price'] ?? 0), 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    <div class="total-section">
        <div>
            <strong>Subtotaal:</strong> € {{ number_format($invoice->amount, 2, ',', '.') }}
        </div>
        @if($invoice->vat_amount > 0)
            <div>
                <strong>BTW:</strong> € {{ number_format($invoice->vat_amount, 2, ',', '.') }}
            </div>
        @endif
        <div class="total-amount">
            <strong>Totaal:</strong> € {{ number_format($invoice->total_amount, 2, ',', '.') }}
        </div>
    </div>
    
    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Factuur
        </button>
    </div>
</body>
</html>




