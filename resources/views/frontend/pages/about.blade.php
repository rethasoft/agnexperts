@extends('frontend.app')

@section('title', 'Over Ons | AGN Experts - Uw Betrouwbare Partner voor Technische Keuringen')
@section('description', 'Ontdek AGN Experts, uw specialist in technische keuringen en expertises. Met jarenlange ervaring en expertise bieden wij professionele diensten voor al uw keuringsbehoeften in België.')
@section('url', url()->current())

@section('content')
<main>
    <div class="it-breadcrumb-area fix p-relative" data-background="{{ asset('frontend/assets/img/breadcrumb/breadcrumb-bg.jpg') }}">
        <div class=it-breadcrumb-shape-1>
            <img src="{{ asset('frontend/assets/img/breadcrumb/breadcrumb-shape.png') }}" alt="">
        </div>
        <!--<div class=it-breadcrumb-transparent-text>
            <h3 class=it-breadcrumb-transparent-title>Agn</h3>
        </div>-->
        <div class=container>
            <div class=row>
                <div class=col-md-12>
                    <div class=it-breadcrumb-content>
                        <div class="it-breadcrumb-title-box mb-25 z-index-3">
                            <h3 class="it-breadcrumb-title text-white">Over Ons</h3>
                        </div>
                        <div class=it-breadcrumb-list-wrap>
                            <div class="it-breadcrumb-list z-index-3">
                                <span><a href="/">Home</a></span>
                                <span class=dvdr>//</span>
                                <span><b>Over Ons</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="it-about-area it-about-style-4 p-relative pt-120 pb-135">
        <div class=it-about-top-shape>
            <span>
                <svg width=137 height=235 viewBox="0 0 137 235" fill=none xmlns="http://www.w3.org/2000/svg">
                    <path d="M93.04 227.578C57.2143 243.259 34.721 225.142 53.8387 205.518C63.634 195.468 88.8983 181.727 93.8204 181.906C103.186 182.226 94.0861 194.298 81.2364 200.475C66.177 207.707 48.9359 207.467 41.1614 202.098C41.1614 202.098 8.41793 182.068 73.7519 135.18C139.09 88.2877 135.085 113.77 135.085 113.77C135.085 113.77 133.013 134.039 81.0381 157.964C29.0629 181.883 -1.38965 157.797 25.0559 127.807C51.5029 97.8193 101.476 69.6898 110.135 75.815C118.777 81.9501 91.5932 108.487 59.5239 124.056C27.4414 139.622 -11.9095 134.765 6.78821 101.204C25.4991 67.6413 63.8691 53.4444 68.5648 58.2383C73.2483 63.0433 59.9017 88.3629 26.3421 94.656C-8.29271 99.3085 1.62113 71.9878 22.2363 57.9815C30.77 52.1905 32.1407 46.6563 31.9202 40.3858C30.9036 12.25 24.6063 0.906935 19.5376 1.35249C14.4739 1.83034 7.65156 11.3723 23.4821 30.6523C34.9083 43.1632 41.4266 36.6556 43.7771 27.1005" stroke=currentColor stroke-width=2 stroke-miterlimit=10 />
                </svg>
            </span>
        </div>
        <div class=container>
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="it-about-thumb-box p-relative wow itfadeLeft" data-wow-duration=.9s data-wow-delay=.5s>
                        <div class=it-about-shape-box>
                            <div class=it-about-shape-1>
                                <span>
                                    <svg width=49 height=292 viewBox="0 0 49 292" fill=none xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule=evenodd clip-rule=evenodd d="M7 3.5C7 5.43311 5.43298 7 3.5 7C1.56702 7 0 5.43311 0 3.5C0 1.56689 1.56702 0 3.5 0C5.43298 0 7 1.56689 7 3.5ZM21 3.5C21 5.43311 19.433 7 17.5 7C15.567 7 14 5.43311 14 3.5C14 1.56689 15.567 0 17.5 0C19.433 0 21 1.56689 21 3.5ZM31.5 7C33.433 7 35 5.43311 35 3.5C35 1.56689 33.433 0 31.5 0C29.567 0 28 1.56689 28 3.5C28 5.43311 29.567 7 31.5 7ZM49 3.5C49 5.43311 47.433 7 45.5 7C43.567 7 42 5.43311 42 3.5C42 1.56689 43.567 0 45.5 0C47.433 0 49 1.56689 49 3.5ZM7 22.5C7 24.4331 5.43298 26 3.5 26C1.56702 26 0 24.4331 0 22.5C0 20.5669 1.56702 19 3.5 19C5.43298 19 7 20.5669 7 22.5ZM21 22.5C21 24.4331 19.433 26 17.5 26C15.567 26 14 24.4331 14 22.5C14 20.5669 15.567 19 17.5 19C19.433 19 21 20.5669 21 22.5ZM31.5 26C33.433 26 35 24.4331 35 22.5C35 20.5669 33.433 19 31.5 19C29.567 19 28 20.5669 28 22.5C28 24.4331 29.567 26 31.5 26ZM49 22.5C49 24.4331 47.433 26 45.5 26C43.567 26 42 24.4331 42 22.5C42 20.5669 43.567 19 45.5 19C47.433 19 49 20.5669 49 22.5ZM3.5 45C5.43298 45 7 43.4331 7 41.5C7 39.5669 5.43298 38 3.5 38C1.56702 38 0 39.5669 0 41.5C0 43.4331 1.56702 45 3.5 45ZM17.5 45C19.433 45 21 43.4331 21 41.5C21 39.5669 19.433 38 17.5 38C15.567 38 14 39.5669 14 41.5C14 43.4331 15.567 45 17.5 45ZM35 41.5C35 43.4331 33.433 45 31.5 45C29.567 45 28 43.4331 28 41.5C28 39.5669 29.567 38 31.5 38C33.433 38 35 39.5669 35 41.5ZM45.5 45C47.433 45 49 43.4331 49 41.5C49 39.5669 47.433 38 45.5 38C43.567 38 42 39.5669 42 41.5C42 43.4331 43.567 45 45.5 45ZM7 60.5C7 62.4331 5.43298 64 3.5 64C1.56702 64 0 62.4331 0 60.5C0 58.5669 1.56702 57 3.5 57C5.43298 57 7 58.5669 7 60.5ZM21 60.5C21 62.4331 19.433 64 17.5 64C15.567 64 14 62.4331 14 60.5C14 58.5669 15.567 57 17.5 57C19.433 57 21 58.5669 21 60.5ZM31.5 64C33.433 64 35 62.4331 35 60.5C35 58.5669 33.433 57 31.5 57C29.567 57 28 58.5669 28 60.5C28 62.4331 29.567 64 31.5 64ZM49 60.5C49 62.4331 47.433 64 45.5 64C43.567 64 42 62.4331 42 60.5C42 58.5669 43.567 57 45.5 57C47.433 57 49 58.5669 49 60.5ZM3.5 83C5.43298 83 7 81.4331 7 79.5C7 77.5669 5.43298 76 3.5 76C1.56702 76 0 77.5669 0 79.5C0 81.4331 1.56702 83 3.5 83ZM17.5 83C19.433 83 21 81.4331 21 79.5C21 77.5669 19.433 76 17.5 76C15.567 76 14 77.5669 14 79.5C14 81.4331 15.567 83 17.5 83ZM35 79.5C35 81.4331 33.433 83 31.5 83C29.567 83 28 81.4331 28 79.5C28 77.5669 29.567 76 31.5 76C33.433 76 35 77.5669 35 79.5ZM45.5 83C47.433 83 49 81.4331 49 79.5C49 77.5669 47.433 76 45.5 76C43.567 76 42 77.5669 42 79.5C42 81.4331 43.567 83 45.5 83ZM7 193.5C7 195.433 5.43298 197 3.5 197C1.56702 197 0 195.433 0 193.5C0 191.567 1.56702 190 3.5 190C5.43298 190 7 191.567 7 193.5ZM21 193.5C21 195.433 19.433 197 17.5 197C15.567 197 14 195.433 14 193.5C14 191.567 15.567 190 17.5 190C19.433 190 21 191.567 21 193.5ZM31.5 197C33.433 197 35 195.433 35 193.5C35 191.567 33.433 190 31.5 190C29.567 190 28 191.567 28 193.5C28 195.433 29.567 197 31.5 197ZM49 193.5C49 195.433 47.433 197 45.5 197C43.567 197 42 195.433 42 193.5C42 191.567 43.567 190 45.5 190C47.433 190 49 191.567 49 193.5ZM3.5 102C5.43298 102 7 100.433 7 98.5C7 96.5669 5.43298 95 3.5 95C1.56702 95 0 96.5669 0 98.5C0 100.433 1.56702 102 3.5 102ZM17.5 102C19.433 102 21 100.433 21 98.5C21 96.5669 19.433 95 17.5 95C15.567 95 14 96.5669 14 98.5C14 100.433 15.567 102 17.5 102ZM35 98.5C35 100.433 33.433 102 31.5 102C29.567 102 28 100.433 28 98.5C28 96.5669 29.567 95 31.5 95C33.433 95 35 96.5669 35 98.5ZM45.5 102C47.433 102 49 100.433 49 98.5C49 96.5669 47.433 95 45.5 95C43.567 95 42 96.5669 42 98.5C42 100.433 43.567 102 45.5 102ZM7 212.5C7 214.433 5.43298 216 3.5 216C1.56702 216 0 214.433 0 212.5C0 210.567 1.56702 209 3.5 209C5.43298 209 7 210.567 7 212.5ZM21 212.5C21 214.433 19.433 216 17.5 216C15.567 216 14 214.433 14 212.5C14 210.567 15.567 209 17.5 209C19.433 209 21 210.567 21 212.5ZM31.5 216C33.433 216 35 214.433 35 212.5C35 210.567 33.433 209 31.5 209C29.567 209 28 210.567 28 212.5C28 214.433 29.567 216 31.5 216ZM49 212.5C49 214.433 47.433 216 45.5 216C43.567 216 42 214.433 42 212.5C42 210.567 43.567 209 45.5 209C47.433 209 49 210.567 49 212.5ZM3.5 121C5.43298 121 7 119.433 7 117.5C7 115.567 5.43298 114 3.5 114C1.56702 114 0 115.567 0 117.5C0 119.433 1.56702 121 3.5 121ZM17.5 121C19.433 121 21 119.433 21 117.5C21 115.567 19.433 114 17.5 114C15.567 114 14 115.567 14 117.5C14 119.433 15.567 121 17.5 121ZM35 117.5C35 119.433 33.433 121 31.5 121C29.567 121 28 119.433 28 117.5C28 115.567 29.567 114 31.5 114C33.433 114 35 115.567 35 117.5ZM45.5 121C47.433 121 49 119.433 49 117.5C49 115.567 47.433 114 45.5 114C43.567 114 42 115.567 42 117.5C42 119.433 43.567 121 45.5 121ZM7 231.5C7 233.433 5.43298 235 3.5 235C1.56702 235 0 233.433 0 231.5C0 229.567 1.56702 228 3.5 228C5.43298 228 7 229.567 7 231.5ZM21 231.5C21 233.433 19.433 235 17.5 235C15.567 235 14 233.433 14 231.5C14 229.567 15.567 228 17.5 228C19.433 228 21 229.567 21 231.5ZM31.5 235C33.433 235 35 233.433 35 231.5C35 229.567 33.433 228 31.5 228C29.567 228 28 229.567 28 231.5C28 233.433 29.567 235 31.5 235ZM49 231.5C49 233.433 47.433 235 45.5 235C43.567 235 42 233.433 42 231.5C42 229.567 43.567 228 45.5 228C47.433 228 49 229.567 49 231.5ZM3.5 140C5.43298 140 7 138.433 7 136.5C7 134.567 5.43298 133 3.5 133C1.56702 133 0 134.567 0 136.5C0 138.433 1.56702 140 3.5 140ZM17.5 140C19.433 140 21 138.433 21 136.5C21 134.567 19.433 133 17.5 133C15.567 133 14 134.567 14 136.5C14 138.433 15.567 140 17.5 140ZM35 136.5C35 138.433 33.433 140 31.5 140C29.567 140 28 138.433 28 136.5C28 134.567 29.567 133 31.5 133C33.433 133 35 134.567 35 136.5ZM45.5 140C47.433 140 49 138.433 49 136.5C49 134.567 47.433 133 45.5 133C43.567 133 42 134.567 42 136.5C42 138.433 43.567 140 45.5 140ZM7 250.5C7 252.433 5.43298 254 3.5 254C1.56702 254 0 252.433 0 250.5C0 248.567 1.56702 247 3.5 247C5.43298 247 7 248.567 7 250.5ZM21 250.5C21 252.433 19.433 254 17.5 254C15.567 254 14 252.433 14 250.5C14 248.567 15.567 247 17.5 247C19.433 247 21 248.567 21 250.5ZM31.5 254C33.433 254 35 252.433 35 250.5C35 248.567 33.433 247 31.5 247C29.567 247 28 248.567 28 250.5C28 252.433 29.567 254 31.5 254ZM49 250.5C49 252.433 47.433 254 45.5 254C43.567 254 42 252.433 42 250.5C42 248.567 43.567 247 45.5 247C47.433 247 49 248.567 49 250.5ZM3.5 159C5.43298 159 7 157.433 7 155.5C7 153.567 5.43298 152 3.5 152C1.56702 152 0 153.567 0 155.5C0 157.433 1.56702 159 3.5 159ZM17.5 159C19.433 159 21 157.433 21 155.5C21 153.567 19.433 152 17.5 152C15.567 152 14 153.567 14 155.5C14 157.433 15.567 159 17.5 159ZM35 155.5C35 157.433 33.433 159 31.5 159C29.567 159 28 157.433 28 155.5C28 153.567 29.567 152 31.5 152C33.433 152 35 153.567 35 155.5ZM45.5 159C47.433 159 49 157.433 49 155.5C49 153.567 47.433 152 45.5 152C43.567 152 42 153.567 42 155.5C42 157.433 43.567 159 45.5 159ZM7 269.5C7 271.433 5.43298 273 3.5 273C1.56702 273 0 271.433 0 269.5C0 267.567 1.56702 266 3.5 266C5.43298 266 7 267.567 7 269.5ZM21 269.5C21 271.433 19.433 273 17.5 273C15.567 273 14 271.433 14 269.5C14 267.567 15.567 266 17.5 266C19.433 266 21 267.567 21 269.5ZM31.5 273C33.433 273 35 271.433 35 269.5C35 267.567 33.433 266 31.5 266C29.567 266 28 267.567 28 269.5C28 271.433 29.567 273 31.5 273ZM49 269.5C49 271.433 47.433 273 45.5 273C43.567 273 42 271.433 42 269.5C42 267.567 43.567 266 45.5 266C47.433 266 49 267.567 49 269.5ZM3.5 178C5.43298 178 7 176.433 7 174.5C7 172.567 5.43298 171 3.5 171C1.56702 171 0 172.567 0 174.5C0 176.433 1.56702 178 3.5 178ZM17.5 178C19.433 178 21 176.433 21 174.5C21 172.567 19.433 171 17.5 171C15.567 171 14 172.567 14 174.5C14 176.433 15.567 178 17.5 178ZM35 174.5C35 176.433 33.433 178 31.5 178C29.567 178 28 176.433 28 174.5C28 172.567 29.567 171 31.5 171C33.433 171 35 172.567 35 174.5ZM45.5 178C47.433 178 49 176.433 49 174.5C49 172.567 47.433 171 45.5 171C43.567 171 42 172.567 42 174.5C42 176.433 43.567 178 45.5 178ZM7 288.5C7 290.433 5.43298 292 3.5 292C1.56702 292 0 290.433 0 288.5C0 286.567 1.56702 285 3.5 285C5.43298 285 7 286.567 7 288.5ZM21 288.5C21 290.433 19.433 292 17.5 292C15.567 292 14 290.433 14 288.5C14 286.567 15.567 285 17.5 285C19.433 285 21 286.567 21 288.5ZM31.5 292C33.433 292 35 290.433 35 288.5C35 286.567 33.433 285 31.5 285C29.567 285 28 286.567 28 288.5C28 290.433 29.567 292 31.5 292ZM49 288.5C49 290.433 47.433 292 45.5 292C43.567 292 42 290.433 42 288.5C42 286.567 43.567 285 45.5 285C47.433 285 49 286.567 49 288.5Z" fill=currentColor />
                                    </svg>
                                </span>
                            </div>
                            <div class=it-about-shape-2>
                                <span>
                                    <svg width=49 height=292 viewBox="0 0 49 292" fill=none xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule=evenodd clip-rule=evenodd d="M7 3.5C7 5.43311 5.43298 7 3.5 7C1.56702 7 0 5.43311 0 3.5C0 1.56689 1.56702 0 3.5 0C5.43298 0 7 1.56689 7 3.5ZM21 3.5C21 5.43311 19.433 7 17.5 7C15.567 7 14 5.43311 14 3.5C14 1.56689 15.567 0 17.5 0C19.433 0 21 1.56689 21 3.5ZM31.5 7C33.433 7 35 5.43311 35 3.5C35 1.56689 33.433 0 31.5 0C29.567 0 28 1.56689 28 3.5C28 5.43311 29.567 7 31.5 7ZM49 3.5C49 5.43311 47.433 7 45.5 7C43.567 7 42 5.43311 42 3.5C42 1.56689 43.567 0 45.5 0C47.433 0 49 1.56689 49 3.5ZM7 22.5C7 24.4331 5.43298 26 3.5 26C1.56702 26 0 24.4331 0 22.5C0 20.5669 1.56702 19 3.5 19C5.43298 19 7 20.5669 7 22.5ZM21 22.5C21 24.4331 19.433 26 17.5 26C15.567 26 14 24.4331 14 22.5C14 20.5669 15.567 19 17.5 19C19.433 19 21 20.5669 21 22.5ZM31.5 26C33.433 26 35 24.4331 35 22.5C35 20.5669 33.433 19 31.5 19C29.567 19 28 20.5669 28 22.5C28 24.4331 29.567 26 31.5 26ZM49 22.5C49 24.4331 47.433 26 45.5 26C43.567 26 42 24.4331 42 22.5C42 20.5669 43.567 19 45.5 19C47.433 19 49 20.5669 49 22.5ZM3.5 45C5.43298 45 7 43.4331 7 41.5C7 39.5669 5.43298 38 3.5 38C1.56702 38 0 39.5669 0 41.5C0 43.4331 1.56702 45 3.5 45ZM17.5 45C19.433 45 21 43.4331 21 41.5C21 39.5669 19.433 38 17.5 38C15.567 38 14 39.5669 14 41.5C14 43.4331 15.567 45 17.5 45ZM35 41.5C35 43.4331 33.433 45 31.5 45C29.567 45 28 43.4331 28 41.5C28 39.5669 29.567 38 31.5 38C33.433 38 35 39.5669 35 41.5ZM45.5 45C47.433 45 49 43.4331 49 41.5C49 39.5669 47.433 38 45.5 38C43.567 38 42 39.5669 42 41.5C42 43.4331 43.567 45 45.5 45ZM7 60.5C7 62.4331 5.43298 64 3.5 64C1.56702 64 0 62.4331 0 60.5C0 58.5669 1.56702 57 3.5 57C5.43298 57 7 58.5669 7 60.5ZM21 60.5C21 62.4331 19.433 64 17.5 64C15.567 64 14 62.4331 14 60.5C14 58.5669 15.567 57 17.5 57C19.433 57 21 58.5669 21 60.5ZM31.5 64C33.433 64 35 62.4331 35 60.5C35 58.5669 33.433 57 31.5 57C29.567 57 28 58.5669 28 60.5C28 62.4331 29.567 64 31.5 64ZM49 60.5C49 62.4331 47.433 64 45.5 64C43.567 64 42 62.4331 42 60.5C42 58.5669 43.567 57 45.5 57C47.433 57 49 58.5669 49 60.5ZM3.5 83C5.43298 83 7 81.4331 7 79.5C7 77.5669 5.43298 76 3.5 76C1.56702 76 0 77.5669 0 79.5C0 81.4331 1.56702 83 3.5 83ZM17.5 83C19.433 83 21 81.4331 21 79.5C21 77.5669 19.433 76 17.5 76C15.567 76 14 77.5669 14 79.5C14 81.4331 15.567 83 17.5 83ZM35 79.5C35 81.4331 33.433 83 31.5 83C29.567 83 28 81.4331 28 79.5C28 77.5669 29.567 76 31.5 76C33.433 76 35 77.5669 35 79.5ZM45.5 83C47.433 83 49 81.4331 49 79.5C49 77.5669 47.433 76 45.5 76C43.567 76 42 77.5669 42 79.5C42 81.4331 43.567 83 45.5 83ZM7 193.5C7 195.433 5.43298 197 3.5 197C1.56702 197 0 195.433 0 193.5C0 191.567 1.56702 190 3.5 190C5.43298 190 7 191.567 7 193.5ZM21 193.5C21 195.433 19.433 197 17.5 197C15.567 197 14 195.433 14 193.5C14 191.567 15.567 190 17.5 190C19.433 190 21 191.567 21 193.5ZM31.5 197C33.433 197 35 195.433 35 193.5C35 191.567 33.433 190 31.5 190C29.567 190 28 191.567 28 193.5C28 195.433 29.567 197 31.5 197ZM49 193.5C49 195.433 47.433 197 45.5 197C43.567 197 42 195.433 42 193.5C42 191.567 43.567 190 45.5 190C47.433 190 49 191.567 49 193.5ZM3.5 102C5.43298 102 7 100.433 7 98.5C7 96.5669 5.43298 95 3.5 95C1.56702 95 0 96.5669 0 98.5C0 100.433 1.56702 102 3.5 102ZM17.5 102C19.433 102 21 100.433 21 98.5C21 96.5669 19.433 95 17.5 95C15.567 95 14 96.5669 14 98.5C14 100.433 15.567 102 17.5 102ZM35 98.5C35 100.433 33.433 102 31.5 102C29.567 102 28 100.433 28 98.5C28 96.5669 29.567 95 31.5 95C33.433 95 35 96.5669 35 98.5ZM45.5 102C47.433 102 49 100.433 49 98.5C49 96.5669 47.433 95 45.5 95C43.567 95 42 96.5669 42 98.5C42 100.433 43.567 102 45.5 102ZM7 212.5C7 214.433 5.43298 216 3.5 216C1.56702 216 0 214.433 0 212.5C0 210.567 1.56702 209 3.5 209C5.43298 209 7 210.567 7 212.5ZM21 212.5C21 214.433 19.433 216 17.5 216C15.567 216 14 214.433 14 212.5C14 210.567 15.567 209 17.5 209C19.433 209 21 210.567 21 212.5ZM31.5 216C33.433 216 35 214.433 35 212.5C35 210.567 33.433 209 31.5 209C29.567 209 28 210.567 28 212.5C28 214.433 29.567 216 31.5 216ZM49 212.5C49 214.433 47.433 216 45.5 216C43.567 216 42 214.433 42 212.5C42 210.567 43.567 209 45.5 209C47.433 209 49 210.567 49 212.5ZM3.5 121C5.43298 121 7 119.433 7 117.5C7 115.567 5.43298 114 3.5 114C1.56702 114 0 115.567 0 117.5C0 119.433 1.56702 121 3.5 121ZM17.5 121C19.433 121 21 119.433 21 117.5C21 115.567 19.433 114 17.5 114C15.567 114 14 115.567 14 117.5C14 119.433 15.567 121 17.5 121ZM35 117.5C35 119.433 33.433 121 31.5 121C29.567 121 28 119.433 28 117.5C28 115.567 29.567 114 31.5 114C33.433 114 35 115.567 35 117.5ZM45.5 121C47.433 121 49 119.433 49 117.5C49 115.567 47.433 114 45.5 114C43.567 114 42 115.567 42 117.5C42 119.433 43.567 121 45.5 121ZM7 231.5C7 233.433 5.43298 235 3.5 235C1.56702 235 0 233.433 0 231.5C0 229.567 1.56702 228 3.5 228C5.43298 228 7 229.567 7 231.5ZM21 231.5C21 233.433 19.433 235 17.5 235C15.567 235 14 233.433 14 231.5C14 229.567 15.567 228 17.5 228C19.433 228 21 229.567 21 231.5ZM31.5 235C33.433 235 35 233.433 35 231.5C35 229.567 33.433 228 31.5 228C29.567 228 28 229.567 28 231.5C28 233.433 29.567 235 31.5 235ZM49 231.5C49 233.433 47.433 235 45.5 235C43.567 235 42 233.433 42 231.5C42 229.567 43.567 228 45.5 228C47.433 228 49 229.567 49 231.5ZM3.5 140C5.43298 140 7 138.433 7 136.5C7 134.567 5.43298 133 3.5 133C1.56702 133 0 134.567 0 136.5C0 138.433 1.56702 140 3.5 140ZM17.5 140C19.433 140 21 138.433 21 136.5C21 134.567 19.433 133 17.5 133C15.567 133 14 134.567 14 136.5C14 138.433 15.567 140 17.5 140ZM35 136.5C35 138.433 33.433 140 31.5 140C29.567 140 28 138.433 28 136.5C28 134.567 29.567 133 31.5 133C33.433 133 35 134.567 35 136.5ZM45.5 140C47.433 140 49 138.433 49 136.5C49 134.567 47.433 133 45.5 133C43.567 133 42 134.567 42 136.5C42 138.433 43.567 140 45.5 140ZM7 250.5C7 252.433 5.43298 254 3.5 254C1.56702 254 0 252.433 0 250.5C0 248.567 1.56702 247 3.5 247C5.43298 247 7 248.567 7 250.5ZM21 250.5C21 252.433 19.433 254 17.5 254C15.567 254 14 252.433 14 250.5C14 248.567 15.567 247 17.5 247C19.433 247 21 248.567 21 250.5ZM31.5 254C33.433 254 35 252.433 35 250.5C35 248.567 33.433 247 31.5 247C29.567 247 28 248.567 28 250.5C28 252.433 29.567 254 31.5 254ZM49 250.5C49 252.433 47.433 254 45.5 254C43.567 254 42 252.433 42 250.5C42 248.567 43.567 247 45.5 247C47.433 247 49 248.567 49 250.5ZM3.5 159C5.43298 159 7 157.433 7 155.5C7 153.567 5.43298 152 3.5 152C1.56702 152 0 153.567 0 155.5C0 157.433 1.56702 159 3.5 159ZM17.5 159C19.433 159 21 157.433 21 155.5C21 153.567 19.433 152 17.5 152C15.567 152 14 153.567 14 155.5C14 157.433 15.567 159 17.5 159ZM35 155.5C35 157.433 33.433 159 31.5 159C29.567 159 28 157.433 28 155.5C28 153.567 29.567 152 31.5 152C33.433 152 35 153.567 35 155.5ZM45.5 159C47.433 159 49 157.433 49 155.5C49 153.567 47.433 152 45.5 152C43.567 152 42 153.567 42 155.5C42 157.433 43.567 159 45.5 159ZM7 269.5C7 271.433 5.43298 273 3.5 273C1.56702 273 0 271.433 0 269.5C0 267.567 1.56702 266 3.5 266C5.43298 266 7 267.567 7 269.5ZM21 269.5C21 271.433 19.433 273 17.5 273C15.567 273 14 271.433 14 269.5C14 267.567 15.567 266 17.5 266C19.433 266 21 267.567 21 269.5ZM31.5 273C33.433 273 35 271.433 35 269.5C35 267.567 33.433 266 31.5 266C29.567 266 28 267.567 28 269.5C28 271.433 29.567 273 31.5 273ZM49 269.5C49 271.433 47.433 273 45.5 273C43.567 273 42 271.433 42 269.5C42 267.567 43.567 266 45.5 266C47.433 266 49 267.567 49 269.5ZM3.5 178C5.43298 178 7 176.433 7 174.5C7 172.567 5.43298 171 3.5 171C1.56702 171 0 172.567 0 174.5C0 176.433 1.56702 178 3.5 178ZM17.5 178C19.433 178 21 176.433 21 174.5C21 172.567 19.433 171 17.5 171C15.567 171 14 172.567 14 174.5C14 176.433 15.567 178 17.5 178ZM35 174.5C35 176.433 33.433 178 31.5 178C29.567 178 28 176.433 28 174.5C28 172.567 29.567 171 31.5 171C33.433 171 35 172.567 35 174.5ZM45.5 178C47.433 178 49 176.433 49 174.5C49 172.567 47.433 171 45.5 171C43.567 171 42 172.567 42 174.5C42 176.433 43.567 178 45.5 178ZM7 288.5C7 290.433 5.43298 292 3.5 292C1.56702 292 0 290.433 0 288.5C0 286.567 1.56702 285 3.5 285C5.43298 285 7 286.567 7 288.5ZM21 288.5C21 290.433 19.433 292 17.5 292C15.567 292 14 290.433 14 288.5C14 286.567 15.567 285 17.5 285C19.433 285 21 286.567 21 288.5ZM31.5 292C33.433 292 35 290.433 35 288.5C35 286.567 33.433 285 31.5 285C29.567 285 28 286.567 28 288.5C28 290.433 29.567 292 31.5 292ZM49 288.5C49 290.433 47.433 292 45.5 292C43.567 292 42 290.433 42 288.5C42 286.567 43.567 285 45.5 285C47.433 285 49 286.567 49 288.5Z" fill=currentColor />
                                    </svg>
                                </span>
                            </div>
                            <div class=it-about-shape-4>
                                <div class="it-about-video-thumb p-relative">
                                    <img src="assets/img/about/about-4-1-sm.jpg" alt="">
                                    <div class=it-about-video-icon>
                                        <a class=popup-video href="https://www.youtube.com/watch?v=hIrx72QFbbM">
                                            <i class="fa-sharp fa-solid fa-play"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=it-about-thumb>
                            <img src="{{ asset('frontend/assets/img/about/about-4-1.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="it-about-content-wrapp wow itfadeRight" data-wow-duration=.9s data-wow-delay=.9s>
                        <div class="it-about-title-box mb-20">
                            <span class=it-subtitle>Over Ons</span>
                            <h3 class=it-section-title>
                                EPC, Elektrische keuring en Asbestattest
                            </h3>
                        </div>
                        <div class="it-about-dsc mb-35">
                            <p>Wij bieden professionele diensten aan voor EPC-certificering, elektrische keuringen en asbestattesten. Onze experts zorgen voor nauwkeurige inspecties en officiële documentatie.</p>
                        </div>
                        <div class="it-about-2-item mb-15">
                            <div class=row>
                                <div class="col-xl-6 col-md-6">
                                    <div class=it-about-item-text>
                                        <h3 class="it-section-title-sm mb-5">EPC CERTIFICAAT:</h3>
                                        <div class=it-about-2-dsc>
                                            <p>Energieprestatiecertificaat voor uw woning of gebouw, verplicht bij verkoop of verhuur van vastgoed.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6">
                                    <div class=it-about-item-text>
                                        <h3 class="it-section-title-sm mb-5">ELEKTRISCHE KEURING:</h3>
                                        <div class=it-about-item-2-dsc>
                                            <p>Grondige inspectie van elektrische installaties voor veiligheid en conformiteit met regelgeving.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class=col-xl-12>
                                    <div class="it-about-list mt-15 mb-20">
                                        <ul>
                                            <li><i class=flaticon-check></i>Asbestattesten voor gebouwen van voor 2001</li>
                                            <li><i class=flaticon-check></i>Snelle en professionele service</li>
                                            <li><i class=flaticon-check></i>Erkende keuringsinstantie</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="it-about-info d-flex align-items-center">
                            <div class="it-about-btn mr-25">
                                <a href="/contact" class="it-btn-green yellow-bg">Meer Informatie</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
  <!--  <div class="it-price-area grey-bg pt-115 pb-90">
        <div class=container>
            <div class="row justify-content-center">
                <div class=col-xl-6>
                    <div class="it-price-title-box text-center mb-70">
                        <span class=it-subtitle>PRICING PLANS</span>
                        <h3 class=it-section-title>Effective & Flexible Pricing</h3>
                    </div>
                </div>
            </div>
            <div class=row>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class=it-price-item>
                        <div class="it-price-top text-center">
                            <div class="it-price-content mb-30">
                                <h3 class=it-price-title>SOLAR ENERGY</h3>
                                <b>$100.99 <span>Per month</span></b>
                            </div>
                            <div class="it-price-button mb-35">
                                <a href="about.html#" class=it-btn-green>Purchase now</a>
                            </div>
                        </div>
                        <div class=it-price-list>
                            <div class="it-price-categories mb-25">
                                <h3 class=it-price-categories-title>Enjoy for solar energy</h3>
                            </div>
                            <ul>
                                <li><i class="fa-regular fa-circle-check"></i> Built using n-type mono</li>
                                <li><i class="fa-regular fa-circle-check"></i> Crystalline cell materials</li>
                                <li><i class="fa-regular fa-circle-check"></i> Reliability and performance</li>
                                <li><i class="fa-regular fa-circle-check"></i> Crystalline cell materials</li>
                                <li><i class="fa-regular fa-circle-check"></i> Reliability and performance</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="it-price-item active">
                        <div class="it-price-top text-center">
                            <div class="it-price-content mb-30">
                                <h3 class=it-price-title>HYDROELECTRIC ENERGY</h3>
                                <b>$100.99 <span>Per month</span></b>
                            </div>
                            <div class="it-price-button mb-35">
                                <a href="about.html#" class="it-btn-green yellow-bg white-bg">Purchase now</a>
                            </div>
                        </div>
                        <div class=it-price-list>
                            <div class="it-price-categories mb-25">
                                <h3 class=it-price-categories-title>Enjoy for solar energy</h3>
                            </div>
                            <ul>
                                <li><i class="fa-regular fa-circle-check"></i> Built using n-type mono</li>
                                <li><i class="fa-regular fa-circle-check"></i> Crystalline cell materials</li>
                                <li><i class="fa-regular fa-circle-check"></i> Reliability and performance</li>
                                <li><i class="fa-regular fa-circle-check"></i> Crystalline cell materials</li>
                                <li><i class="fa-regular fa-circle-check"></i> Reliability and performance</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class=it-price-item>
                        <div class="it-price-top text-center">
                            <div class="it-price-content mb-30">
                                <h3 class=it-price-title>SOLAR ENERGY</h3>
                                <b>$100.99 <span>Per month</span></b>
                            </div>
                            <div class="it-price-button mb-35">
                                <a href="about.html#" class=it-btn-green>Purchase now</a>
                            </div>
                        </div>
                        <div class=it-price-list>
                            <div class="it-price-categories mb-25">
                                <h3 class=it-price-categories-title>Enjoy for solar energy</h3>
                            </div>
                            <ul>
                                <li><i class="fa-regular fa-circle-check"></i> Built using n-type mono</li>
                                <li><i class="fa-regular fa-circle-check"></i> Crystalline cell materials</li>
                                <li><i class="fa-regular fa-circle-check"></i> Reliability and performance</li>
                                <li><i class="fa-regular fa-circle-check"></i> Crystalline cell materials</li>
                                <li><i class="fa-regular fa-circle-check"></i> Reliability and performance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    <div class="it-work-area pt-115 pb-90">
        <div class=container>
            <div class="row justify-content-center">
                <div class=col-xl-6>
                    <div class="it-work-title-box text-center mb-70">
                        <span class="it-subtitle mb-30">ONS WERKPROCES</span>
                        <h3 class=it-section-title>Professionele Keuringen voor uw Vastgoed</h3>
                    </div>
                </div>
            </div>
            <div class="it-work-wrapper p-relative">
                <div class=row>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">
                        <div class="it-work-item text-center">
                            <div class=it-wor-icon-box>
                                <span class=it-work-main-number>01</span>
                                <div class=it-work-sub-icon>
                                    <span class=download-icon>
                                        <svg width=8 height=39 viewBox="0 0 8 39" fill=none xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.64645 38.3536C3.84171 38.5488 4.15829 38.5488 4.35355 38.3536L7.53553 35.1716C7.7308 34.9763 7.7308 34.6597 7.53553 34.4645C7.34027 34.2692 7.02369 34.2692 6.82843 34.4645L4 37.2929L1.17157 34.4645C0.976311 34.2692 0.659728 34.2692 0.464466 34.4645C0.269204 34.6597 0.269204 34.9763 0.464466 35.1716L3.64645 38.3536ZM3.5 0V0.95H4.5V0H3.5ZM3.5 2.85V4.75H4.5V2.85H3.5ZM3.5 6.65V8.55H4.5V6.65H3.5ZM3.5 10.45V12.35H4.5V10.45H3.5ZM3.5 14.25V16.15H4.5V14.25H3.5ZM3.5 18.05V19.95H4.5V18.05H3.5ZM3.5 21.85V23.75H4.5V21.85H3.5ZM3.5 25.65V27.55H4.5V25.65H3.5ZM3.5 29.45V31.35H4.5V29.45H3.5ZM3.5 33.25V35.15H4.5V33.25H3.5ZM3.5 37.05V38H4.5V37.05H3.5Z" fill=currentColor></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class=it-work-content>
                                <h3 class=it-section-title-sm>EPC Certificaat</h3>
                                <p>Het Energieprestatiecertificaat (EPC) geeft inzicht in de energiezuinigheid van uw woning. Verplicht bij verkoop of verhuur van woningen.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">
                        <div class="it-work-item text-center">
                            <div class=it-wor-icon-box>
                                <span class=it-work-main-number>02</span>
                                <div class=it-work-sub-icon>
                                    <span class=download-icon>
                                        <svg width=8 height=39 viewBox="0 0 8 39" fill=none xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.64645 38.3536C3.84171 38.5488 4.15829 38.5488 4.35355 38.3536L7.53553 35.1716C7.7308 34.9763 7.7308 34.6597 7.53553 34.4645C7.34027 34.2692 7.02369 34.2692 6.82843 34.4645L4 37.2929L1.17157 34.4645C0.976311 34.2692 0.659728 34.2692 0.464466 34.4645C0.269204 34.6597 0.269204 34.9763 0.464466 35.1716L3.64645 38.3536ZM3.5 0V0.95H4.5V0H3.5ZM3.5 2.85V4.75H4.5V2.85H3.5ZM3.5 6.65V8.55H4.5V6.65H3.5ZM3.5 10.45V12.35H4.5V10.45H3.5ZM3.5 14.25V16.15H4.5V14.25H3.5ZM3.5 18.05V19.95H4.5V18.05H3.5ZM3.5 21.85V23.75H4.5V21.85H3.5ZM3.5 25.65V27.55H4.5V25.65H3.5ZM3.5 29.45V31.35H4.5V29.45H3.5ZM3.5 33.25V35.15H4.5V33.25H3.5ZM3.5 37.05V38H4.5V37.05H3.5Z" fill=currentColor></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class=it-work-content>
                                <h3 class=it-section-title-sm>Elektrische Keuring</h3>
                                <p>Professionele controle van uw elektrische installatie voor conformiteit en veiligheid. Verplicht bij verkoop of verhuur van woningen.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-30">
                        <div class="it-work-item text-center">
                            <div class=it-wor-icon-box>
                                <span class=it-work-main-number>03</span>
                                <div class=it-work-sub-icon>
                                    <span class=download-icon>
                                        <svg width=8 height=39 viewBox="0 0 8 39" fill=none xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.64645 38.3536C3.84171 38.5488 4.15829 38.5488 4.35355 38.3536L7.53553 35.1716C7.7308 34.9763 7.7308 34.6597 7.53553 34.4645C7.34027 34.2692 7.02369 34.2692 6.82843 34.4645L4 37.2929L1.17157 34.4645C0.976311 34.2692 0.659728 34.2692 0.464466 34.4645C0.269204 34.6597 0.269204 34.9763 0.464466 35.1716L3.64645 38.3536ZM3.5 0V0.95H4.5V0H3.5ZM3.5 2.85V4.75H4.5V2.85H3.5ZM3.5 6.65V8.55H4.5V6.65H3.5ZM3.5 10.45V12.35H4.5V10.45H3.5ZM3.5 14.25V16.15H4.5V14.25H3.5ZM3.5 18.05V19.95H4.5V18.05H3.5ZM3.5 21.85V23.75H4.5V21.85H3.5ZM3.5 25.65V27.55H4.5V25.65H3.5ZM3.5 29.45V31.35H4.5V29.45H3.5ZM3.5 33.25V35.15H4.5V33.25H3.5ZM3.5 37.05V38H4.5V37.05H3.5Z" fill=currentColor></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class=it-work-content>
                                <h3 class=it-section-title-sm>Asbestattest</h3>
                                <p>Professionele asbestinventarisatie voor uw gebouw. Verplicht bij verkoop van woningen gebouwd voor 2001.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="it-testimonial-2-area theme-bg pt-115 pb-120" data-background="assets/img/testimonial/testimonial-2-bg.png">
        <div class=container>
            <div class="row align-items-end">
                <div class="col-xl-6 col-lg-6">
                    <div class=it-testimonial-2-title-box>
                        <span class="it-subtitle subtitle-yellow">Klantbeoordelingen</span>
                        <h3 class="it-section-title text-white mb-20">
                            Wat onze klanten zeggen over onze <span>keuringsdiensten</span>
                        </h3>
                        <p>Ontdek waarom klanten ons vertrouwen voor hun EPC certificaat, elektrische keuring en asbestattest. Professionele service en expertise die het verschil maken.</p>
                    </div>
                    <div class=row>
                        <div class="col-xl-6 col-md-6 mb-20">
                            <div class="it-testimonial-2-review-box d-flex align-items-center">
                                <div class=it-testimonial-2-review-icon>
                                    <span>
                                        <i class=flaticon-like></i>
                                    </span>
                                </div>
                                <div class=it-testimonial-2-review-content>
                                    <span>Gemiddelde beoordeling 4.9</span>
                                    <div class=it-testimonial-2-ratting>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-20">
                            <div class="it-testimonial-2-review-box d-flex align-items-center">
                                <div class=it-testimonial-2-review-icon>
                                    <span>
                                        <img src="assets/img/testimonial/google-icon.png" alt="Google Reviews">
                                    </span>
                                </div>
                                <div class=it-testimonial-2-review-content>
                                    <span>Google Beoordelingen</span>
                                    <div class=it-testimonial-2-ratting>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                        <span><i class="fa-sharp fa-solid fa-star"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class=it-testimonial-2-wrapper>
                        <div class="swiper-container it-testimonial-2-active p-relative">
                            <div class=swiper-wrapper>
                                <div class=swiper-slide>
                                    <div class="it-testimonial-2-item p-relative">
                                        <div class=it-testimonial-2-item-shape>
                                            <span><i class="fa-solid fa-quote-right"></i></span>
                                        </div>
                                        <p>"Zeer professionele service voor ons EPC certificaat. De keuring werd grondig uitgevoerd en we kregen duidelijke uitleg over de resultaten. Een aanrader voor iedereen die een energieprestatiecertificaat nodig heeft."</p>
                                        <div class="it-testimonial-2-author d-flex align-items-center justify-content-between">
                                            <div class="it-testimonial-2-avater-box d-flex align-items-center">
                                                <div class=it-testimonial-2-avater>
                                                    <img src="assets/img/testimonial/avater-2-1.png" alt="Klant Testimonial">
                                                </div>
                                                <div class=it-testimonial-2-avater-info>
                                                    <h3 class=it-testimonial-2-avater-title>Sophie Janssens</h3>
                                                    <span>Huiseigenaar</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=swiper-slide>
                                    <div class="it-testimonial-2-item p-relative">
                                        <div class=it-testimonial-2-item-shape>
                                            <span><i class="fa-solid fa-quote-right"></i></span>
                                        </div>
                                        <p>"De elektrische keuring werd vakkundig uitgevoerd. Het rapport was gedetailleerd en de aanbevelingen waren zeer nuttig. De service was snel en efficiënt, precies wat we nodig hadden voor de verkoop van ons huis."</p>
                                        <div class="it-testimonial-2-author d-flex align-items-center justify-content-between">
                                            <div class="it-testimonial-2-avater-box d-flex align-items-center">
                                                <div class=it-testimonial-2-avater>
                                                    <img src="assets/img/testimonial/avater-2-2.png" alt="Klant Review">
                                                </div>
                                                <div class=it-testimonial-2-avater-info>
                                                    <h3 class=it-testimonial-2-avater-title>Marc Peeters</h3>
                                                    <span>Vastgoedeigenaar</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=swiper-slide>
                                    <div class="it-testimonial-2-item p-relative">
                                        <div class=it-testimonial-2-item-shape>
                                            <span><i class="fa-solid fa-quote-right"></i></span>
                                        </div>
                                        <p>"Uitstekende ervaring met de asbestinventarisatie. Het team was zeer kundig en nam de tijd om alles grondig te controleren. Het rapport was helder en compleet. Zeer tevreden met de geleverde dienst."</p>
                                        <div class="it-testimonial-2-author d-flex align-items-center justify-content-between">
                                            <div class="it-testimonial-2-avater-box d-flex align-items-center">
                                                <div class=it-testimonial-2-avater>
                                                    <img src="assets/img/testimonial/avater-2-3.png" alt="Klant Feedback">
                                                </div>
                                                <div class=it-testimonial-2-avater-info>
                                                    <h3 class=it-testimonial-2-avater-title>Linda Vermeulen</h3>
                                                    <span>Projectontwikkelaar</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
