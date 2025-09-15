@extends('frontend.app')

@section('title', 'AGN Experts | Professionele Keuringsdiensten in België')
@section('description', 'Ontdek onze uitgebreide keuringsdiensten: EPC certificaten, asbestcontrole, elektrische keuringen en meer. Professionele expertise voor uw vastgoed in heel België.')
@section('keywords', 'keuring diensten, EPC Attest, asbestcontrole, elektrische keuring, vastgoedkeuring, technische controle, energieprestatiecertificaat, woningkeuring België, professionele keuringsdienst, AGN Experts')
@section('author', 'AGN Experts')
@section('canonical', route('services'))

@section('content')
    <main>
        <div class="simple-header-bg py-5 mb-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <nav aria-label="breadcrumb" class="mb-2">
                            <ol class="breadcrumb small justify-content-center bg-transparent p-0 mb-1">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Diensten</li>
                            </ol>
                        </nav>
                        <h1 class="simple-header-title mb-0">Diensten</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="it-service-area p-relative pt-120 pb-50">
            <div class=container>
                <div class=row>
                    @foreach ($services as $service)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <a href="/dienst/{{ $service->slug }}" class="text-decoration-none">
                            <div class="it-service-item cursor-pointer hover-shadow">
                                <h3 class=it-section-title-sm>{{ $service->name }}</h3>
                                <p>{{ $service->short_description }}</p>
                                
                                @if($service->regions && count($service->regions) > 0)
                                    <div class="mt-3 mb-3">
                                        <small class="text-muted">Beschikbaar in:</small>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @foreach($service->regions as $region)
                                                <span class="badge bg-primary" style="font-size: 0.7rem;">{{ ucfirst($region) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="it-service-item-thumb mt-25">
                                    <img src="{{ asset($service->image) }}" alt="{{ $service->name }}">
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@endsection
