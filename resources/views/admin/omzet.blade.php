<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold display-5 mb-3">Omzet Bekijken</h2>
    </x-slot>

    <div class="container py-5">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Revenue Summary Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Totale Omzet</h6>
                            <i class="bi bi-currency-euro text-primary fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">€{{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                        <small class="text-muted">Alle tijd</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Dit Jaar</h6>
                            <i class="bi bi-calendar-check text-success fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">€{{ number_format($yearRevenue, 2, ',', '.') }}</h3>
                        <small class="text-muted">{{ date('Y') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Deze Maand</h6>
                            <i class="bi bi-calendar-month text-info fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">€{{ number_format($monthRevenue, 2, ',', '.') }}</h3>
                        <small class="text-muted">{{ date('F Y') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="text-muted mb-0">Aantal Facturen</h6>
                            <i class="bi bi-receipt text-warning fs-4"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ $totalInvoices }}</h3>
                        <small class="text-muted">Totaal</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by Status -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100 border-start border-success border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Betaald</h6>
                        <h4 class="fw-bold text-success mb-1">€{{ number_format($paidRevenue, 2, ',', '.') }}</h4>
                        <small class="text-muted">{{ $paidInvoices }} facturen</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100 border-start border-warning border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Onbetaald</h6>
                        <h4 class="fw-bold text-warning mb-1">€{{ number_format($unpaidRevenue, 2, ',', '.') }}</h4>
                        <small class="text-muted">{{ $unpaidInvoices }} facturen</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 h-100 border-start border-danger border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Achterstallig</h6>
                        <h4 class="fw-bold text-danger mb-1">€{{ number_format($overdueRevenue, 2, ',', '.') }}</h4>
                        <small class="text-muted">{{ $overdueInvoices }} facturen</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Maandelijkse Omzet {{ date('Y') }}</h5>
                        <canvas id="revenueChart" style="max-height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-bold mb-0">Recente Facturen</h5>
                            <a href="{{ route('invoices.all') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                Alle facturen bekijken
                            </a>
                        </div>
                        
                        @if($recentInvoices->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Factuurnummer</th>
                                            <th>Patiënt</th>
                                            <th>Datum</th>
                                            <th>Bedrag</th>
                                            <th>Status</th>
                                            <th class="text-end">Acties</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold">{{ $invoice->invoice_number }}</span>
                                                </td>
                                                <td>{{ $invoice->patient->name ?? 'N/A' }}</td>
                                                <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                                <td class="fw-semibold">€{{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                                                <td>
                                                    @if($invoice->status === 'paid')
                                                        <span class="badge bg-success rounded-pill">Betaald</span>
                                                    @elseif($invoice->status === 'unpaid')
                                                        <span class="badge bg-warning rounded-pill">Onbetaald</span>
                                                    @elseif($invoice->status === 'overdue')
                                                        <span class="badge bg-danger rounded-pill">Achterstallig</span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill">{{ $invoice->status }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                        <i class="bi bi-eye"></i> Bekijken
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <p class="text-muted mt-3">Geen facturen gevonden</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const monthlyData = @json(array_values($monthlyRevenueData));
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Omzet (€)',
                    data: monthlyData,
                    backgroundColor: 'rgba(13, 110, 253, 0.8)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '€' + context.parsed.y.toLocaleString('nl-NL', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€' + value.toLocaleString('nl-NL');
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
