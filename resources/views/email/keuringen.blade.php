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
            <h2>Agnexperts/h2>
            <p style="font-size:16px;">
                Klant : {{ $keuringen->client->name . ' ' . $keuringen->client->surname }} heeft een nieuwe keuring aangevraagd op het platform.
                <a href="{{ url('/tenant/keuringen/' . $keuringen->id) }}">hier</a> te klikken</p>
        </div>
    </div>
</body>
</html>