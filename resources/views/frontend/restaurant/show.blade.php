@extends('frontend.layouts.restaurent_app')
@push('meta')
    <meta property="og:url" content="{{ route('restaurant.show', [$restaurant->slug]) }}" />
    <meta property="og:type" content="{{ setting('site_name') }}">
    <meta property="og:title" content="{{ $restaurant->name }}">
    <meta property="og:description" content="{{ $restaurant->description }}">
    <meta property="og:image" content="{{ $restaurant->image }}">
@endpush

@push('body-data')
    data-bs-spy="scroll" data-bs-target="#scrollspy-menu" data-bs-smooth-scroll="true"
@endpush

@section('main-content')

    <!--====== RESTAURANT PART START =========-->            
  <!-- HERO SECTION -->
<section class="hero-section">

    <!-- Background -->
<div class="hero-bg"
     style="background:
     linear-gradient(
        to right,
        rgba(0,0,0,0.95) 0%,
        rgba(0,0,0,0.95) 40%,
        rgba(0,0,0,0.75) 55%,
        rgba(0,0,0,0.45) 70%,
        rgba(0,0,0,0.20) 85%,
        rgba(0,0,0,0.00) 100%
     ),
     url('{{ asset($restaurant->image) }}') center/cover no-repeat;">
</div>


<div class="hero-top-navigation">
    <div class="hero-tab-bar">
        <!-- Bulk Order Tab -->
        <button class="tab-pill active hero-contact-trigger"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#contact-modal"
                data-contact-type="bulk">
            <span class="tab-icon">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </span>
            Bulk Order
        </button>

        <!-- Party Tab -->
        <button class="tab-pill active hero-contact-trigger"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#contact1-modal"
                data-contact-type="party">
            <span class="tab-icon">
                <i class="fa-solid fa-cake-candles"></i>
            </span>
            Party
        </button>
    </div>
</div>    
    <div class="hero-container">
        
    

        <!-- LEFT IMAGE -->
        <div class="hero-card">
            <img src="{{ asset($restaurant->image) }}" alt="restaurant">
        </div>

        <!-- RIGHT CONTENT -->
        <div class="hero-content">

            <h1 class="hero-title">
                <!--@if ($restaurant->opening_time < $currenttime && $restaurant->closing_time > $currenttime)-->
                <!--    <span class="status-dot open"></span>-->
                <!--@else-->
                <!--    <span class="status-dot closed"></span>-->
                <!--@endif-->
     <div class="hero-image">
            <img src="{{ asset($restaurant->logo) }}" alt="restaurant">
        </div>
                {{ $restaurant->name }}
            </h1>
            
       

            <p class="rest-title">
                @foreach ($restaurant->cuisines ?? [] as $cuisine)
                    {{ $cuisine->name }}@if(!$loop->last), @endif
                @endforeach
            </p>

            <p class="hero-address">
                {{ \Illuminate\Support\Str::limit($restaurant->address, 80) }}
            </p>

            @if ($restaurant->table_status == \App\Enums\TableStatus::ENABLE)
                <div class="hero-buttons">

                    <button type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#booking-modal"
                            class="table-btn">
                        <i class="fa-solid fa-calendar-days"></i>
                        Table Order
                    </button>

                    <button type="button" data-bs-toggle="modal" data-bs-target="#shop-modal" style=" width:45px; height:45px; border-radius:50%; border:none; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.2); "> <svg width="18" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M8.00016 14.6668C11.6668 14.6668 14.6668 11.6668 14.6668 8.00016C14.6668 4.3335 11.6668 1.3335 8.00016 1.3335C4.3335 1.3335 1.3335 4.3335 1.3335 8.00016C1.3335 11.6668 4.3335 14.6668 8.00016 14.6668Z" stroke="#EE1D48" stroke-linecap="round" stroke-linejoin="round"/> <path d="M8 5.3335V8.66683" stroke="#EE1D48" stroke-linecap="round" stroke-linejoin="round"/> <path d="M7.99609 10.6665H8.00208" stroke="#EE1D48" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg> </button>

                </div>
            @endif

        </div>

    </div>
