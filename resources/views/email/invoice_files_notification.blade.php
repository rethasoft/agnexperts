<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }

        .content {
            padding: 20px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Factuur Bestanden Kennisgeving</h2>
        </div>

        <div class="content">
            <p>Beste {{ $invoice->inspection->name }},</p>

            <p>Bedankt voor uw betaling. We hebben uw betaling voor factuurnummer #{{ $invoice->number }} succesvol
                ontvangen.</p>

            <p>De administratieve bestanden zijn als bijlage aan deze e-mail toegevoegd. Bewaar deze documenten
                zorgvuldig voor uw administratie.</p>

            <p>Details van de factuur:</p>
            <ul>
                <li>Factuurnummer: #{{ $invoice->number }}</li>
                <li>Factuurdatum: {{ $invoice->created_at->format('d-m-Y') }}</li>
                <li>Totaalbedrag: {{ $invoice->formatted_amount }}</li>
                <li>Inspectie Adres: {{ $invoice->inspection->formatted_address }}</li>
                <li>Inspectie Datum: {{ $invoice->inspection->inspection_date->format('d-m-Y') }}</li>
            </ul>

            <p>Inspectie Details:</p>
            <ul>
                @foreach ($invoice->inspection->items as $item)
                    <li>{{ $item->quantity }}x -
                        {{ $item->category ? $item->category->name . ' > ' : '' }}{{ $item->name }}</li>
                @endforeach
            </ul>

            <p>Heeft u vragen over deze factuur? Neem dan gerust contact met ons op via:</p>
            <ul>
                <li>Email: {{ config('company.email') }}</li>
                <li>Telefoon: {{ config('company.phone') }}</li>
            </ul>

            <p>Met vriendelijke groet,<br>
                {{ config('company.name') }}</p>
        </div <div class="footer">
        <p>Dit is een automatisch gegenereerde e-mail. Reageer niet op dit bericht.</p>
    </div>
    </div>
</body>

</html>
