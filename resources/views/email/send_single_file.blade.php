<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificaat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0 30px;
            border-bottom: 1px solid #eee;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 0;
            text-align: left;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .highlight {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .contact-info {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px dashed #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('frontend/assets/img/logo/agn-experts-logoo.png') }}" alt="AGN Experts Logo" class="logo">
            <h1>Certificaat</h1>
        </div>
        <div class="content">
            <p>Geachte heer/mevrouw,</p>
            
            <p>Wij danken u voor het vertrouwen in AGN Experts. Hierbij ontvangt u het certificaat van uw recente keuring.</p>
            
            <div class="highlight">
                <p style="margin:0;"><strong>Belangrijke informatie:</strong></p>
                <ul style="margin-top:10px;">
                    <li>Het certificaat is als bijlage toegevoegd aan deze e-mail</li>
                    <li>Bewaar dit document zorgvuldig voor uw administratie</li>
                    <li>Het certificaat is een officieel document</li>
                </ul>
            </div>

            <p>Heeft u vragen over het certificaat of onze dienstverlening? Aarzel dan niet om contact met ons op te nemen.</p>

            <div class="contact-info">
                <p><strong>Contact:</strong><br>
                Tel: +32 123 45 67<br>
                E-mail: info@agnexperts.be</p>
            </div>

            <p>Met vriendelijke groeten,<br>
            <strong>AGN Experts Team</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AGN Experts. Alle rechten voorbehouden.</p>
        </div>
    </div>
</body>
</html>