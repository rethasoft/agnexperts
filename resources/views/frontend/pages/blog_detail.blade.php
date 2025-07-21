@extends('frontend.app')
@section('title', $blog->title)
@section('description', $blog->short_description)
@section('image', asset($blog->image))
@section('url', route('blog.detail', $blog->slug))
@section('content')
    <main>
        <div class="it-breadcrumb-area fix p-relative" data-background="assets/img/breadcrumb/breadcrumb-bg.jpg">
            <div class=it-breadcrumb-shape-1>
                <img src="{{ asset('frontend/assets/img/breadcrumb/breadcrumb-shape.png') }}" alt="">
            </div>
            <div class=container>
                <div class=row>
                    <div class=col-md-12>
                        <div class=it-breadcrumb-content>
                            <div class="it-breadcrumb-title-box mb-25 z-index-3">
                                <h1 class="it-breadcrumb-title text-white">{{ $blog->title }}</h1>
                            </div>
                            <div class=it-breadcrumb-list-wrap>
                                <div class="it-breadcrumb-list z-index-3">
                                    <span><a href="{{ route('home') }}">Home</a></span>
                                    <span class=dvdr>//</span>
                                    <span><a href="{{ route('blog.index') }}">Diensten</a></span>
                                    <span class=dvdr>//</span>
                                    <span><b>{{ $blog->title }}</b></span>
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
                                    <h4 class="sidebar-widget-title mb-40">Andere Artikel</h4>
                                    <div class="list-group">
                                        @foreach ($blogs as $item)
                                            <a href="{{ route('blog.detail', $item->slug) }}"
                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $blog->id == $item->id ? 'active' : '' }}">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-1 fw-normal">{{ $item->title }}</h6>
                                                        {{-- <small class="text-muted">{{ Str::limit($item->short_description, 50) }}</small> --}}
                                                    </div>
                                                </div>
                                                <i class="fa-regular fa-angle-right"></i>
                                            </a>
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
                                <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="img-fluid">
                            </div>
                            <h2 class=it-sv-details__title>{{ $blog->title }}</h2>

                                {!! $blog->content !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
