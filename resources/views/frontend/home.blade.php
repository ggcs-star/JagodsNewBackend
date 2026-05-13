@extends('frontend.layouts.app')
@push('meta')
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:type" content="FoodBank" />
    <meta property="og:title" content="{{ setting('banner_title') }}">
    <meta property="og:description" content="Explore top-rated attractions, activities and more">
    <meta property="og:image" content="{{ asset('images/' . setting('site_logo')) }}">
@endpush

@section('main-content')

    <!--========  FEATURE PART START ========-->
  @include('frontend.partials.hero')

    <!--======== FEATURE PART END =======-->


    <!--========  APP PART START ======-->
    @if (setting('android_app_link') || setting('ios_app_link'))
        <section class="app section-gap-90">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="app-content">
                            <h2>{{ __('Download the app') }} </h2>
                            <p> {{ __('Click, sit back, and enjoy.') }}</p>

                            <nav>
                                @if (setting('android_app_link'))
                                    <a href="{{ setting('android_app_link') }}" target="_blank">
                                        <img src="{{ asset('frontend/images/play.png') }}" alt="play">
                                    </a>
                                @endif
                                @if (setting('ios_app_link'))
                                    <a href="{{ setting('ios_app_link') }}" target="_blank">
                                        <img src="{{ asset('frontend/images/app.png') }}" alt="">
                                    </a>
                                @endif
                            </nav>

                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="app-image">
                            <img src="{{ asset('images/' . setting('app_mockup')) }}" alt="mockup">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!--========== APP PART END =======-->


    <!--========= Cusines PART START ========-->
    @if (!blank($bestSellingCuisines))
        <section class="category section-gap-66 mt-5">
            <div class="container">
                <h2 class="section-title">{{ __('frontend.popular_cuisines') }} </h2>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5">

                    @foreach ($bestSellingCuisines as $key => $bestSellingCusine)
                        <div class="col-6 col-md-4 col-lg-5-custom mb-4">
                            <a href="{{ route('search', ['cuisines' => [$bestSellingCusine->slug], 'expedition' => 'all']) }}"
                                class="category-card text-center">
                        
                                <div class="category-img-wrapper">
                                    <img src="{{ $bestSellingCusine->web_image }}" alt="category">
                                </div>
                        
                                <h5 class="mt-3">
                                    {{ Str::of(strip_tags($bestSellingCusine->name))->limit(18) }}
                                </h5>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif
    <!--========== Cusines PART END =========-->


    <!--=========  RESTAURANT PART START ========-->
    @if (!blank($bestSellingRestaurants))
        <section class="restaurant section-gap-66">
            <div class="container">
                <h2 class="section-title">{{ __('frontend.most_visited_restaurants') }}</h2>
                <div class="row">
                    @foreach ($bestSellingRestaurants as $restaurant)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <a href="{{ route('restaurant.show', [$restaurant]) }}" class="restaurant-card">
                                <figure class="figure position-relative">
                                    <img class="bestSellingRestaurantsImage" src="{{ $restaurant->image }}"
                                        alt="restaurant">
                                    @if (array_key_exists($restaurant->id, $vouchers))
                                        <span class="coupon-label"> {{ __('frontend.voucher') }}
                                            {{ $vouchers[$restaurant->id] }} </span>
                                    @endif
                                </figure>


                                <div class="content">
                                   <h4 style="display:flex; align-items:center; gap:6px;">
                                        {{ Str::of(strip_tags($restaurant->name))->limit(18) }}
                                    
                                        @if(strtolower($restaurant->restroType) == 'veg')
                                            <span class="food-type veg">
                                                <span class="dot"></span>
                                            </span>
                                        @elseif(strtolower($restaurant->restroType) == 'non-veg')
                                            <span class="food-type nonveg">
                                                <span class="dot"></span>
                                            </span>
                                        @endif
                                    </h4>
                                    
                                    <div class="ratings">

                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $restaurant->avgRating($restaurant->id)['avgRating'])
                                                <svg class="active" width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M5.97191 1.37497C6.4383 0.599986 7.56186 0.599985 8.02825 1.37497L9.15178 3.24189C9.31933 3.5203 9.59263 3.71886 9.90919 3.79218L12.0319 4.28381C12.9131 4.48789 13.2603 5.55646 12.6674 6.23951L11.239 7.88495C11.026 8.13034 10.9216 8.45162 10.9497 8.77535L11.1381 10.9461C11.2163 11.8472 10.3073 12.5076 9.47449 12.1548L7.46819 11.3048C7.16899 11.1781 6.83117 11.1781 6.53197 11.3048L4.52568 12.1548C3.69283 12.5076 2.78386 11.8472 2.86206 10.9461L3.05045 8.77535C3.07855 8.45162 2.97416 8.13034 2.76115 7.88495L1.33279 6.23951C0.739863 5.55646 1.08706 4.48789 1.96824 4.28381L4.09097 3.79218C4.40753 3.71886 4.68083 3.5203 4.84838 3.24189L5.97191 1.37497Z"
                                                        stroke-width="1.5" />
                                                </svg>
                                            @else
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M5.97191 1.37497C6.4383 0.599986 7.56186 0.599985 8.02825 1.37497L9.15178 3.24189C9.31933 3.5203 9.59263 3.71886 9.90919 3.79218L12.0319 4.28381C12.9131 4.48789 13.2603 5.55646 12.6674 6.23951L11.239 7.88495C11.026 8.13034 10.9216 8.45162 10.9497 8.77535L11.1381 10.9461C11.2163 11.8472 10.3073 12.5076 9.47449 12.1548L7.46819 11.3048C7.16899 11.1781 6.83117 11.1781 6.53197 11.3048L4.52568 12.1548C3.69283 12.5076 2.78386 11.8472 2.86206 10.9461L3.05045 8.77535C3.07855 8.45162 2.97416 8.13034 2.76115 7.88495L1.33279 6.23951C0.739863 5.55646 1.08706 4.48789 1.96824 4.28381L4.09097 3.79218C4.40753 3.71886 4.68083 3.5203 4.84838 3.24189L5.97191 1.37497Z"
                                                        stroke-width="1.5" />
                                                </svg>
                                            @endif
                                        @endfor
                                        <span>({{ $restaurant->avgRating($restaurant->id)['countUser'] }}) </span>

                                    </div>
                                    <div class="location">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.99992 8.95346C9.14867 8.95346 10.0799 8.02221 10.0799 6.87346C10.0799 5.7247 9.14867 4.79346 7.99992 4.79346C6.85117 4.79346 5.91992 5.7247 5.91992 6.87346C5.91992 8.02221 6.85117 8.95346 7.99992 8.95346Z"
                                                stroke="#1F1F39" stroke-width="1.5" />
                                            <path
                                                d="M2.4133 5.66016C3.72664 -0.113169 12.28 -0.106502 13.5866 5.66683C14.3533 9.0535 12.2466 11.9202 10.4 13.6935C9.05997 14.9868 6.93997 14.9868 5.5933 13.6935C3.7533 11.9202 1.64664 9.04683 2.4133 5.66016Z"
                                                stroke="#1F1F39" stroke-width="1.5" />
                                        </svg>
                                        <span>{{ Str::of(strip_tags($restaurant->address)) }}</span>
                                    </div>

                                    @if ($restaurant->opening_time < $current_data && $restaurant->closing_time > $current_data)
                                        <p class="on"> {{ __('frontend.open_now') }} </p>
                                    @else
                                        <p class="off">
                                            {{ __('frontend.close_now') }}
                                        </p>
                                    @endif

                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
         @include('frontend.partials.getApp')
           @include('frontend.partials.howToOrder')
    @endif
    <!--=========  RESTAURANT PART End ========-->

@endsection

@push('js')
    <script type="text/javascript" src="{{ asset('frontend/js/map-current.js') }}"></script>
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_map_api_key') }}&sensor=false&libraries=places&callback=initAutocomplete"></script>
@endpush

<style>
    .food-type {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid;
    border-radius: 2px;
    position: relative;
}

.food-type .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
}

.food-type.veg {
    border-color: green;
}

.food-type.veg .dot {
    background-color: green;
}

.food-type.nonveg {
    border-color: red;
}

.food-type.nonveg .dot {
    background-color: red;
}
</style>

