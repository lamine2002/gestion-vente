<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        p {
            margin-bottom: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1 style="
        text-align: center;
        margin-bottom: 20px;
    ">
        Liste des clients
    </h1>
{{-- Nombre de clients   --}}
    <p>
        Nombre de clients: {{ $customers->count() }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Sexe</th>
                <th>Nombre de commandes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->lastname }}</td>
                    <td>{{ $customer->firstname }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->sex }}</td>
                    <td>{{ $customer->orders()->count() }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>


</body>
</html>
