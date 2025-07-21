<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Template</title>
</head>
<body>
    <div style="padding: 250px 0;">
        <div style="background:#eee;padding:16px;font-family:Arial, Helvetica, sans-serif;text-align:center;width:600px;padding:50px 25px;margin:0 auto;border-radius:5px;">
            <p style="font-size:24px;">Beste, {{ $keuringen->name }} </p>
            <p style="font-size:16px;">De status van u keuringsaanvraag voor {{ $keuringen->street . ' ' . $keuringen->postal_code . ' ' . $keuringen->number_bus }} is veranderd naar <b>{{ $newStatus }}</b>. U kunt deze keuring bekijken door <a href="{{ url('/client/keuringen/' . $keuringen->id) }}">hier</a> te klikken.</p>
            <p style="font-size:16px;">Team Agnexperts</p>
        </div>
    </div>
</body>
</html>