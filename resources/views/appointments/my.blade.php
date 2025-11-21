@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold display-6 mb-4">Mijn Afspraken</h2>
    @if($appointments->isEmpty())
        <div class="alert alert-info">Je hebt nog geen afspraken geboekt.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Starttijd</th>
                    <th>Eindtijd</th>
                    <th>Tandarts</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $a)
                    <tr>
                        <td>{{ $a->date }}</td>
                        <td>{{ $a->start_time }}</td>
                        <td>{{ $a->end_time }}</td>
                        <td>{{ $a->tandarts->name }}</td>
                        <td>{{ ucfirst($a->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
