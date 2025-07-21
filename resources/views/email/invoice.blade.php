<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Template</title>
</head>
<body>
    <div style="padding: 90px 0;width:750px;">
        <div style="font-family:Arial, Helvetica, sans-serif;padding: 1rem;border-radius:5px;padding: .5rem 1.5rem;">
            <p style="font-size:16px;">Beste {{ $data->name . ' ' .$data->surname }},</p>

            <p style="font-size:16px;">Hierbij ontvangt u de factuur als bijlage. Zodra de betaling van de factuur is voltooid, <br>ontvangt u automatisch het verslag.</p>
            <p style="font-size:16px;">Met vriendelijke groeten,</p>

            <p style="font-size:16px;">Team AGNexperts</p>
        </div>
    </div>
</body>
</html>