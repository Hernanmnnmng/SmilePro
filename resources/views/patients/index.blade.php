
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patiënten Overzicht</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Patiënten Overzicht</h1>

        @if($patients->isEmpty())
            <div class="alert alert-warning">
                Er zijn momenteel geen patiënten beschikbaar.
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Geboortedatum</th>
                        <th>Contactgegevens</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->birth_date }}</td>
                            <td>{{ $patient->contact }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>