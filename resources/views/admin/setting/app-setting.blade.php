@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/app') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')

    <!-- SITE SETTING -->
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.app_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form role="form" method="POST" action="{{ route('admin.setting.app-update') }}" enctype="multipart/form-data">
                @csrf
                <fieldset class="p-4 mb-6 border border-[#DBDEE0]">
                    <legend class="py-1.5 px-4 text-base font-semibold capitalize border border-[#DBDEE0] text-primary">{{ __('setting.customer_app_setting') }}</legend>
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="customer_app_name">{{ __('setting.app_name') }}</label>
                            <input name="customer_app_name" id="customer_app_name" type="text" class="db-field-control @error('customer_app_name') invalid @enderror" value="{{ old('customer_app_name', setting('customer_app_name')) }}">
                            @error('customer_app_name')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="customer_app_logo">{{ __('setting.app_logo') }}</label>
                            <input name="customer_app_logo" type="file" class="db-field-control @error('customer_app_logo') invalid @enderror" id="customer_app_logo" onchange="readURL(this,'previewImage1');">
                            @error('customer_app_logo')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror

                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage1" src="{{ asset('images/app/'.setting('customer_app_logo')) }}" alt="{{ __('Food Express Logo') }} test" />
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="customer_splash_screen_logo">{{ __('setting.splash_screen_logo') }}</label>
                            <input name="customer_splash_screen_logo" type="file" class="db-field-control @error('customer_splash_screen_logo') invalid @enderror" id="customer_splash_screen_logo" onchange="readURL(this,'previewImage2');">
                            @error('customer_splash_screen_logo')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror

                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage2" src="{{ asset('images/app/'.setting('customer_splash_screen_logo')) }}" alt="{{ __('Food Express Logo') }}" />
                        </div>
                    </div>
                </fieldset>
                <fieldset class="p-4 mb-6 border border-[#DBDEE0]">
                    <legend class="py-1.5 px-4 text-base font-semibold capitalize border border-[#DBDEE0] text-primary">{{ __('setting.vendor_app_setting') }}</legend>
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="vendor_app_name">{{ __('setting.app_name') }}</label>
                            <input name="vendor_app_name" id="vendor_app_name" type="text" class="db-field-control @error('vendor_app_name') invalid @enderror" value="{{ old('vendor_app_name', setting('vendor_app_name')) }}">
                            @error('vendor_app_name')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="vendor_app_logo">{{ __('setting.app_logo') }}</label>
                            <input name="vendor_app_logo" type="file" class="db-field-control @error('vendor_app_logo') invalid @enderror" id="vendor_app_logo" onchange="readURL(this,'previewImage3');">
                            @error('vendor_app_logo')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror

                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage3" src="{{ asset('images/app/'.setting('vendor_app_logo')) }}" alt="{{ __('Food Express Logo') }}" />
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="vendor_splash_screen_logo">{{ __('setting.splash_screen_logo') }}</label>
                            <input name="vendor_splash_screen_logo" type="file" class="db-field-control @error('vendor_splash_screen_logo') invalid @enderror" id="vendor_splash_screen_logo" onchange="readURL(this,'previewImage4');">
                            @error('vendor_splash_screen_logo')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror

                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage4" src="{{ asset('images/app/'.setting('vendor_splash_screen_logo')) }}" alt="{{ __('Food Express Logo') }}" />
                        </div>
                    </div>
                </fieldset>
                <fieldset class="p-4 mb-6 border border-[#DBDEE0]">
                    <legend class="py-1.5 px-4 text-base font-semibold capitalize border border-[#DBDEE0] text-primary">{{ __('setting.delivery_app_setting') }}</legend>
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="delivery_app_name">{{ __('setting.app_name') }}</label>
                            <input name="delivery_app_name" id="delivery_app_name" type="text" class="db-field-control @error('delivery_app_name') invalid @enderror" value="{{ old('delivery_app_name', setting('delivery_app_name')) }}">
                            @error('delivery_app_name')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="delivery_app_logo">{{ __('setting.app_logo') }}</label>
                            <input name="delivery_app_logo" type="file" class="db-field-control @error('delivery_app_logo') invalid @enderror" id="delivery_app_logo" onchange="readURL(this,'previewImage5');">
                            @error('delivery_app_logo')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror

                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage5" src="{{ asset('images/app/'.setting('delivery_app_logo')) }}" alt="{{ __('Food Express Logo') }}" />
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="delivery_splash_screen_logo">{{ __('Splash Screen Logo') }}</label>
                            <input name="delivery_splash_screen_logo" type="file" class="db-field-control @error('delivery_splash_screen_logo') invalid @enderror" id="delivery_splash_screen_logo" onchange="readURL(this,'previewImage6');">
                            @error('delivery_splash_screen_logo')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror

                            <img class="img-thumbnail image-width mt-4 mb-3" id="previewImage6" src="{{ asset('images/app/'.setting('delivery_splash_screen_logo')) }}" alt="{{ __('Food Express Logo') }}" />
                        </div>
                    </div>
                </fieldset>
                <div class="form-col-12">
                    <button class="db-btn text-white bg-primary">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ __('setting.update_support_setting') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
