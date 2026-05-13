@extends('admin.setting.index')
@section('admin.setting.breadcrumbs')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('settings/site') }}
            </div>
        </div>
    </div>
@endsection
@section('admin.setting.layout')
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.site_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form role="form" method="POST" action="{{ route('admin.setting.site-update') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="site_name">{{ __('levels.site_name') }}</label>
                        <input name="site_name" id="site_name" type="text"
                            class="db-field-control @error('site_name') invalid @enderror"
                            value="{{ old('site_name', setting('site_name')) }}">
                        @error('site_name')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="site_email">{{ __('levels.site_email') }}</label>
                        <input type="email" name="site_email" id="site_email"
                            class="db-field-control @error('site_email') invalid @enderror"
                            value="{{ old('site_email', setting('site_email')) }}">
                        @error('site_email')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                            for="site_phone_number">{{ __('setting.site_phone_number') }}</label>
                        <input name="site_phone_number" id="site_phone_number" type="text"
                            class="db-field-control @error('site_phone_number') invalid @enderror"
                            value="{{ old('site_phone_number', setting('site_phone_number')) }}"
                            onkeypress='validate(event)'>
                        @error('site_phone_number')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="site_footer">{{ __('levels.site_footer') }}</label>
                        <input name="site_footer" id="site_footer"
                            class="db-field-control @error('site_footer') invalid @enderror"
                            value="{{ old('site_footer', setting('site_footer')) }}">
                        @error('site_footer')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required">{{ __('setting.change_language') }}</label>
                        <div class="db-field-down-arrow">
                            <select name="locale" id="locale"
                                class="db-field-control appearance-none @error('locale') invalid @enderror">
                                <option value="">{{ __('setting.select_language') }}</option>
                                @if (!blank($language))
                                    @foreach ($language as $lang)
                                        <option value="{{ $lang->code }}"
                                            {{ old('locale', setting('locale')) == $lang->code ? 'selected' : '' }}>
                                            <span
                                                class="flag-icon flag-icon-aw ">{{ $lang->flag_icon == null ? '🇬🇧' : $lang->flag_icon }}&nbsp</span>{{ $lang->name }}
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                            @error('locale')
                                <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="timezone">{{ __('levels.timezone') }}</label>
                        <?php
                        $className = 'db-field-control';
                        if ($errors->first('timezone')) {
                            $className = 'db-field-control invalid';
                        }
                        echo Timezonelist::create('timezone', setting('timezone'), ['class' => $className]); ?>
                        @error('timezone')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="currency_code">{{ __('levels.currency_code') }}</label>
                        <input name="currency_code" id="currency_code" type="text"
                            class="db-field-control @error('currency_code') invalid @enderror"
                            value="{{ old('currency_code', setting('currency_code')) }}">
                        @error('currency_code')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="currency_name">{{ __('levels.currency_name') }}</label>
                        <input name="currency_name" id="currency_name" type="text"
                            class="db-field-control @error('currency_name') invalid @enderror"
                            value="{{ old('currency_name', setting('currency_name')) }}">
                        @error('currency_name')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="site_address">{{ __('levels.address') }}</label>
                        <input name="site_address" id="site_address" type="text"
                            class="db-field-control @error('site_address') invalid @enderror"
                            value="{{ old('site_address', setting('site_address')) }}">
                        @error('site_address')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="banner_title">{{ __('levels.banner_title') }}</label>
                        <input name="banner_title" id="banner_title" type="text"
                            class="db-field-control @error('banner_title') invalid @enderror"
                            value=" {{ old('banner_title', setting('banner_title')) }}">
                        @error('banner_title')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="customFile">{{ __('levels.banner_image') }}</label>
                        <input name="banner_image" type="file"
                            class="db-field-control @error('banner_image') invalid @enderror" id="customFile"
                            onchange="readURL(this,'previewImage3');">
                        @error('banner_image')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror

                        @if (setting('banner_image'))
                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage3"
                                src="{{ asset('images/' . setting('banner_image')) }}" alt="{{ __('Banner Image') }}" />
                        @else
                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage3"
                                src="{{ asset('images/defoult/hero.png') }}" alt="{{ __('Banner Image') }}" />
                        @endif
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                            for="order_commission_percentage">{{ __('levels.order_commission_percentage') }}</label>
                        <input name="order_commission_percentage" id="order_commission_percentage" type="number"
                            min="0" max="100"
                            class="db-field-control @error('order_commission_percentage') invalid @enderror"
                            value="{{ old('order_commission_percentage', setting('order_commission_percentage')) }}">
                        @error('order_commission_percentage')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                            for="geolocation_distance_radius">{{ __('levels.geolocation_distance_radius') }}</label>
                        <input name="geolocation_distance_radius" id="geolocation_distance_radius" type="text"
                            class="db-field-control @error('geolocation_distance_radius') invalid @enderror"
                            value="{{ old('geolocation_distance_radius', setting('geolocation_distance_radius')) }}">
                        @error('geolocation_distance_radius')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                            for="delivery_boy_order_amount_limit">{{ __('levels.delivery_boy_order_amount_limit') }}</label>
                        <input name="delivery_boy_order_amount_limit" id="delivery_boy_order_amount_limit" type="number"
                            step=".01" min="0"
                            class="db-field-control @error('delivery_boy_order_amount_limit') invalid @enderror"
                            value="{{ old('delivery_boy_order_amount_limit', setting('delivery_boy_order_amount_limit')) }}">
                        @error('delivery_boy_order_amount_limit')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required">{{ __('levels.order_attachment_checking') }}</label>
                        <div class="db-field-down-arrow">
                            <select name="order_attachment_checking" id="order_attachment_checking"
                                class="db-field-control appearance-none  @error('order_attachment_checking') invalid @enderror">
                                @foreach (trans('order_attachment_checking_statuses') as $key => $order_attachment_checking)
                                    <option value="{{ $key }}"
                                        {{ old('order_attachment_checking', setting('order_attachment_checking')) == $key ? 'selected' : '' }}>
                                        {{ $order_attachment_checking }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('order_attachment_checking')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                            for="free_delivery_radius">{{ __('setting.free_delivery_radius') }}</label>
                        <input name="free_delivery_radius" id="free_delivery_radius" type="number" min="0"
                            class="db-field-control @error('free_delivery_radius') invalid @enderror"
                            value="{{ old('free_delivery_radius', setting('free_delivery_radius')) }}">
                        @error('free_delivery_radius')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                            for="basic_delivery_charge">{{ __('setting.basic_delivery_charge') }}</label>
                        <input name="basic_delivery_charge" id="basic_delivery_charge" type="number"
                            class="db-field-control @error('basic_delivery_charge') invalid @enderror"
                            value="{{ old('basic_delivery_charge', setting('basic_delivery_charge')) }}">
                        @error('basic_delivery_charge')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                            for="charge_per_kilo">{{ __('setting.charge_per_kilo') }}</label>
                        <input name="charge_per_kilo" id="charge_per_kilo" type="number" min="0" max="100"
                            class="db-field-control @error('charge_per_kilo') invalid @enderror"
                            value="{{ old('charge_per_kilo', setting('charge_per_kilo')) }}">
                        @error('charge_per_kilo')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="ios_app_link">{{ __('levels.ios_app_link') }}</label>
                        <input name="ios_app_link" id="ios_app_link" type="text"
                            class="db-field-control @error('ios_app_link') invalid @enderror"
                            value="{{ old('ios_app_link', setting('ios_app_link')) }}">
                        @error('ios_app_link')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="android_app_link">{{ __('levels.android_app_link') }}</label>
                        <input name="android_app_link" id="android_app_link" type="text"
                            class="db-field-control @error('android_app_link') invalid @enderror"
                            value="{{ old('android_app_link', setting('android_app_link')) }}">
                        @error('android_app_link')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="customFile">{{ __('levels.app_section_mockup') }}</label>
                        <input name="app_mockup" type="file"
                            class="db-field-control @error('app_mockup') invalid @enderror" id="customFile"
                            onchange="readURL(this,'previewImage4');">
                        @error('app_mockup')
                            <small class="db-field-alert">{{ $message }}</small>
                        @enderror

                        @if (setting('app_mockup'))
                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage4"
                                src="{{ asset('images/' . setting('app_mockup')) }}" alt="{{ __('App Mockup') }}" />
                        @else
                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage4"
                                src="{{ asset('images/defoult/mockup.png') }}" alt="{{ __('App Mockup') }}" />
                        @endif
                    </div>
                    <div class="form-col-12">
                        <button class="db-btn text-white bg-primary">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ __('setting.update_site_setting') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/phone_validation/index.js') }}"></script>
@endsection