</section>




    <section class="restaurant">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-12 rest-col">
                    <div class="rest-content">

                        <!--<div class="rest-profile">-->
                        <!--    <div class="rest-info">-->
                        <!--        <h1 class="rest-name">-->
                        <!--            @if ($restaurant->opening_time < $currenttime && $restaurant->closing_time > $currenttime)-->
                        <!--                <span class="dot on me-1" title="Open Now"></span>-->
                        <!--            @else-->
                        <!--                <span class="dot off me-1" title="Close Now"></span>-->
                        <!--            @endif-->
                        <!--            {{ $restaurant->name }}-->
                        <!--        </h1>-->
                        <!--        <p class="rest-title">-->
                        <!--            @if (!blank($restaurant->cuisines))-->
                        <!--                @foreach ($restaurant->cuisines as $cuisine)-->
                        <!--                    {{ $cuisine->name }}-->
                        <!--                    @if (!$loop->last)-->
                        <!--                        <span>,</span>-->
                        <!--                    @endif-->
                        <!--                @endforeach-->
                        <!--            @endif-->
                        <!--        </p>-->
                        <!--        @if (!$average_rating == 0)-->
                        <!--            <div class="rest-review">-->
                        <!--                @for ($i = 0; $i < 5; $i++)-->
                        <!--                    @if ($i < $average_rating)-->
                        <!--                        <i class="fa-solid fa-star active"></i>-->
                        <!--                    @else-->
                        <!--                        <i class="fa-solid fa-star"></i>-->
                        <!--                    @endif-->
                        <!--                @endfor-->
                        <!--                <span>({{ $rating_user_count }} {{ __('frontend.reviews') }})</span>-->
                        <!--            </div>-->
                        <!--        @endif-->
                        <!--        <div class="rest-location">-->
                        <!--            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"-->
                        <!--                xmlns="http://www.w3.org/2000/svg">-->
                        <!--                <path-->
                        <!--                    d="M7.99992 8.95346C9.14867 8.95346 10.0799 8.02221 10.0799 6.87346C10.0799 5.7247 9.14867 4.79346 7.99992 4.79346C6.85117 4.79346 5.91992 5.7247 5.91992 6.87346C5.91992 8.02221 6.85117 8.95346 7.99992 8.95346Z"-->
                        <!--                    stroke="#1F1F39" stroke-width="1.5" />-->
                        <!--                <path-->
                        <!--                    d="M2.4133 5.66016C3.72664 -0.113169 12.28 -0.106502 13.5866 5.66683C14.3533 9.0535 12.2466 11.9202 10.4 13.6935C9.05997 14.9868 6.93997 14.9868 5.5933 13.6935C3.7533 11.9202 1.64664 9.04683 2.4133 5.66016Z"-->
                        <!--                    stroke="#1F1F39" stroke-width="1.5" />-->
                        <!--            </svg>-->
                        <!--            <span> {{ \Illuminate\Support\Str::limit($restaurant->address, 65) }} </span>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--    <div class="rest-btns">-->
                        <!--        @if ($restaurant->table_status == \App\Enums\TableStatus::ENABLE)-->
                        <!--            <button type="button" class="rest-book-btn" data-bs-toggle="modal"-->
                        <!--                data-bs-target="#booking-modal">-->
                        <!--                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"-->
                        <!--                    xmlns="http://www.w3.org/2000/svg">-->
                        <!--                    <path d="M5.3335 1.3335V3.3335" stroke="white" stroke-miterlimit="10"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path d="M10.6665 1.3335V3.3335" stroke="white" stroke-miterlimit="10"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path d="M2.3335 6.06006H13.6668" stroke="white" stroke-miterlimit="10"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path-->
                        <!--                        d="M14 5.66683V11.3335C14 13.3335 13 14.6668 10.6667 14.6668H5.33333C3 14.6668 2 13.3335 2 11.3335V5.66683C2 3.66683 3 2.3335 5.33333 2.3335H10.6667C13 2.3335 14 3.66683 14 5.66683Z"-->
                        <!--                        stroke="white" stroke-miterlimit="10" stroke-linecap="round"-->
                        <!--                        stroke-linejoin="round" />-->
                        <!--                    <path d="M10.463 9.13314H10.469" stroke="white" stroke-width="1.5"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path d="M10.463 11.1331H10.469" stroke="white" stroke-width="1.5"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path d="M7.99715 9.13314H8.00314" stroke="white" stroke-width="1.5"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path d="M7.99715 11.1331H8.00314" stroke="white" stroke-width="1.5"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path d="M5.52938 9.13314H5.53537" stroke="white" stroke-width="1.5"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                    <path d="M5.52938 11.1331H5.53537" stroke="white" stroke-width="1.5"-->
                        <!--                        stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                </svg>-->
                        <!--                <span>{{ __('frontend.table') }} </span>-->
                        <!--            </button>-->
                        <!--        @endif-->
                        <!--        <button type="button" class="rest-info-btn" data-bs-toggle="modal"-->
                        <!--            data-bs-target="#shop-modal">-->
                        <!--            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"-->
                        <!--                xmlns="http://www.w3.org/2000/svg">-->
                        <!--                <path-->
                        <!--                    d="M8.00016 14.6668C11.6668 14.6668 14.6668 11.6668 14.6668 8.00016C14.6668 4.3335 11.6668 1.3335 8.00016 1.3335C4.3335 1.3335 1.3335 4.3335 1.3335 8.00016C1.3335 11.6668 4.3335 14.6668 8.00016 14.6668Z"-->
                        <!--                    stroke="#EE1D48" stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--                <path d="M8 5.3335V8.66683" stroke="#EE1D48" stroke-linecap="round"-->
                        <!--                    stroke-linejoin="round" />-->
                        <!--                <path d="M7.99609 10.6665H8.00208" stroke="#EE1D48" stroke-width="1.5"-->
                        <!--                    stroke-linecap="round" stroke-linejoin="round" />-->
                        <!--            </svg>-->
                        <!--        </button>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!--@if(!empty($vouchers) && is_iterable($vouchers))-->
                        <!--    @foreach($vouchers as $voucher)-->
                        <!--        <div class="rest-voucher d-inline-block">-->
                        <!--            @php-->
                        <!--                $amount = $voucher->discount_type == \App\Enums\DiscountType::FIXED-->
                        <!--                        ? currencyName(round($voucher->amount))-->
                        <!--                        : round($voucher->amount) . '%';-->
                        <!--            @endphp-->
                        <!--            <button class="me-2">-->
                        <!--                {{ $voucher->restaurant_id == 0 ? __('frontend.coupon') : __('frontend.voucher') }}-->
                        <!--                <span>{{ $voucher->slug }}</span>-->
                        <!--            </button>-->
                        <!--        </div>-->
                        <!--    @endforeach-->
                        <!--@endif-->
                                
                        <div class="rest-menu-wrapper" id="scrollspy-menu">
                            <div class="rest-menu-group">
                                <button type="button" class="rest-swiper-prev fa-solid fa-chevron-left"></button>
                                <div class="swiper rest-swiper">
                                    <nav class="swiper-wrapper">
                                        @foreach ($categories as $category)
                                            <a href="#listing_product{{ $category->id }}" wire:key="{{ $category->id }}"
                                                class="swiper-slide">
                                                {{ $category->name }}
                                            </a>
                                        @endforeach
                                        @if (!blank($other_products))
                                            <a href="#listing_product_other" class="swiper-slide">
                                                {{ __('frontend.other') }}
                                            </a>
                                        @endif
                                    </nav>
                                </div>
                                <button type="button" class="rest-swiper-next fa-solid fa-chevron-right"></button>
                            </div>
                        </div>

                        @livewire('show-page', ['restaurant' => $restaurant])

                    </div>
                    @include('frontend.partials._footer')
                </div>

            </div>
        </div>
    </section>
    
