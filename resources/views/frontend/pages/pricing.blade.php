@extends('frontend.app')

@section('title', 'Tarieven | AGN Experts - Uw Partner voor Technische Keuringen')
@section('description', 'Ontdek onze transparante prijzen voor al onze diensten. Wij bieden professionele keuringen en certificeringen aan voor uw woning of gebouw tegen concurrerende tarieven. Contacteer ons voor een persoonlijke offerte.')
@section('keywords', 'tarieven, prijzen, EPC Attest, keuring, AGN Experts, België')
@section('author', 'AGN Experts')
@section('canonical', route('tarieven'))
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
        
        @php
            // Ana kategorileri çek (category_id = 0 olanlar)
            $mainCategories = \App\Models\Type::where('category_id', 0)->get();
        @endphp
        
        @foreach($mainCategories as $mainCategory)
        <!-- {{ $mainCategory->name }} Table -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-12">
                <div class="pricing-table-wrapper mb-4">
                    <div class="pricing-table-title bg-primary text-white py-2 px-3 mb-0 rounded-top">
                        <h4 class="mb-0 text-white">{{ $mainCategory->name }}</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="text-align: left !important; width: 70%;">Type</th>
                                    <th style="text-align: left !important; width: 20%;">Prijzen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Bu ana kategorinin alt hizmetlerini çek
                                    $subServices = \App\Models\Type::where('category_id', $mainCategory->id)->get();
                                @endphp
                                
                                @foreach($subServices as $subService)
                                <tr>
                                    <td style="text-align: left !important; width: 70%;"><a href="{{ route('order.index') }}">{{ $subService->name }}</a></td>
                                    <td style="color:#216eb0; text-align: left !important; width: 20%;" class="fw-bold">
                                        <a href="{{ route('order.index') }}">€ {{ number_format($subService->price, 0) }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <a href="{{ route('contact') }}" class="btn btn-success btn-lg px-5 py-2 mt-3">Offerte Aanvragen</a>
                <p class="text-muted mt-3">Voor maatwerk of andere keuringen, neem gerust contact met ons op!</p>
            </div>
        </div>
    </div>
</main>
@endsection