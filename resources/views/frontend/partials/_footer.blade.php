<!--======== FOOTER PART START ========-->
<footer class="clean-footer">
    <div class="container">
        <div class="row align-items-start gy-4">

            <!-- LEFT COLUMN -->
            <div class="col-lg-4 col-md-6">
             <img src="{{ themeSetting('site_logo') ? themeSetting('site_logo')->logo : asset('images/seeder/settings/logo.png') }}"
     data-sticky-logo="{{ themeSetting('site_logo') ? themeSetting('site_logo')->logo : asset('images/seeder/settings/logo.png') }}"
     alt="logo"
     class="site-logo">


                <p class="footer-address mt-3">
                    5th Floor, Grand Empio, Shiv Habitat B-Block,<br>
                    Motera Stadium Rd, opp. S Mall, Motera,<br>
                    Ahmedabad, Gujarat 380005
                </p>

                <div class="footer-app-buttons mt-3 d-flex align-items-center">
    <a href="{{ setting('android_app_link') }}" target="_blank">
        <img src="{{ asset('frontend/images/googlePlay.png') }}" alt="Google Play">
    </a>

    <a href="{{ setting('ios_app_link') }}" target="_blank">
        <img src="{{ asset('frontend/images/appStore.png') }}" alt="App Store">
    </a>
</div>

            </div>

            <!-- LEARN MORE -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-title">LEARN MORE</h6>
                <ul class="footer-links">
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Help & Support</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>

            <!-- DELIVERY PARTNERS -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-title">FOR DELIVERY PARTNERS</h6>
                <ul class="footer-links">
                 <li>
                    <a href="https://delivery.jagods.com" target="_blank" rel="noopener">
                        Delivery Partner With Us
                    </a>
                </li>
                <li>
                    <a href="https://merchant.jagods.com" target="_blank" rel="noopener">
                        Delivery Merchant With Us
                    </a>
                </li>

                    <li><a href="#">Apps For You</a></li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">CONTACT US</h6>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-envelope"></i>
                        info@jagods.com
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        8866373077
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} JAGODS All Rights Reserved.
    </div>
</footer>
<!--======== FOOTER PART END ========-->

<style>
    .footer-app-buttons img {
    width: 120px;   /* 👈 size control */
    height: auto;
}

.footer-app-buttons a:not(:last-child) {
    margin-right: 12px;
}
.clean-footer {
    background-color: #F7F7F7; /* 👈 requested background */
    padding-top: 60px;
}

.footer-bottom {
    background-color: #F7F7F7; /* bottom strip same color */
    text-align: center;
    padding: 16px 0;
    font-size: 14px;
    color: #555;
    border-top: 1px solid #e5e5e5;
}

/* App buttons size (already correct, keeping here) */
.footer-app-buttons img {
    width: 120px;
    height: auto;
}

.footer-app-buttons a:not(:last-child) {
    margin-right: 12px;
}
.site-logo {
    width: 120px;   /* 👈 yahan size control karo */
    height: auto;
}
.footer-title {
    color: #000000;          /* pure black */
    font-weight: 600;
}

.footer-links li a {
    color: #000000;          /* pure black */
    font-weight: 500;
}

.footer-links li a:hover {
    color: #6C34CC;          /* optional hover color (brand touch) */
}


</style>
