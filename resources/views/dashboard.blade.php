<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold display-5 mb-3">Dashboard</h2>
    </x-slot>
    <div class="container py-5">
        @php $role = auth()->user()->role; @endphp
        <div class="row g-4">
            @if ($role === 'patient')
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-calendar2-check display-4 text-primary"></i></div>
                            <h5 class="card-title fw-bold">Afspraken</h5>
                            <p class="card-text">Bekijk en beheer uw afspraken eenvoudig.</p>
                            <a href="{{ route('appointments.my') }}" class="btn btn-primary rounded-pill px-4">Mijn afspraken</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-calendar-plus display-4 text-success"></i></div>
                            <h5 class="card-title fw-bold">Afspraak maken</h5>
                            <p class="card-text">Boek een afspraak bij een tandarts.</p>
                            <a href="{{ url('/admin') }}" class="btn btn-success rounded-pill px-4">Kies tandarts</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-receipt display-4 text-success"></i></div>
                            <h5 class="card-title fw-bold">Facturen</h5>
                            <p class="card-text">Bekijk uw facturen en betalingen.</p>
                            <a href="#" class="btn btn-success rounded-pill px-4">Naar facturen</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-chat-dots display-4 text-warning"></i></div>
                            <h5 class="card-title fw-bold">Communicatie</h5>
                            <p class="card-text">Stuur berichten naar de praktijk.</p>
                            <a href="#" class="btn btn-warning rounded-pill px-4 text-white">Naar berichten</a>
                        </div>
                    </div>
                </div>
            @elseif (in_array($role, ['tandarts', 'mondhygienist', 'assistent']))
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-calendar2-week display-4 text-primary"></i></div>
                            <h5 class="card-title fw-bold">Agenda & Afspraken</h5>
                            <p class="card-text">Bekijk uw agenda en afspraken.</p>
                            <a href="{{ route('appointments.tandarts') }}" class="btn btn-primary rounded-pill px-4 mb-2">Naar agenda</a>
                            @if($role === 'tandarts')
                                <a href="{{ route('availability.new.index') }}" class="btn btn-success rounded-pill px-4">Beschikbaarheid instellen</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-clock-history display-4 text-success"></i></div>
                            <h5 class="card-title fw-bold">Beschikbaarheid</h5>
                            <p class="card-text">Beheer uw beschikbaarheid.</p>
                            <a href="{{ route('availability.index') }}" class="btn btn-success rounded-pill px-4">Naar beschikbaarheid</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-person-lines-fill display-4 text-info"></i></div>
                            <h5 class="card-title fw-bold">Patiëntinformatie</h5>
                            <p class="card-text">Bekijk patiëntinformatie.</p>
                            <a href="#" class="btn btn-info rounded-pill px-4 text-white">Naar patiënten</a>
                        </div>
                    </div>
                </div>
            @elseif ($role === 'management')
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-people-fill display-4 text-danger"></i></div>
                            <h5 class="card-title fw-bold">Gebruikersbeheer</h5>
                            <p class="card-text">Beheer gebruikers en rollen.</p>
                            <form action="{{ route('admin.index') }}" method="get">
                                <button type="submit" class="btn btn-danger rounded-pill px-4">Naar gebruikersbeheer</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-bar-chart-line-fill display-4 text-primary"></i></div>
                            <h5 class="card-title fw-bold">Statistieken</h5>
                            <p class="card-text">Bekijk statistieken en rapportages.</p>
                            <a href="#" class="btn btn-primary rounded-pill px-4">Naar statistieken</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <div class="mb-3"><i class="bi bi-clock-history display-4 text-success"></i></div>
                            <h5 class="card-title fw-bold">Beschikbaarheid</h5>
                            <p class="card-text">Beheer medewerker beschikbaarheid.</p>
                            <a href="{{ route('availability.index') }}" class="btn btn-success rounded-pill px-4">Naar beschikbaarheid</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="card shadow h-100 border-0 rounded-4">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold">Welkom!</h5>
                            <p class="card-text">Uw rol is niet herkend.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
