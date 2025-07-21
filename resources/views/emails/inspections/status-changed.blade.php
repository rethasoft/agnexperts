<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Status Update - Inspectie</title>
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            padding: 20px 0;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .status-box {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }

        .status-change {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 10px 0;
        }

        .status-arrow {
            color: #6c757d;
            font-size: 20px;
            margin: 0 15px;
        }

        .old-status {
            color: #dc3545;
        }

        .new-status {
            color: #198754;
        }

        .inspection-details {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('frontend/assets/img/logo/agn-experts-logoo.png') }}" alt="AGN Experts Logo" class="logo">
            <h1>Status Update - Inspectie</h1>
        </div>
        <div class="content">
            <p>Hallo {{ $clientName }},</p>
            <p>Er is een update in de status van uw inspectie. Hieronder vindt u de details:</p>

            <div class="status-box">
                <h3>Status Wijziging</h3>
                <div class="status-change">
                    <span class="old-status"><strong>Oude status:</strong>
                        {{ $inspection->getStatusName($oldStatus) }}</span>
                    <span class="status-arrow">→</span>
                    <span class="new-status"><strong>Nieuwe status:</strong>
                        {{ $inspection->getStatusName($status) }}</span>
                </div>
            </div>

            <div class="inspection-details">
                <h3>Inspectie Details</h3>
                <p><strong>Dossiernummer:</strong> {{ $fileId }}</p>
                <p><strong>Datum inspectie:</strong> {{ $inspectionDate->format('d-m-Y') }}</p>
                @if ($inspection->address)
                    <p><strong>Locatie:</strong> {{ $inspection->address }}</p>
                @endif
            </div>

            <p>U kunt de volledige details van de inspectie bekijken door in te loggen op uw account.</p>

            <p>Met vriendelijke groet,<br>AGN Experts</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AGN Experts. Alle rechten voorbehouden.</p>
        </div>
    </div>
</body>

</html>