<style>

/* Default: hide background (mobile-first approach) */
.hero-bg {
    display: none;
}

.hero-image {
    width: 60px;              /* Adjust size as needed */
    height: 60px;
    border-radius: 5%;
    overflow: hidden;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;         /* Optional background */
}

.hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;        /* Ensures proper cropping */
}

.hero-top-navigation {
    position: relative;
    z-index: 5;
    margin: 10px 0 10px 0; /* Changed from 30px to 10px */
    padding: 0 clamp(20px, 5vw, 150px);
    display: flex;
    align-items: center; /* Center vertically */
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.hero-tab-bar {
    display: flex;
    align-items: center;
    gap: clamp(4px, 1vw, 8px);
    margin-bottom: 0; /* Removed bottom margin since parent handles spacing */
    flex-wrap: wrap;
    justify-content: flex-start;
}

.tab-pill {
    border: none;
    background: transparent;
    padding: clamp(8px, 2vw, 10px) clamp(16px, 3vw, 24px);
    font-weight: 700;
    color: #4b5563;
    display: flex;
    align-items: center;
    gap: clamp(6px, 1.5vw, 8px);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    border-radius: 10px;
    font-size: clamp(14px, 2.5vw, 15px);
    min-width: fit-content;
    white-space: nowrap;
}

/* Active State */
.tab-pill.active {
    background: #f3f0ff; 
    color: #6d28d9;
}

/* Hover State */
.tab-pill:hover:not(.active) {
    background: #f9fafb;
    transform: translateY(-1px);
}

.tab-pill:active {
    transform: translateY(0);
}

.tab-divider {
    width: 1px;
    height: 24px;
    background: #e5e7eb;
    margin: 0 4px;
}

/* Mobile-specific adjustments */
@media (max-width: 640px) {
    .hero-top-navigation {
        margin-bottom: 10px;
    }
    
    .hero-tab-bar {
        gap: 12px;
    }
    
    .tab-pill {
        padding: 12px 20px;
        font-size: 14px;
        min-width: 140px;
    }
    
    .tab-icon {
        font-size: 16px;
    }
}

/* Very small screens */
@media (max-width: 480px) {
    .hero-top-navigation {
        padding: 0 16px;
    }
    
    .hero-tab-bar {
        flex-direction: row; /* Always row, never column */
        flex-wrap: wrap;
        width: 100%;
        gap: 8px;
        align-items: center;
        justify-content: flex-start;
    }
    
    .tab-pill {
        width: auto;
        max-width: none;
        padding: 12px 18px;
        font-size: 14px;
    }
}

/* Large screens optimization */
@media (min-width: 1200px) {
    .hero-top-navigation {
        padding: 0 150px;
    }
    
    .tab-pill {
        padding: 12px 28px;
        font-size: 16px;
    }
}


/* Show background only on large screens (≥ 992px) */
@media (min-width: 992px) {
    .hero-bg {
        display: block;
        position: absolute;
        inset: 0;
        filter: brightness(0.5);
        z-index: 0;
    }

    .hero-section {
        position: relative;
    }
    
  

 .hero-container {
    position: relative;
    display: flex;
    gap: 60px;
    z-index: 1;
    width: 100%;
    margin-right: auto;
    margin-left: auto;
    padding-right: 15px;
    padding-left: 150px;
}

.rest-content{
    margin-top: 50px;
}
    
    .rest-title{
        color:white;
        margin-bottom: 16px;

    }
    
    .hero-section {
    position: relative;
    padding: 80px 0;
    overflow: hidden;
}

.hero-title{
    color:white;
    font-size: 36px;
    margin-bottom: 20px;
}
}

@media (max-width: 992px){
    
    .hero-title{
        color:black;
        font-size: 25px;
    }
    
    .hero-address{
        color:black;
        font-size: 14px;
    }

   .hero-container {
    position: relative;
    display: flex;
    gap: 60px;
    z-index: 1;
    width: 100%;
    margin-right: auto;
    margin-left: auto;
    padding-right: 0px;
    padding-left: 0px;
}

.hero-section {
    position: relative;
    padding: 0 0;
    overflow: hidden;
}

.rest-title{
    color:black;
    margin-top: 8px;
    margin-bottom: 8px;

}
}



.hero-card {
    width: 420px;
    height: 320px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.hero-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-content {
    color: #fff;
    max-width: 600px;
}

.hero-title {
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-dot.open {
    background: #28a745;
}

.status-dot.closed {
    background: red;
}

.hero-address {
    opacity: 0.85;
    margin-bottom: 20px;
}

.hero-buttons {
    display: flex;
    align-items: center;
    gap: 15px;
}

.table-btn {
    background: #6f42c1;
    color: #fff;
    border: none;
    padding: 10px 25px;
    border-radius: 30px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: none;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ----------- RESPONSIVE ----------- */
@media (max-width: 992px) {
  .hero-container {
        flex-direction: column;
        gap: 0px 15px;
    }

    .hero-card {
        width: 100%;
        height: 260px;
        border-radius: 0; /* remove rounded corners on mobile */
    }

    .hero-content {
        max-width: 100%;
        padding: 15px;
    }

 
}

@media (max-width: 576px) {
  

    .hero-card {
        width: 100%;
        height: 220px;
    }
}

/* Contact Modal Styling */
.contact-modal .modal-content {
    border-radius: 16px;
    overflow: hidden;
    border: none;
}

.contact-modal-header {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.contact-modal-header img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.contact-modal-header .close-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    font-size: 24px;
    color: #6C34CC;
    background: white;
    border: none;
    padding: 6px 6px;
    border-radius: 100%;
    cursor: pointer;
    transition: 0.2s;
}


/* Restaurant Meta Info */
.contact-modal-meta {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.contact-modal-meta h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.contact-modal-meta h4 {
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
    margin: 0;
}

.contact-modal-meta h4 span {
    margin: 0 4px;
    color: #d1d5db;
}

/* Contact Section */
.contact-section {
    padding: 24px;
}

.contact-type-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.contact-type-header i {
    font-size: 24px;
    color: #7c3aed;
}

.contact-type-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
}

/* Contact Details */
.contact-details {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-bottom: 24px;
}

.contact-detail-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
}

.detail-label i {
    color: #7c3aed;
    font-size: 14px;
}

.detail-value {
    font-size: 15px;
    font-weight: 500;
    color: #1f2937;
    margin: 0;
}

.detail-value a {
    color: #7c3aed;
    text-decoration: none;
    font-weight: 600;
}

.detail-value a:hover {
    text-decoration: underline;
}

/* Call Button */
.contact-action {
    display: flex;
    gap: 12px;
}

.call-btn {
    flex: 1;
    padding: 14px 20px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 15px;
}

.call-btn:hover {
    background: linear-gradient(135deg, #6d28d9, #5b21b6);
    transform: translateY(-2px);
}

/* Footer Note */
.contact-modal-footer {
    padding: 16px 24px;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}

.contact-modal-footer p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}

/* Responsive */
@media (max-width: 576px) {
    .contact-modal-header {
        height: 140px;
    }

    .contact-modal-meta {
        padding: 16px 18px;
    }

    .contact-section {
        padding: 18px;
    }

    .contact-details {
        gap: 14px;
    }

    .detail-value {
        font-size: 14px;
    }

    .call-btn {
        padding: 12px 16px;
        font-size: 14px;
    }
}



</style>    <!--=======  RESTAURANT PART END ========-->

    <!--======= Add to Cart Modal Start  ========-->
    @livewire('show-cart', ['restaurant' => $restaurant])


    <!--====== Table BOOKING MODAL START =========-->
    <div class="modal fade booking-modal" id="booking-modal" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="booking-modal-header">
                    <button class="fa-regular fa-circle-xmark" type="button" data-bs-dismiss="modal"></button>
                    <h3>{{ __('frontend.table_booking') }} </h3>
                    <img src="{{ asset('frontend/images/gif/table.gif') }}" alt="gif">
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('restaurant.reservation') }}" class="bookForm" method="GET">
                    <input type="hidden" name="restaurant_id" id="restaurant_id" value="{{ $restaurant->id }}">
                    <div class="booking-modal-content">
                        <div class="booking-modal-group">
                            <div class="booking-modal-select">
                                <h4><span> {{ __('frontend.pick_date') }} </span>
                                    <span class="dateshow">
                                        {{ date('d M Y') }}
                                    </span>
                                </h4>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="booking-modal-option">
                                <dl>
                                    <dt>{{ __('frontend.choose_date') }} </dt>
                                    <dd class="date">
                                        <input type="date" name="reservation_date" value="{{ date('Y-m-d') }}" id="datePick">
                                    </dd>
                                </dl>
                                <button class="done" type="button">{{ __('frontend.done') }} </button>
                            </div>
                        </div>
                        <div class="booking-modal-group">
                            <div class="booking-modal-select">
                                <h4><span>{{ __('frontend.number_guests') }} </span>
                                    <span class="guestQty guestotal d-inline">1</span>
                                    <span class="d-inline guestQty">{{ __('frontend.guests') }} </span>
                                </h4>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                            <div class="booking-modal-option">
                                <dl>
                                    <dt> {{ __('frontend.choose_guests_number') }} </dt>
                                    <dd class="cart-counter">
                                        <button type="button" class="fa-solid fa-minus qminus"></button>
                                        <input type="number" name="qtyInput" value="1" id="qtyInput"
                                            class="cart-counter-value">
                                        <button type="button" class="fa-solid fa-plus qplus"></button>
                                    </dd>
                                </dl>
                                <button class="done plusMinusBtn" type="button">{{ __('frontend.done') }}</button>
                            </div>
                        </div>
                        <div class="booking-modal-time  @error('time_slot') is-invalid @enderror">
                            <h4>{{ __('frontend.time_slots') }}</h4>

                            <ul class="panel-dropdown-scrollable  reserveList" id="showTimeSlot">

                            </ul>

                        </div>

                        <div class="text-danger jsbook">

                        </div>
                    </div>
                    <div class="booking-modal-footer">
                        <button type="submit" id="bkkkid" class="cart-btn">{{ __('frontend.request_to_book') }} </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--====== Table BOOKING MODAL PART END ==========-->

<div class="modal fade contact-modal" id="contact-modal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header with Image -->
            <div class="contact-modal-header">
                <button class="fa-regular fa-circle-xmark close-btn" type="button" data-bs-dismiss="modal"></button>
                <img src="{{ $restaurant->image }}" alt="restaurant">
            </div>

            <!-- Restaurant Info -->
            <div class="contact-modal-meta">
                <h3>{{ $restaurant->name }}</h3>
                @if (!blank($restaurant->cuisines))
                    <h4>
                        @foreach ($restaurant->cuisines as $cuisine)
                            {{ $cuisine->name }}
                            @if (!$loop->last)
                                <span>-</span>
                            @endif
                        @endforeach
                    </h4>
                @endif
            </div>

            <!-- Contact Info Section -->
            <div class="contact-section">
                <div class="contact-type-header">
                    <i id="contactTypeIcon" class="fa-solid fa-truck-ramp-box"></i>
                    <h4 id="contactTypeTitle">Bulk Order</h4>
                </div>

                <div class="contact-details">
                    <div class="contact-detail-item">
                        <span class="detail-label">
                            <i class="fa-solid fa-phone"></i>
                            Phone
                        </span>
                        <a id="contactPhone" href="tel:+15559876543" class="detail-value">
                            {{ $owner_mobile }}
                        </a>
                    </div>

                    <div class="contact-detail-item">
                        <span class="detail-label">
                            <i class="fa-solid fa-clock"></i>
                            Available Time
                        </span>
                        <p class="detail-value">
                            {{ date('h:i A', strtotime($restaurant->opening_time)) }} -
                            {{ date('h:i A', strtotime($restaurant->closing_time)) }}
                        </p>
                    </div>

                    <div class="contact-detail-item">
                        <span class="detail-label">
                            <i class="fa-solid fa-map-pin"></i>
                            Address
                        </span>
                        <p class="detail-value">{{ $restaurant->address }}</p>
                    </div>
                </div>

                <div class="contact-action">
                    <a id="callButton" href="tel:{{ $owner_mobile }}" class="call-btn">
                        <i class="fa-solid fa-phone"></i>
                        Call Now
                    </a>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="contact-modal-footer">
                <p id="contactNote">For bulk orders, please call during business hours</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade contact-modal" id="contact1-modal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header with Image -->
            <div class="contact-modal-header">
                <button class="fa-regular fa-circle-xmark close-btn" type="button" data-bs-dismiss="modal"></button>
                <img src="{{ $restaurant->image }}" alt="restaurant">
            </div>

            <!-- Restaurant Info -->
            <div class="contact-modal-meta">
                <h3>{{ $restaurant->name }}</h3>
                @if (!blank($restaurant->cuisines))
                    <h4>
                        @foreach ($restaurant->cuisines as $cuisine)
                            {{ $cuisine->name }}
                            @if (!$loop->last)
                                <span>-</span>
                            @endif
                        @endforeach
                    </h4>
                @endif
            </div>

            <!-- Contact Info Section -->
            <div class="contact-section">
                <div class="contact-type-header">
                    <!--<i id="contactTypeIcon" class="fa-solid fa-truck-ramp-box"></i>-->
                    <i class="fa-solid fa-cake-candles"></i>
                    <h4 id="contactTypeTitle">Party</h4>
                </div>

                <div class="contact-details">
                    <div class="contact-detail-item">
                        <span class="detail-label">
                            <i class="fa-solid fa-phone"></i>
                            Phone
                        </span>
                        <a id="contactPhone" href="tel:{{ $owner_mobile }}" class="detail-value">
                         {{ $owner_mobile }}
                        </a>
                    </div>

                    <div class="contact-detail-item">
                        <span class="detail-label">
                            <i class="fa-solid fa-clock"></i>
                            Available Time
                        </span>
                        <p class="detail-value">
                            {{ date('h:i A', strtotime($restaurant->opening_time)) }} -
                            {{ date('h:i A', strtotime($restaurant->closing_time)) }}
                        </p>
                    </div>

                    <div class="contact-detail-item">
                        <span class="detail-label">
                            <i class="fa-solid fa-map-pin"></i>
                            Address
                        </span>
                        <p class="detail-value">{{ $restaurant->address }}</p>
                    </div>
                </div>

                <div class="contact-action">
                    <a id="callButton" href="tel:+15559876543" class="call-btn">
                        <i class="fa-solid fa-phone"></i>
                        Call Now
                    </a>
                </div>
            </div>

            
        </div>
    </div>
</div>

    <!--======= Resturent Infromation MODAL START =========-->
    <div class="modal fade shop-modal" id="shop-modal" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="shop-modal-header">
                    <button class="fa-regular fa-circle-xmark" type="button" data-bs-dismiss="modal"></button>
                    <img src="{{ $restaurant->image }}" alt="restaurant">
                </div>
                <div class="shop-modal-meta">
                    <h3>{{ $restaurant->name }} </h3>
                    @if (!blank($restaurant->cuisines))
                        <h4>
                            @foreach ($restaurant->cuisines as $cuisine)
                                {{ $cuisine->name }}
                                @if (!$loop->last)
                                    <span>-</span>
                                @endif
                            @endforeach
                        </h4>
                    @endif
                    <p>{{ __('frontend.open') }} {{ date('h:i A', strtotime($restaurant->opening_time)) }} -
                        {{ date('h:i A', strtotime($restaurant->closing_time)) }} </p>
                </div>
                <div class="nav nav-tabs">
                    <a class="nav-link active" data-bs-toggle="tab" href="#about">{{ __('frontend.about') }}</a>
                    <a class="nav-link" data-bs-toggle="tab" href="#reviews">{{ __('frontend.reviews') }}</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="about">
                        <div class="shop-modal-about">
                            <ul>
                                <li>
                                    <h3>{{ __('frontend.delivery_hours') }} </h3>
                                    <p> {{ date('h:i A', strtotime($restaurant->opening_time)) }} -
                                        {{ date('h:i A', strtotime($restaurant->closing_time)) }} </p>
                                </li>
                                <li>
                                    <h3>{{ __('frontend.address') }}</h3>
                                    <p>{{ $restaurant->address }} </p>
                                </li>
                            </ul>
                            <img src="data:image/png;base64,{!! $qrCode !!}" alt="qr">
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews">

                        @if (!blank($order_status))
                            <form action="{{ route('restaurant.ratings-update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div id="add-review" class="add-review-box custom-width">
                                    <h5>{{ __('frontend.add_review') }}</h5>
                                    <hr>
                                    <div class="sub-ratings-container">
                                        <div class="add-sub-rating">
                                            <div class="sub-rating-title">{{ __('frontend.review') }}
                                                <i class="tip"
                                                    data-tip-content="{{ __('frontend.auality_customer') }}"></i>
                                            </div>
                                            <div class="sub-rating-stars">
                                                <div class="clearfix"></div>
                                                <div class="leave-rating">
                                                    <input class="d-none" type="radio" value="5" name="rating"
                                                        {{ 5 == old('rating') ? 'checked' : '' }} id="rating-5">
                                                    <label for="rating-5" class="fa fa-star"></label>
                                                    <input class="d-none" type="radio" value="4" name="rating"
                                                        {{ 4 == old('rating') ? 'checked' : '' }} id="rating-4">
                                                    <label for="rating-4" class="fa fa-star"></label>
                                                    <input class="d-none" type="radio" value="3" name="rating"
                                                        {{ 3 == old('rating') ? 'checked' : '' }} id="rating-3">
                                                    <label for="rating-3" class="fa fa-star"></label>
                                                    <input class="d-none" type="radio" value="2" name="rating"
                                                        {{ 2 == old('rating') ? 'checked' : '' }} id="rating-2">
                                                    <label for="rating-2" class="fa fa-star"></label>
                                                    <input class="d-none" type="radio" value="1" name="rating"
                                                        {{ 1 == old('rating') ? 'checked' : '' }} id="rating-1">
                                                    <label for="rating-1" class="fa fa-star"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                                    <input type="hidden" name="status" value="5">

                                    <div class="form-group mt-2 pt-2">
                                        <label class="reviewLabel">{{ __('frontend.write_review') }} <span
                                                class="text-danger">*</span> </label>
                                        <textarea name="review" type="text" cols="40" rows="3" aria-label="With textarea"
                                            placeholder="{{ __('frontend.write_review') }} " class="form-control @error('review') is-invalid @enderror">{{ old('review') }}</textarea>
                                        @if ($errors->has('review'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('review') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <button type="submit" class="rest-book-btn">
                                        {{ __('frontend.submit_review') }}
                                    </button>
                                </div>
                            </form>
                        @endif
                        <br>
                        @if (!blank($ratings))
                            <ul class="shop-modal-review">
                                @foreach ($ratings as $rating)
                                    <li>
                                        <dl>

                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $rating->rating)
                                                    <svg class="active" width="14" height="14"
                                                        viewBox="0 0 14 14" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.97191 1.37497C6.4383 0.599986 7.56186 0.599985 8.02825 1.37497L9.15178 3.24189C9.31933 3.5203 9.59263 3.71886 9.90919 3.79218L12.0319 4.28381C12.9131 4.48789 13.2603 5.55646 12.6674 6.23951L11.239 7.88495C11.026 8.13034 10.9216 8.45162 10.9497 8.77535L11.1381 10.9461C11.2163 11.8472 10.3073 12.5076 9.47449 12.1548L7.46819 11.3048C7.16899 11.1781 6.83117 11.1781 6.53197 11.3048L4.52568 12.1548C3.69283 12.5076 2.78386 11.8472 2.86206 10.9461L3.05045 8.77535C3.07855 8.45162 2.97416 8.13034 2.76115 7.88495L1.33279 6.23951C0.739863 5.55646 1.08706 4.48789 1.96824 4.28381L4.09097 3.79218C4.40753 3.71886 4.68083 3.5203 4.84838 3.24189L5.97191 1.37497Z"
                                                            stroke-width="1.5" />
                                                    </svg>
                                                @else
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.97191 1.37497C6.4383 0.599986 7.56186 0.599985 8.02825 1.37497L9.15178 3.24189C9.31933 3.5203 9.59263 3.71886 9.90919 3.79218L12.0319 4.28381C12.9131 4.48789 13.2603 5.55646 12.6674 6.23951L11.239 7.88495C11.026 8.13034 10.9216 8.45162 10.9497 8.77535L11.1381 10.9461C11.2163 11.8472 10.3073 12.5076 9.47449 12.1548L7.46819 11.3048C7.16899 11.1781 6.83117 11.1781 6.53197 11.3048L4.52568 12.1548C3.69283 12.5076 2.78386 11.8472 2.86206 10.9461L3.05045 8.77535C3.07855 8.45162 2.97416 8.13034 2.76115 7.88495L1.33279 6.23951C0.739863 5.55646 1.08706 4.48789 1.96824 4.28381L4.09097 3.79218C4.40753 3.71886 4.68083 3.5203 4.84838 3.24189L5.97191 1.37497Z"
                                                            stroke-width="1.5" />
                                                    </svg>
                                                @endif
                                            @endfor


                                            <div class="star-rating" data-rating="{{ $rating->rating }}"> </div>

                                            <dd> {{ $rating->updated_at->format('d M Y, h:i A') }}</dd>
                                        </dl>
                                        <p>{{ $rating->review }} </p>
                                    </li>
                                @endforeach
                            </ul>
                            <br>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--======= Resturent Infromation MODAL END ==========-->
@endsection

@push('js')
    <script> const reservationUrl = "{{ route('reservation.check') }}";</script>
    <script src="{{ asset('frontend/js/booking.js') }}" type="text/javascript"></script>
    <script src="{{ asset('frontend/js/show.js') }}" type="text/javascript"></script>
    <script src="{{ asset('frontend/js/loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('frontend/js/navcount.js') }}" type="text/javascript"></script>
@endpush

@push('livewire')
    <script src="{{ asset('js/order-cart.js') }}"></script>
@endpush
