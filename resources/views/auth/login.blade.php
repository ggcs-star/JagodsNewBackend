@extends('frontend.layouts.app')

@section('main-content')

<section class="login-hero">

    <div class="container-fluid">
        <div class="row my-2 min-vh-90.5 align-items-center justify-content-end">

            <!-- LOGIN FORM -->
            <div class="col-12 col-lg-6 d-flex justify-content-center min-vh-90.5">
                <div class="auth-content login-box">

                    <!-- NAVIGATION -->
                    <nav class="auth-navs">
                        <a class="nav-link active"
                           href="{{ route('login') }}">
                            {{ __('login') }}
                        </a>
                        <a class="nav-link"
                           href="{{ route('register') }}">
                            {{ __('register') }}
                        </a>
                    </nav>

                    <div class="auth-tabs">

                        <!-- HEADER -->
                        <div class="auth-header text-center">
                            <h3 class="text-white">{{ __('Welcome Back!') }}</h3>
                            <p class="text-white-50">
                                {{ __('Please enter your login details below') }}
                            </p>
                        </div>

                        <!-- FORM -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="type" value="frontend">

                            <!-- EMAIL -->
                            <div class="form-group mb-3">
                                <label class="form-label text-white">{{ __('Email') }}</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="Email">

                                <small class="text-white-50">
                                    {{ __("We'll never share your email with anyone else.") }}
                                </small>

                                @error('email')
                                    <span class="text-danger d-block mt-1">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <!-- PASSWORD -->
                           <div class="form-group mb-3">
    <label class="form-label text-white">{{ __('Password') }}</label>

    <div class="input-group">
        <input type="password"
               name="password"
               id="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Password">

        <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
            <i id="toggleIcon" class="fa fa-eye"></i>
        </span>
    </div>

    @error('password')
        <span class="text-danger d-block mt-1">
            {{ $message }}
        </span>
    @enderror
</div>

                            <!-- REMEMBER + FORGOT -->
                            <div class="d-flex justify-content-between align-items-center mb-3 text-white">
                                <div>
                                    <input type="checkbox" id="remember-me" name="remember">
                                    <label for="remember-me">
                                        {{ __('Remember me') }}
                                    </label>
                                </div>

                                <a href="{{ route('password.request') }}"
                                   class="text-white text-decoration-none">
                                    {{ __('Forgot Password?') }}
                                </a>
                            </div>

                            <!-- LOGIN BUTTON -->
                            <input type="submit"
                                   class="form-btn"
                                   value="Login">

                            <!-- SOCIAL LOGIN -->
                            <!--@if (setting('facebook_key') || setting('google_key'))-->
                                <!--<div class="text-white-50 text-center my-3">-->
                                <!--    <span>{{ __('Or Login With') }}</span>-->
                                <!--</div>-->

                                <!--<div class="d-flex gap-3 justify-content-center">-->

                                <!--    @if (setting('google_key'))-->
                                <!--        <a href="{{ route('social-login', 'google') }}"-->
                                <!--           class="social-btn">-->
                                <!--            <img src="{{ asset('frontend/images/social/google.png') }}">-->
                                <!--            Google-->
                                <!--        </a>-->
                                <!--    @endif-->

                                <!--    @if (setting('facebook_key'))-->
                                <!--        <a href="{{ route('social-login', 'facebook') }}"-->
                                <!--           class="social-btn">-->
                                <!--            <img src="{{ asset('frontend/images/social/facebook.png') }}">-->
                                <!--            Facebook-->
                                <!--        </a>-->
                                <!--    @endif-->

                                <!--</div>-->
                            <!--@endif-->

                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

</section>

<script>
function togglePassword() {

    const password = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }

}
</script>
<style>
/* =========================
   BACKGROUND
========================= */

.login-hero {
    position: relative;
    min-height: 90.5vh;
    background: url('{{ asset('frontend/images/auth-bg.png') }}') center/cover no-repeat;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.auth-header h3 {
    line-height: 34px;
    margin-bottom: 8px;
    font-size: 32px;
    font-weight: 600;
    text-transform: capitalize;
}

.auth-header p {
    line-height: 16px;
    font-size: 14px;
    font-weight: 300;
}
}

.login-hero .container-fluid {
    position: relative;
    z-index: 1;
    height: 92.5vh;

}

/* =========================
   GLASS CARD
========================= */
.auth-content{
    height:75.5vh;
}


/* =========================
   NAVIGATION
========================= */

.auth-navs {
    display: flex;
    gap: 25px;
    border-bottom: 1px solid rgba(255,255,255,0.35);
    /*padding-bottom: 12px;*/
    /*margin-bottom: 25px;*/
}

.auth-navs .nav-link {
    color: rgba(255,255,255,0.8);
    font-weight: 500;
}

.auth-navs .nav-link.active {
    color: #ffffff !important;
    border-bottom: 2px solid #ffffff;
}

/* =========================
   HEADER TEXT
   HEADER TEXT
========================= */

.auth-header h3 {
    font-weight: 600;
}


.auth-header {
    padding: 10px 0px 10px;
    text-align: center;
}

/* =========================
   INPUTS
========================= */

.login-box {
    width: 90.5%;
    max-width: 650px;
    /*padding: 45px 50px;*/
    border-radius: 25px;

    /* GLASS EFFECT */
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);

    border: 2px solid rgba(255, 255, 255, 0.35);

    box-shadow:
        0 15px 40px rgba(0, 0, 0, 0.25),
        inset 0 0 0 1px rgba(255, 255, 255, 0.15);

    color: #ffffff;
}

.login-box .form-control {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.4);
    color: #ffffff;
    border-radius: 12px;
    padding: 12px 14px;
}

.login-box .form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.login-box .form-label {
    color: #ffffff;
    font-weight: 500;
}

.form-label {
    font-size: 14px;
}

/* =========================
   BUTTON
========================= */

/* SUBMIT BUTTON */
.form-btn {
    background-color: #6C34CC !important;
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.35);
    width: 100%;
    /*padding: 12px;*/
    border-radius: 6px;
    font-weight: 600;
    transition: 0.3s;
}

.form-btn:hover {
    background-color: #5528a8 !important;
}

/* =========================
   SOCIAL BUTTONS
========================= */

.social-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 18px;
    background: white;
    border-radius: 12px;
    color: black;
    font-weight: 700;   /* Makes text bold */
    text-decoration: none;
    transition: 0.3s ease;
}

.social-btn:hover {
    background: #e9ecef;
}

.social-btn img {
    width: 18px;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 992px) {

    .row {
        justify-content: center !important;
    }

    .login-box {
        margin: 40px 15px;
        padding: 30px;
    }

}


</style>

@endsection
