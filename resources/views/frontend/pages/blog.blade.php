@extends('frontend.app')

@section('content')
    <main>
        <div class="it-breadcrumb-area fix p-relative" data-background="assets/img/breadcrumb/breadcrumb-bg.jpg">
            <div class=it-breadcrumb-shape-1>
                <img src="assets/img/breadcrumb/breadcrumb-shape.png" alt="">
            </div>
            <div class=container>
                <div class=row>
                    <div class=col-md-12>
                        <div class=it-breadcrumb-content>
                            <div class="it-breadcrumb-title-box mb-25 z-index-3">
                                <h3 class="it-breadcrumb-title text-white">Diensten</h3>
                            </div>
                            <div class=it-breadcrumb-list-wrap>
                                <div class="it-breadcrumb-list z-index-3">
                                    <span><a href="/">Home</a></span>
                                    <span class=dvdr>//</span>
                                    <span><b>Diensten</b></span>
                                </div>
                            </div>
                        </div>
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
