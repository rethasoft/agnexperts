<!doctype html>
<html class=no-js lang="nl">

<head>
    <meta charset=utf-8>
    <meta http-equiv=x-ua-compatible content="ie=edge">
    <title>@yield('title')</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="@yield('title')">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords', 'EPC Attest, keuring, asbestattest, elektrische keuring, vastgoedkeuring, België')">
    <meta name="author" content="@yield('author', 'AGN Experts')">
    <meta name="robots" content="index, follow">
    <meta name="language" content="nl">
    <meta name="geo.region" content="BE">
    <meta name="geo.placename" content="Antwerpen">
    <meta name="geo.position" content="51.1709;4.3957">
    <meta name="ICBM" content="51.1709, 4.3957">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/assets/img/logo/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/img/logo/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/assets/img/logo/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/assets/img/logo/favicon.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="@yield('url')">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">
    <meta property="og:image" content="@yield('image')">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="AGN Experts">
    <meta property="og:locale" content="nl_BE">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="@yield('url')">
    <meta property="twitter:title" content="@yield('title')">
    <meta property="twitter:description" content="@yield('description')">
    <meta property="twitter:image" content="@yield('image')">
    <meta property="twitter:image:width" content="1200">
    <meta property="twitter:image:height" content="600">
    <meta property="twitter:site" content="@agnexperts">
    <meta property="twitter:creator" content="@agnexperts">

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/favicon.png">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/animate.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/custom-animation.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/slick.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/nice-select.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/swiper-bundle.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/flaticon_solvra.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/font-awesome-pro.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/spacing.css') }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel=stylesheet href="{{ asset('frontend/assets/css/style.css?v=1.0.2') }}">
    <script src="{{ asset('frontend/assets/js/jquery.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- WhatsApp Button Styles -->
    <style>
        .whatsapp-button {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: white;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            line-height: 60px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .whatsapp-button:hover {
            background-color: #128c7e;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.6);
        }
        
        .whatsapp-button i {
            font-size: 32px;
        }
        
        @media (max-width: 768px) {
            .whatsapp-button {
                width: 50px;
                height: 50px;
                bottom: 80px;
                right: 15px;
            }
            
            .whatsapp-button i {
                font-size: 26px;
            }
        }
    </style>
</head>

<body id=body class=it-magic-cursor>
    <div id=preloader>
        <div class=preloader>
            <span></span>
            <span></span>
        </div>
    </div>
    <div id=magic-cursor>
        <div id=ball></div>
    </div>
    <button class="scroll-top scroll-to-target" data-target=html>
        <i class="fa-solid fa-arrow-up"></i>
    </button>
    
    <!-- WhatsApp Button -->
    <a href="https://wa.me/+320451031121" target="_blank" class="whatsapp-button" aria-label="WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
    <div class=it-offcanvas-area>
        <div class=itoffcanvas>
            <div class=itoffcanvas__close-btn>
                <button class=close-btn><i class="fal fa-times"></i></button>
            </div>
            <div class="itoffcanvas__logo">
                <a href="/">
                    <img src="{{ asset('frontend/assets/img/logo/agn-experts-logoo-white.png') }}" alt="Agn Experts">
                </a>
            </div>
            <div class=it-menu-mobile></div>
        </div>
    </div>
    <div class=body-overlay></div>
    <header class=header-height>
        <div class="it-header-area-2 it-header-style-4 it-header-transparent">
            <div id=header-sticky class="it-header-bottom-4 it-header-mob-space z-index-5">
                <div class=container>
                    <div class="it-header-2-menu-wrapp p-relative">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-6 col-md-6 col-6">
                                <div class="it-main-logo-2 mt-3">
                                    <a href="/">
                                        <img src="{{ asset('frontend/assets/img/logo/agn-experts-logoo.png') }}" alt="Agn Experts"
                                            style="height: 80px; width: auto;">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-8 d-none d-xl-block">
                                <div class="it-main-menu it-main-menu-2 text-center">
                                    <nav class=it-menu-content>
                                        <ul>
                                            <li><a href="{{ route('home') }}">Home</a></li>
                                            <li><a href="{{ route('tarieven') }}">Tarieven</a></li>
                                            <li class="has-dropdown">
                                                <a href="{{ route('services') }}">Diensten</a>
                                                @php
                                                    $services_menu = App\Models\Service::all();
                                                @endphp
                                                <ul class="submenu">
                                                    @if ($services_menu->count() > 0)
                                                        @foreach ($services_menu as $service)
                                                            <li><a href="{{ route('service.detail', $service->slug) }}">{{ $service->name }}</a></li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </li>
                                            <li><a href="{{ route('about') }}">Over Ons</a></li>
                                            
                                            {{-- <li class="has-dropdown">
                                                <a href="?page=epc">EPC</a>
                                                <ul class="submenu">
                                                    <li><a href="?page=epc">Bruseel</a></li>
                                                    <li><a href="?page=epc">Vlaanderen</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="{{ route('asbest') }}">Asbest</a></li> --}}
                                            <li><a href="{{ route('contact') }}">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 col-6">
                                <div class="it-header-2-right-box d-flex align-items-center justify-content-end">
                                    <div class="it-header-2-right-btn d-none d-md-block">
                                        <a href="{{ route('order.index') }}" class="it-btn-green px-4">Keuring
                                            Aanvragen</a>
                                    </div>
                                    <div class="it-header-bar-wrap d-xl-none">
                                        <button class="it-header-bar it-menu-bar"><i
                                                class="fa-sharp fa-regular fa-bars-staggered"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>


    @yield('content')

    <footer>
        <div class="it-footer-2-area p-relative" data-background="{{ asset('frontend/assets/img/footer/footer-bg.jpg') }}"
            style="background-image:url({{ asset('frontend/assets/img/footer/footer-bg.jpg') }})">
            <div class=it-footer-2-border>
                <div class=container>
                    <div class=row>
                        <div class=col-xl-12>
                            <div class="it-footer-2-top-wrap d-flex align-items-center wow itfadeUp"
                                data-wow-duration=.9s data-wow-delay=.3s>
                                <div class=it-footer-2-top-item>
                                    <div
                                        class="it-footer-2-top-content d-flex align-items-center justify-content-start justify-content-lg-center">
                                        <div class=it-footer-2-top-icon>
                                            <a href="#"><i class="fa-regular fa-location-dot"></i></a>
                                        </div>
                                        <div class=it-footer-2-top-text>
                                            <span>Adres</span>
                                            <h3 class=it-section-title-sm>
                                                <a href="#">Jules Moretuslei 52 
                                                    <br>2610 Wilrijk (Antwerpen)
                                                    <br>Belgie</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class=it-footer-2-top-item>
                                    <div
                                        class="it-footer-2-top-content d-flex align-items-center justify-content-start justify-content-lg-center">
                                        <div class=it-footer-2-top-icon>
                                            <a href="#"><i class="fa-solid fa-phone"></i></a>
                                        </div>
                                        <div class=it-footer-2-top-text>
                                            <span>Telefoon:</span>
                                            <h3 class=it-section-title-sm>
                                                <a href="tel:+32 (0)451 031 121">+32 (0)451 031 121</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class=it-footer-2-top-item>
                                    <div
                                        class="it-footer-2-top-content d-flex align-items-center justify-content-start justify-content-lg-center">
                                        <div class=it-footer-2-top-icon>
                                            <a href="#"><i class="fa-regular fa-envelope"></i></a>
                                        </div>
                                        <div class=it-footer-2-top-text>
                                            <span>E-mail:</span>
                                            <h3 class=it-section-title-sm>
                                                <a href="mailto:info@agnexperts.be">info@agnexperts.be</a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="it-footer-2-bottom pt-110 pb-60">
                <div class=container>
                    <div class=row>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-50">
                            <div class="it-footer-widget footer-2-col-1 wow itfadeUp" data-wow-duration=.9s
                                data-wow-delay=.5s>
                                <div class="it-footer-logo mb-30">
                                    <a href="/">
                                        <img src="{{ asset('frontend/assets/img/logo/agn-experts-logoo-white.png') }}" alt="Agn Experts Logo"
                                            style="height: 80px; width: auto;">
                                    </a>
                                </div>
                                <div class=it-footer-content>
                                    <p>Professionele keuringsdiensten voor uw vastgoed. Erkend voor EPC certificaten,
                                        elektrische keuringen en asbestattesten in heel België.</p>
                                    <div class="it-footer-social footer-social-2">
                                        <a href="https://www.instagram.com/agnexperts?igsh=MXI1eDZ2enQzYzhtOQ==" aria-label="Instagram" target="_blank"><i
                                                class="fa-brands fa-instagram"></i></a>
                                        <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-50">
                            <div class="it-footer-widget footer-2-col-2 wow itfadeUp" data-wow-duration=.9s
                                data-wow-delay=.7s>
                                <h3 class="it-footer-widget-title mb-35">Diensten</h3>
                                <div class=it-footer-list>
                                    <ul>
                                        @php
                                            $services = App\Models\Service::all();
                                        @endphp
                                        @foreach($services as $service)
                                            <li><a href="{{ route('service.detail', $service->slug) }}">{{ $service->name }}</a></li>
                                        @endforeach
                                        <li><a href="{{ route('contact') }}">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-50">
                            <div class="it-footer-widget footer-2-col-3 wow itfadeUp" data-wow-duration=.9s
                                data-wow-delay=.8s>
                                <h3 class="it-footer-widget-title mb-35">Informatie</h3>
                                <div class=it-footer-list>
                                    <ul>
                                        <li><a href="{{ route('about') }}">Over Ons</a></li>
                                        <li><a href="{{ route('tarieven') }}">Tarieven</a></li>
                                        <li><a href="{{ route('home') }}">Veelgestelde Vragen</a></li>
                                        <li><a href="{{ route('blog') }}">Blog</a></li>
                                        <li><a href="{{ route('blog') }}">Nieuws</a></li>
                                        <li><a href="{{ route('home') }}">Wetgeving</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-50">
                            <div class="it-footer-widget p-relative footer-2-col-4 wow itfadeUp" data-wow-duration=.9s
                                data-wow-delay=.9s>
                                <h3 class="it-footer-widget-title mb-45">Bedrijfsgegevens</h3>
                                <div class=it-footer-list>
                                    <ul>
                                        <li><a href="{{ route('tarieven') }}">Jules Moretuslei 52 </a></li>
                                        <li><a href="{{ route('tarieven') }}">2610 Wilrijk (Antwerpen)</a></li>
                                        <li><a href="tel:+32 (0)451 031 121">+32 (0)451 031 121</a></li>
                                        <li><a href="mailto:info@agnexperts.be">info@agnexperts.be</a></li>
                                        <li><a href="{{ route('about') }}">BTW nummer: BE1000.146.907</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="it-copyright-2-area pt-20 pb-20">
                <div class=container>
                    <div class="row align-items-center">
                        <div class="col-xl-6 col-lg-7">
                            <div class="it-copyright-content text-center text-lg-start wow itfadeUp"
                                data-wow-duration=.9s data-wow-delay=.5s>
                                <p>Copyright © 2024 <span><a href="/">EPC Keuringen</a></span>. Alle rechten
                                    voorbehouden.</p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-5">
                            <div class="it-copyright-privacy text-center text-lg-end wow itfadeUp"
                                data-wow-duration=.9s data-wow-delay=.7s>
                                <a href="https://rethasoft.com/?lang=nl" target="_blank">Gemaakt door Rethasoft</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/gsap.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jarallax.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/magnific-popup.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/purecounter.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/swiper-bundle.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope-pkgd.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
</body>

</html>
