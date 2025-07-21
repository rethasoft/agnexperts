@extends('frontend.app')
@section('title', $service->name)
@section('description', $service->short_description)
@section('image', asset($service->image))
@section('url', route('service.detail', $service->slug))
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
                                <h1 class="it-breadcrumb-title text-white">{{ $service->name }}</h1>
                            </div>
                            <div class=it-breadcrumb-list-wrap>
                                <div class="it-breadcrumb-list z-index-3">
                                    <span><a href="?page=home">Home</a></span>
                                    <span class=dvdr>//</span>
                                    <span><a href="?page=diensten">Diensten</a></span>
                                    <span class=dvdr>//</span>
                                    <span><b>{{ $service->name }}</b></span>
                                </div>
                            </div>
                        </div>
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
