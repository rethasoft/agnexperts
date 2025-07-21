@extends('frontend.app')

@section('title', 'Contact | AGN Experts - Uw Partner voor Technische Keuringen')
@section('description', 'Neem contact op met AGN Experts voor al uw vragen over technische keuringen, EPC certificaten, asbestattesten en meer. Onze experts staan klaar om u te helpen.')
@section('image', asset('frontend/assets/img/agn-experts-social-logo-big.png'))
@section('url', route('contact'))

@section('content')
<main>
    <div class="it-breadcrumb-area fix p-relative" data-background="{{ asset('frontend/assets/img/breadcrumb/breadcrumb-bg.jpg') }}">
        <div class=it-breadcrumb-shape-1>
            <img src="{{ asset('frontend/assets/img/breadcrumb/breadcrumb-shape.png') }}" alt="">
        </div>
        <div class=container>
            <div class=row>
                <div class=col-md-12>
                    <div class=it-breadcrumb-content>
                        <div class="it-breadcrumb-title-box mb-25 z-index-3">
                            <h3 class="it-breadcrumb-title text-white">Contact</h3>
                        </div>
                        <div class=it-breadcrumb-list-wrap>
                            <div class="it-breadcrumb-list z-index-3">
                                <span><a href="{{ route('home') }}">Home</a></span>
                                <span class=dvdr>//</span>
                                <span><b>Contact</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="it-contact-area pt-120 pb-120">
        <div class=container>
            <div class="row align-items-center">
                <div class=col-xl-12>
                    <div class="it-contact-wrapp grey-bg">
                        <div class="it-contact-title-box pb-10 mb-40">
                            <h3 class=it-section-title>Neem Contact Op</h3>
                        </div>
                        <form action="" method="post" onsubmit="return false">
                            <div class=it-contact-input>
                                <input type=text placeholder="Volledige Naam *" required>
                            </div>
                            <div class=it-contact-input>
                                <input type=text placeholder="Telefoon *" required>
                            </div>
                            <div class=it-contact-input>
                                <input type=email placeholder="E-mail *" required>
                            </div>
                            <div class="it-contact-input mb-30">
                                <textarea placeholder="Bericht"></textarea>
                            </div>
                            <div class="it-contact-button mb-50">
                                <button class=it-btn-green type=submit>Verstuur Bericht</button>
                            </div>
                        </form>
                        <div class=it-contact-link>
                            <div class="it-contact-link-title mb-5">
                                <h3 class=it-section-title-sm>Contactgegevens:</h3>
                            </div>
                            <div class=it-contact-link-item>
                                <a href="tel:+32 (0)493 936 112">
                                    <i class=flaticon-phone-call></i> +32 (0)493 936 112
                                </a>
                                <a href="https://maps.app.goo.gl/1234567890">
                                    <i class=flaticon-location></i> 
                                    Adres Jules Moretuslei 52
                                    2610 Wilrijk (Antwerpen)
                                    Belgie                                    
                                </a>
                                <a href="mailto:info@agnexperts.be">
                                    <i class=flaticon-mail></i> info@agnexperts.be
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class=col-xl-6>
                    <div class=it-contact-map-area>
                        <div class=it-contact-map-wrapp>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2501.6119208371947!2d4.374229977396983!3d51.17094363522579!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c3f145f81bab2d%3A0xb3d9b817c0fa4ca8!2sJules%20Moretuslei%2052%2C%202610%20Antwerpen%2C%20Bel%C3%A7ika!5e0!3m2!1str!2str!4v1751971406864!5m2!1str!2str" style="border:0;" allowfullscreen="" loading=lazy referrerpolicy=no-referrer-when-downgrade></iframe>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</main>
@endsection
