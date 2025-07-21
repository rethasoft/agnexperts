<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Orderbevestiging</title>
    <style>
        body {font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333; margin: 0; padding: 20px;}
        .container {max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);}
        .header {text-align: center; padding: 20px 0 30px; border-bottom: 1px solid #eee;}
        .logo {max-width: 200px; height: auto; margin-bottom: 20px;}
        .header h1 {margin: 0; color: #333; font-size: 24px; font-weight: 600;}
        .content {padding: 20px 0;}
        .footer {text-align: center; font-size: 12px; color: #777; padding-top: 20px; border-top: 1px solid #eee;}
        table {width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td {padding: 8px; border-bottom: 1px solid #ddd; text-align: left;}
        th {background-color: #f1f1f1;}
        .total-row {background-color: #f8f9fa; font-weight: bold;}
        .service-name {color: #2c3038; font-weight: 500;}
        .price {color: #198754;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('frontend/assets/img/logo/agn-experts-logoo.png') }}" alt="AGN Experts Logo" class="logo">
            <h1>Orderbevestiging</h1>
        </div>
        <div class="content">
            <p>Hallo {{ explode(' ', $orderDetails['name'])[0] }},</p>
            <p>Uw bestelling is succesvol ontvangen. Hieronder vindt u de details van uw bestelling:</p>
            <h3>Bestellingsoverzicht</h3>
            <table>
                <thead>
                    <tr>
                        <th>Dienst</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                        <th>Totaal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedServicesValidation['selectedServices'] as $service)
                    <tr>
                        <td class="service-name">{{ $service['name'] }}</td>
                        <td>{{ $service['quantity'] }}</td>
                        <td class="price">€ {{ number_format($service['price'], 2) }}</td>
                        <td class="price">€ {{ number_format($service['total'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Totaalbedrag:</td>
                        <td class="price">€ {{ number_format(array_sum(array_column($selectedServicesValidation['selectedServices'], 'total')), 2) }}</td>
                    </tr>
                </tbody>
            </table>
            
            <h3>Contactgegevens</h3>
            <p><strong>Naam:</strong> {{ $orderDetails['name'] }}</p>
            <p><strong>E-mail:</strong> {{ $orderDetails['email'] }}</p>
            <p><strong>Telefoon:</strong> {{ $orderDetails['phone'] }}</p>
            @if(!empty($orderDetails['company_name']))
            <p><strong>Bedrijfsnaam:</strong> {{ $orderDetails['company_name'] }}</p>
            @endif
            @if(!empty($orderDetails['vat_number']))
            <p><strong>BTW-nummer:</strong> {{ $orderDetails['vat_number'] }}</p>
            @endif

            <h3>Adresgegevens</h3>
            <p>
                <strong>Keuringsadres:</strong><br>
                {{ $orderDetails['street'] }}, {{ $orderDetails['number'] }}
                @if(!empty($orderDetails['bus'])), Bus: {{ $orderDetails['bus'] }}@endif<br>
                {{ $orderDetails['postal_code'] }} {{ $orderDetails['city'] }}
            </p>

            <p>Bedankt voor uw bestelling,<br>AGN Experts</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AGN Experts. Alle rechten voorbehouden.</p>
        </div>
    </div>
</body>
</html>
