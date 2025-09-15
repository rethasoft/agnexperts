@extends('frontend.app')
@section('title', $service->name . ' | AGN Experts')
@section('description', $service->short_description)
@section('keywords', $service->name . ', keuring, AGN Experts, België')
@section('author', 'AGN Experts')
@section('canonical', route('service.detail', $service->slug))
@section('image', asset($service->image))
@section('url', route('service.detail', $service->slug))
@section('content')
    <main>
        <div class="simple-header-bg py-5 mb-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <nav aria-label="breadcrumb" class="mb-2">
                            <ol class="breadcrumb small justify-content-center bg-transparent p-0 mb-1">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('services') }}">Diensten</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
                            </ol>
                        </nav>
                        <h1 class="simple-header-title mb-0">{{ $service->name }}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="it-service-details__area pt-120 pb-100">
            <div class=container>
                <div class=row>
                    <div class="col-xl-3 col-lg-3 mb-50">
                        <div class=it-sv-details__wrapp>
                            <div class="sidebar-wrapp mb-60 p-relative">
                                <div class="sidebar-widget mb-55">
                                    <h4 class="sidebar-widget-title mb-40">Onze Diensten</h4>
                                    <div class=sidebar-widget-list>
                                        @foreach ($services as $item)
                                            <a href="{{ route('service.detail', $item->slug) }}"
                                                class="{{ $service->id == $item->id ? 'active' : '' }}">{{ $item->name }}<i
                                                    class="fa-regular fa-angle-right"></i></a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="it-sv-details__contact p-relative"
                                data-background="assets/img/service/service-details/sidebar-service.jpg">
                                <div class="it-sv-details__contact-content text-center">
                                    <div class=it-sv-details__contact-text>
                                        <h3>Contacteer Ons</h3>
                                    </div>
                                    <div class=it-sv-details__contact-text>
                                        <span>Voor een vrijblijvende offerte</span>
                                        <a href="{{ route('contact') }}" class="it-btn-green white-bg">Vraag offerte aan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <div class="it-sv-details__wrapp ml-40">
                            <div class="it-sv-details__main-thumb mb-35">
                                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="img-fluid">
                            </div>
                            <h2 class=it-sv-details__title>{{ $service->name }}</h2>
                            
                            @if($service->regions && count($service->regions) > 0)
                                <div class="mb-4">
                                    <h5 class="mb-2">Beschikbaar in:</h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($service->regions as $region)
                                            <span class="badge bg-primary">{{ ucfirst($region) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="quill-content">
                                {!! $service->description !!}
                            </div>

                            <div class="text-center mt-50">
                                <a href="{{ route('order.index') }}" class="it-btn-green">
                                    <span>Vraag Nu Een Offerte Aan</span>
                                    <i class="fa-regular fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
