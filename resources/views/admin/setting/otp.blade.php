@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/otp') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <!-- SITE SETTING -->
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.otp_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form role="form" method="POST" action="{{ route('admin.setting.otp') }}">
                @csrf
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="otp_type_checking">{{ __('setting.otp_type') }}</label>
                        <div class="db-field-down-arrow">
                            <select class="db-field-control appearance-none @error('otp_type_checking') invalid @enderror"
                                name="otp_type_checking" id="otp_type_checking">
                                <option value="email"
                                    {{ (old('otp_type_checking', setting('otp_type_checking')) == 'email') ? 'selected' : '' }}>
                                    {{ __('levels.email')}} </option>
                                <option value="phone"
                                    {{ (old('otp_type_checking', setting('otp_type_checking')) == 'phone') ? 'selected' : '' }}>
                                    {{ __('levels.phone') }}</option>
                                <option value="both"
                                    {{ (old('otp_type_checking', setting('otp_type_checking')) == 'both') ? 'selected' : '' }}>
                                    {{ __('setting.both') }}</option>
                            </select>
                        </div>
                        @error('otp_type_checking')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="otp_digit_limit">{{ __('setting.otp_digit_limit') }}</label>
                        <div class="db-field-down-arrow">
                            <select class="db-field-control appearance-none @error('otp_digit_limit') invalid @enderror"
                                name="otp_digit_limit" id="otp_digit_limit">
                                <option value="4"
                                    {{ (old('otp_digit_limit', setting('otp_digit_limit')) == 4) ? 'selected' : '' }}>
                                    {{ __('setting.4')}} </option>
                                <option value="6"
                                    {{ (old('otp_digit_limit', setting('otp_digit_limit')) == 6) ? 'selected' : '' }}>
                                    {{ __('setting.6') }}</option>
                                <option value="8"
                                    {{ (old('otp_digit_limit', setting('otp_digit_limit')) == 8) ? 'selected' : '' }}>
                                    {{ __('setting.8') }}</option>
                            </select>
                        </div>
                        @error('otp_digit_limit')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="otp_expire_time">{{ __('setting.expire_time') }}</label>
                        <input name="otp_expire_time" id="otp_expire_time" type="number"
                            class="db-field-control @error('otp_expire_time') invalid @enderror"
                            value="{{ old('otp_expire_time', setting('otp_expire_time')) }}">
                        @error('otp_expire_time')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12">
                        <button class="db-btn text-white bg-primary">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ __('setting.update_otp_aetting') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
