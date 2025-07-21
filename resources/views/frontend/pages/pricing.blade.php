@extends('frontend.app')

@section('title', 'Tarieven | AGN Experts - Uw Partner voor Technische Keuringen')
@section('description', 'Ontdek onze transparante prijzen voor al onze diensten. Wij bieden professionele keuringen en certificeringen aan voor uw woning of gebouw tegen concurrerende tarieven. Contacteer ons voor een persoonlijke offerte.')
@section('url', url()->current())
@section('content')
<main>
    <!-- Simple & Minimal Page Header -->
    <div class="simple-header-bg py-5 mb-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <nav aria-label="breadcrumb" class="mb-2">
                        <ol class="breadcrumb small justify-content-center bg-transparent p-0 mb-1">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tarieven</li>
                        </ol>
                    </nav>
                    <h1 class="simple-header-title mb-0">Onze Tarieven</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-5">
        {{-- <div class="row justify-content-center mb-4">
            <div class="col-xl-8 text-center">
                <h1 class="mb-2">Onze Tarieven</h1>
                <p class="lead">Transparante prijzen voor al onze diensten. Profiteer van onze actuele acties!</p>
            </div>
        </div> --}}
        <!-- PEB Certificaat Table -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-12">
                <div class="pricing-table-wrapper mb-4">
                    <div class="pricing-table-title bg-primary text-white py-2 px-3 mb-0 rounded-top">
                        <h4 class="mb-0 text-white">PEB Certificaat</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Verlaagde Prijs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><a href="{{ route('order.index') }}">Studio max 50m²</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 185</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Appartement 50m²-75m²</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 225</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Appartement 75m²-125m²</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 235</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Appartement duplex</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 260</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Appartement triplex</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 290</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Gesloten woning</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 300</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Half open woning</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 335</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Open woning</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 360</a></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="pricing-table-wrapper mb-4">
                    <div class="pricing-table-title bg-primary text-white py-2 px-3 mb-0 rounded-top">
                        <h4 class="mb-0 text-white">EPC-Vlaanderen</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Onze Prijs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><a href="{{ route('order.index') }}">Appartement – Studio</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 145</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Gesloten woning</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 190</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Half open woning</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 195</a></td></tr>
                                <tr><td><a href="{{ route('order.index') }}">Open woning</a></td><td style="color:#216eb0" class="fw-bold"><a href="{{ route('order.index') }}">€ 220</a></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- EPC-Vlaanderen Table -->
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <a href="{{ route('contact') }}" class="btn btn-success btn-lg px-5 py-2 mt-3">Offerte Aanvragen</a>
                <p class="text-muted mt-3">Voor maatwerk of andere keuringen, neem gerust contact met ons op!</p>
            </div>
        </div>
    </div>
    <style>
        .pricing-table-title {
            background: var(--it-theme-1) !important;
            color: #fff !important;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .pricing-table-wrapper {
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #e3e6f0;
        }
        .minimal-breadcrumb-bg {
            background: #f5f7fa !important;
            border-bottom: 1.5px solid #e3e6f0;
        }
        .minimal-breadcrumb-title {
            color: #222;
            font-weight: 700;
            font-size: 2.1rem;
            letter-spacing: 0.5px;
        }
        .minimal-breadcrumb-link {
            color: var(--it-theme-1);
            font-size: 1.05rem;
            text-decoration: none;
        }
        .minimal-breadcrumb-link.fw-bold {
            color: #222;
        }
        .dvdr {
            color: #b0b0b0;
        }
        .modern-page-header {
            background: #fff;
        }
        .modern-header-card {
            background: #f8fafc;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.04);
            border: 1.5px solid #e3e6f0;
            position: relative;
            overflow: hidden;
        }
        .modern-header-title {
            font-size: 2.3rem;
            font-weight: 800;
            color: #222;
            letter-spacing: 0.5px;
            z-index: 2;
            position: relative;
        }
        .modern-header-desc {
            color: #555;
            font-size: 1.08rem;
            z-index: 2;
            position: relative;
        }
        .modern-header-bg-icon {
            position: absolute;
            right: 30px;
            top: 10px;
            font-size: 5.5rem;
            color: var(--it-theme-1, #003399);
            opacity: 0.06;
            z-index: 1;
            pointer-events: none;
        }
        .modern-header-card:after {
            content: '';
            display: block;
            height: 3px;
            width: 60px;
            background: var(--it-theme-1, #003399);
            border-radius: 2px;
            margin: 24px auto 0 auto;
        }
        .breadcrumb {
            font-size: 0.98rem;
            color: #888;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            color: #b0b0b0;
        }
        .breadcrumb-item a {
            color: var(--it-theme-1);
            text-decoration: none;
        }
        .breadcrumb-item.active {
            color: #222;
            font-weight: 600;
        }
        .simple-header-bg {
            background: #f7fafd;
            border-bottom: 1.5px solid #e3e6f0;
            margin-top: 96px;
        }
        .simple-header-title {
            color: #222;
            font-weight: 800;
            font-size: 2.1rem;
            letter-spacing: 0.5px;
        }
        @media (max-width: 768px) {
            .pricing-table-title { font-size: 1.1rem; }
            .table th, .table td { font-size: 0.95rem; padding: 0.5rem; }
            .minimal-breadcrumb-title { font-size: 1.2rem; }
            .it-breadcrumb-content { padding: 20px 0 !important; }
            .modern-header-title { font-size: 1.3rem; }
            .modern-header-card { padding: 1.2rem !important; }
            .modern-header-bg-icon { font-size: 3.2rem; right: 10px; top: 10px; }
            .alt-header-title { font-size: 1.3rem; }
            .alt-header-desc { font-size: 1rem; }
            .alt-page-header { padding: 2rem 0 !important; }
            .simple-header-title { font-size: 1.2rem; }
            .simple-header-bg { padding: 18px 0 !important; margin-top: 60px; }
        }
    </style>
</main>
@endsection