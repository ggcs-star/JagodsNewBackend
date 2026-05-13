<section class="hero-section">

    <div class="hero-bg"></div>

    <div class="hero-content-wrapper">

        <h4 class="hero-small-heading">
            Mix the Orders. Match the Mood.
        </h4>

        <h1 class="hero-main-heading">
            From Chandni Chowk to China!
        </h1>

        <div class="hero-buttons">
            @if (Auth::guest())
                <!-- Guest: Show Login & Register -->
                <a href="{{ route('login') }}" class="hero-btn">{{ __('topbar.sign_in') }}</a>
                <a href="{{ route('register') }}" class="hero-btn">{{ __('topbar.register') }}</a>
            @else
                <!-- Logged-in: Show Dashboard / Custom Button -->
                <?php
                    $myrole  = auth()->user()->myrole ?? 0;
                    $permissionBackend = [2];
                ?>
                @if (!in_array($myrole, $permissionBackend))
                    @if ($myrole == 3 && !auth()->user()->restaurant)
                        <!--<a href="{{ route('admin.restaurants.index') }}" class="hero-btn">{{ __('topbar.dashboard') }}</a>-->
                    @else
                        <!--<a href="{{ route('admin.dashboard.index') }}" class="hero-btn">{{ __('topbar.dashboard') }}</a>-->
                    @endif
                @endif
            @endif
        </div>


        <div class="hero-cards">
            <img src="{{ asset('frontend/images/heroCards.svg') }}" alt="Cuisine Cards">

            <!-- Logo -->
            <div class="hero-logo">
                <img src="{{ asset('frontend/images/jagods.webp') }}" alt="Jagods Logo">
            </div>

            <!-- Tagline -->
            <div class="hero-tagline">
                In Your Pocket. Every Cuisine. One Tap Away.
            </div>
        </div>

    </div>

<style>

/* ================= HERO SECTION ================= */

.hero-section {
    position: relative;
    min-height: 90.5vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 40px 20px;
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
    overflow: hidden;
}

/* Background */
.hero-bg {
    position: absolute;
    inset: 0;
    z-index: 0;
    background: url('{{ asset('frontend/images/heroBackground.png') }}') center center / cover no-repeat;
}

.hero-bg::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.25);
}

.hero-content-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1200px;
}

/* Headings */

.hero-small-heading {
    font-size: 40px;
    font-weight: 500;
    color: white;
}

.hero-main-heading {
    font-size: 64px;
    font-weight: 600;
    color: #FFD33D;
    margin-bottom: 25px;
}

/* Buttons */

.hero-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 35px;
}

.hero-btn {
    padding: 12px 28px;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 500;
    border: 1px solid rgba(255,255,255,0.7);
    background: rgba(255,255,255,0.08);
    color: #fff;
    text-decoration: none;
    backdrop-filter: blur(4px);
    transition: 0.3s ease;
    min-width: 140px;
}

.hero-btn:hover {
    background: #ffffff;
    color: #4b1dbd;
}

/* ================= CARDS ================= */

.hero-cards {
    position: relative;
    width: 100%;
    max-width: 950px;
    margin: 0 auto;
}

.hero-cards img {
    width: 100%;
    display: block;
}

/* Desktop Overlap */

.hero-logo {
    position: absolute;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}

.hero-logo img {
    width: 200px;
}

.hero-tagline {
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    background: #FFD33D;
    color: #2E0E7C;
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 800;
    font-size: 16px;
    z-index: 3;
    white-space: nowrap;
}

/* ================= RESPONSIVE ================= */

@media (max-width: 992px) {

    .hero-small-heading {
        font-size: 26px;
    }

    .hero-main-heading {
        font-size: 40px;
    }
}

/* MOBILE FIX */

@media (max-width: 576px) {

    .hero-section {
        padding: 70px 15px;
        min-height: auto;
    }

    .hero-small-heading {
        font-size: 18px;
        line-height: 1.4;
    }

    .hero-main-heading {
        font-size: 28px;
        line-height: 1.3;
    }

    /* Buttons stay in row */
    .hero-buttons {
        flex-direction: row;
        gap: 12px;
        margin-bottom: 25px;
    }

    .hero-btn {
        flex: 1;
        padding: 10px;
        font-size: 14px;
        text-align: center;
    }

    /* Remove overlap */
    .hero-logo,
    .hero-tagline {
        position: static;
        transform: none;
    }

    /* CENTER LOGO PERFECTLY */
    .hero-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        width: 100%;
    }

    .hero-logo img {
        width: 130px;
        display: block;
    }

    /* Center tagline */
    .hero-tagline {
        margin-top: 12px;
        text-align: center;
        font-size: 13px;
        padding: 10px 18px;
        white-space: normal;
        display: inline-block;
    }
}

</style>

</section>
