@extends('frontend.layouts.app')

@section('main-content')

    @if($device === 'ios')
        @push('meta')
            <meta name="apple-itunes-app"
                content="app-id={{ setting('ios_app_link') ? last(explode('/id', setting('ios_app_link'))) : '123456789' }}">
        @endpush
    @endif

    <style>

        .page-redirect-wrapper {
            display: flex;
            align-items: center;
            
        }

        .download-wrapper-mobile {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
            width: 100%;
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #ddd;
            border-top: 5px solid #ff6b6b;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .app-content nav a {
            transition: transform 0.2s ease-in-out;
            display: inline-block;
        }

        .app-content nav a:hover {
            transform: scale(1.05);
        }

        @media (max-width: 576px) {
            .page-redirect-wrapper {
                padding-top: 80px;
            }
        }
    </style>

    <div class="page-redirect-wrapper w-100">

        @if($device === 'desktop')
            @if (setting('android_app_link') || setting('ios_app_link'))
                <section class="app section-gap-90 my-auto w-100 bg-white">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-12 col-sm-6">
                                <div class="app-content py-4">
                                    <h2>{{ __('Download the App') }}</h2>
                                    <p>{{ __('Experience fast & easy online ordering on the Jagods app.') }}</p>

                                    <nav class="d-flex align-items-center gap-3 flex-wrap mt-4">
                                        @if (setting('android_app_link'))
                                            <a href="{{ setting('android_app_link') }}" target="_blank">
                                                <img src="{{ asset('frontend/images/googlePlay.png') }}" alt="Google Play">
                                            </a>
                                        @endif
                                        @if (setting('ios_app_link'))
                                            <a href="{{ setting('ios_app_link') }}" target="_blank">
                                                <img src="{{ asset('frontend/images/appStore.png') }}" alt="App Store">
                                            </a>
                                        @endif
                                    </nav>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="app-image text-center py-4">
                                    <img src="{{ asset('images/' . setting('app_mockup')) }}" alt="mockup" class="img-fluid"
                                        style="max-height: 450px; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endif

        @if($device === 'android' || $device === 'ios')
            <div class="container w-100">

                <div id="redirect-loader-zone" class="download-wrapper-mobile">
                    <div class="loader"></div>
                    <h2 class="mb-2">{{ __('Opening App...') }}</h2>
                    <p class="text-muted">
                        {{ __('Please wait while we connect to your application.') }}
                    </p>
                </div>

                <section id="fallback-ui-zone" class="app section-gap-90 w-100" style="display: none;">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">
                            <div class="app-content py-4">
                                <h2>{{ __('Download the app to order') }}</h2>
                                <p>{{ __('Click below to open or download the app directly from store.') }}</p>

                                <nav class="d-flex align-items-center justify-content-center gap-3 flex-wrap mt-4">
                                    @if($device === 'android' && setting('android_app_link'))
                                        <a id="android-direct-btn" href="#">
                                            <img src="{{ asset('frontend/images/googlePlay.png') }}" alt="Google Play">
                                        </a>
                                    @endif
                                    @if($device === 'ios' && setting('ios_app_link'))
                                        <a href="{{ setting('ios_app_link') }}" target="_blank">
                                            <img src="{{ asset('frontend/images/appStore.png') }}" alt="App Store">
                                        </a>
                                    @endif
                                </nav>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <div class="app-image py-4">
                                <img src="{{ asset('images/' . setting('app_mockup')) }}" alt="mockup" class="img-fluid"
                                    style="max-height: 380px; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endif
    </div>

    <script>
        const device = "{{ $device }}";

        @php
            $androidLink = setting('android_app_link');
            $packageName = 'com.yourpackage.name';
            if ($androidLink && str_contains($androidLink, 'id=')) {
                $parts = explode('id=', $androidLink);
                $packageName = explode('&', $parts[1])[0];
            }
        @endphp

        const config = {
            android: {
                customScheme: "myapp://home",
                intentUrl:
                    "intent://home#Intent;" +
                    "scheme=myapp;" +
                    "package={{ $packageName }};" +
                    "S.browser_fallback_url={{ setting('android_app_link') }};" +
                    "end",
                storeUrl: "{{ setting('android_app_link') }}"
            },
            ios: {
                appUrl: "myapp://home",
                storeUrl: "{{ setting('ios_app_link') }}"
            }
        };

        function showFallbackUI() {
            const loaderZone = document.getElementById('redirect-loader-zone');
            const fallbackZone = document.getElementById('fallback-ui-zone');
            const androidBtn = document.getElementById('android-direct-btn');

            if (loaderZone && fallbackZone) {
                loaderZone.style.setProperty('display', 'none', 'important');
                fallbackZone.style.setProperty('display', 'block', 'important');

                if (device === 'android' && androidBtn) {
                    androidBtn.setAttribute('href', config.android.intentUrl);
                }
            }
        }

     
        if (device === 'android' && config.android.storeUrl) {
            window.location.href = config.android.intentUrl;

            setTimeout(function () {
                showFallbackUI();
            }, 3000);
        }

        if (device === 'ios' && config.ios.storeUrl) {
           
            window.location.href = config.ios.appUrl;

         
            setTimeout(function () {
                window.location.href = config.ios.storeUrl;
            }, 2500);
        }
    </script>

@endsection