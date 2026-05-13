@extends('frontend.layouts.app')

@push('style')
<link rel="stylesheet" href="{{ asset('frontend/lib/inttelinput/css/intlTelInput.css') }}">
@endpush

@section('main-content')

<section class="register-hero">

    <!-- Overlay -->
    <div class="register-overlay"></div>

    <div class="container-fluid">
        <div class="row min-vh-70 align-items-center justify-content-end">

            <!-- REGISTER FORM -->
<div class="col-12 col-lg-6 d-flex justify-content-center my-4 min-vh-70">
                <div class="auth-content register-box">

                    <!-- NAVIGATION -->
                    <nav class="auth-navs mb-1">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('login') }}</a>
                        <a class="nav-link active"
                           href="{{ route('register') }}"
                      >
                            {{ __('register') }}
                        </a>
                    </nav>

                    <!-- FORM -->
                                   <form method="POST" action="{{ route('register') }}" class="form-inputs">
                    @csrf
                
                    <ul class="auth-types">
                        <li>
                            <input type="radio" id="CustomerRegister" name="roles" value="2"
                                   style="accent-color:#6C34CC;"
                                   {{ old('roles',2)==2 ? 'checked' : '' }}>
                            <label for="CustomerRegister">{{ __('Customer') }}</label>
                        </li>
                
                        <!--<li>-->
                        <!--    <input type="radio" id="RestaurantOwnerRegister" name="roles" value="3"-->
                        <!--           style="accent-color:#6C34CC;"-->
                        <!--           {{ old('roles')==3 ? 'checked' : '' }}>-->
                        <!--    <label for="RestaurantOwnerRegister">{{ __('Restaurant Owner') }}</label>-->
                        <!--</li>-->
                
                        <li>
                            <input type="radio" id="DeliveryRegister" name="roles" value="4"
                                   style="accent-color:#6C34CC;"
                                   {{ old('roles')==4 ? 'checked' : '' }}>
                            <label for="DeliveryRegister">{{ __('Delivery Man') }}</label>
                        </li>
                    </ul>
                
                    @error('roles')
                        <div class="invalid-feedback d-block text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                
                    <div class="row">
                
                        <!-- First Name -->
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label required">{{ __('First name') }}</label>
                                <input name="first_name"
                                       value="{{ old('first_name') }}"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       type="text"
                                       placeholder="John">
                
                                @error('first_name')
                                <div class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                
                        <!-- Last Name -->
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label required">{{ __('Last Name') }}</label>
                                <input name="last_name"
                                       value="{{ old('last_name') }}"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       type="text"
                                       placeholder="Doe">
                
                                @error('last_name')
                                <div class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                
                        <!-- Username -->
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label required">{{ __('Username') }}</label>
                                <input name="username"
                                       value="{{ old('username') }}"
                                       class="form-control @error('username') is-invalid @enderror"
                                       type="text"
                                       placeholder="john">
                
                                @error('username')
                                <div class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                
                        <!-- Email -->
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label class="form-label required">{{ __('Email Address') }}</label>
                                <input name="register_email"
                                       value="{{ old('register_email') }}"
                                       class="form-control @error('register_email') is-invalid @enderror"
                                       type="email"
                                       placeholder="johndoe@example.com">
                
                                @error('register_email')
                                <div class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                
                        <!-- Phone -->
                        <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label required">{{ __('frontend.phone') }} </label>
                                            <input
                                                class="form-control mobilenumber @error('mobile') is-invalid @enderror phone"
                                                type="tel" id="number" name="phone" onkeypress='validate(event)'>

                                            <input type="hidden" id="code" name="countrycode" value="1">
                                            <input type="hidden" id="code_name" name="countrycodename" value="us">

                                            @error('phone')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                
                        <!-- Address -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label required">{{ __('Address') }}</label>
                                <input name="address"
                                       value="{{ old('address') }}"
                                       class="form-control @error('address') is-invalid @enderror"
                                       type="text"
                                       placeholder="Add your address">
                
                                @error('address')
                                <div class="invalid-feedback d-block text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                
                      <div class="col-12 col-sm-6">
    <div class="form-group">
        <label class="form-label required">{{ __('Password') }}</label>

        <div class="input-group">
            <input name="password"
                   id="password"
                   class="form-control @error('password') is-invalid @enderror"
                   type="password"
                   placeholder="Create password">

            <span class="input-group-text" onclick="togglePassword('password','icon1')" style="cursor:pointer;">
                <i id="icon1" class="fa fa-eye"></i>
            </span>
        </div>

        @error('password')
        <div class="invalid-feedback d-block text-danger">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>


<div class="col-12 col-sm-6">
    <div class="form-group">
        <label class="form-label required">{{ __('Repeat Password') }}</label>

        <div class="input-group">
            <input name="password_confirmation"
                   id="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   type="password"
                   placeholder="Repeat password">

            <span class="input-group-text" onclick="togglePassword('password_confirmation','icon2')" style="cursor:pointer;">
                <i id="icon2" class="fa fa-eye"></i>
            </span>
        </div>

        @error('password_confirmation')
        <div class="invalid-feedback d-block text-danger">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
                
                        <!-- Submit -->
                        <div class="col-12">
                            <input type="submit"
                                   class="form-btn mt-3"
                                   value="Register">
                        </div>
                
                    </div>
                </form>


                </div>
            </div>

        </div>
    </div>

</section>


<script>
function togglePassword(fieldId, iconId) {

    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);

    if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        field.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }

}
</script>
<style>

/* FULL BACKGROUND */
.register-hero {
    position: relative;
    min-height: 70vh;
    background: url('{{ asset('frontend/images/auth-bg.png') }}') center center / cover no-repeat;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.form-inputs{
    padding: 10px 20px;
}

.form-group {
    margin-bottom: 8px;
    position: relative;
}


/* CONTENT ABOVE OVERLAY */
.register-hero .container-fluid {
    position: relative;
    z-index: 1;
    height: 90.5vh;
}

.auth-content{
    height:81.5vh;
}

.auth-types li label {
    font-size: 12px;
    font-weight: 400;
    line-height: 16px;
    cursor: pointer;
    text-transform: capitalize;
    color: white;
}

/* FORM CARD */
.register-box {
    width: 100%;
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

.register-box .form-control {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.4);
    color: #ffffff;
    border-radius: 12px;
    padding: 12px 14px;
}

.register-box .form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.register-box .form-label {
    color: #ffffff;
    font-weight: 500;
}


/* NAVIGATION */
.auth-navs {
    display: flex;
    gap: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.35);

}

.auth-navs .nav-link{
    color:white;
}

.auth-navs .nav-link.active {
    color: white;
    border-color: white;
}

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

/* RESPONSIVE */
@media (max-width: 992px) {

    .row {
        justify-content: center !important;
    }

    .register-box {
        margin: 40px 15px;
        padding: 30px;
    }

}

</style>

@endsection

@push('js')
<script defer src="{{ asset('frontend/lib/inttelinput/js/intlTelInput-jquery.js') }}"></script>
<script defer src="{{ asset('frontend/lib/inttelinput/js/intlTelInput.js') }}"></script>
<script defer src="{{ asset('frontend/lib/inttelinput/js/utils.js') }}"></script>
<script defer src="{{ asset('frontend/lib/inttelinput/js/data.js') }}"></script>
<script defer src="{{ asset('frontend/lib/inttelinput/js/init.js') }}"></script>
<script src="{{ asset('js/phone_validation/index.js') }}"></script>
@endpush
