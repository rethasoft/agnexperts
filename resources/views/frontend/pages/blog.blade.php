@extends('frontend.app')

@section('title', 'Blog | AGN Experts - Nieuws en Informatie over Keuringen')
@section('description', 'Lees onze blog artikelen over EPC certificaten, asbestattesten, elektrische keuringen en meer. Blijf op de hoogte van de laatste ontwikkelingen in vastgoedkeuringen.')
@section('keywords', 'blog, nieuws, EPC Attest, asbestattest, elektrische keuring, vastgoedkeuring, België')
@section('author', 'AGN Experts')
@section('canonical', route('blog'))

@section('content')
    <main>
        <div class="simple-header-bg py-5 mb-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <nav aria-label="breadcrumb" class="mb-2">
                            <ol class="breadcrumb small justify-content-center bg-transparent p-0 mb-1">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Blog</li>
                            </ol>
                        </nav>
                        <h1 class="simple-header-title mb-0">Blog</h1>
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
