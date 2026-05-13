@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/sms') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <!-- SITE SETTING -->
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.sms_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form class="form-horizontal" role="form" method="POST"
                  action="{{ route('admin.setting.sms-update') }}">
                @csrf
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                               for="twilio_auth_token">{{ __('setting.twilio_auth_token') }}</label>
                        <input name="twilio_auth_token" id="twilio_auth_token" type="text"
                               class="db-field-control {{ $errors->has('twilio_auth_token') ? ' invalid ' : '' }}"
                               value="{{ old('twilio_auth_token', setting('twilio_auth_token')) }}">
                        @if ($errors->has('twilio_auth_token'))
                            <small class="db-field-alert">{{ $errors->first('twilio_auth_token') }}</small>
                        @endif
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required"
                               for="twilio_account_sid">{{ __('levels.twilio_account_sid') }}</label>
                        <input name="twilio_account_sid" id="twilio_account_sid" type="text"
                               class="db-field-control {{ $errors->has('twilio_account_sid') ? ' invalid ' : '' }}"
                               value="{{ old('twilio_account_sid', setting('twilio_account_sid')) }}">
                        @if ($errors->has('twilio_account_sid'))
                            <small class="db-field-alert">{{ $errors->first('twilio_account_sid') }}</small>
                        @endif
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="twilio_from">{{ __('levels.twilio_from') }}</label>
                        <input name="twilio_from" id="twilio_from" type="text"
                               class="db-field-control {{ $errors->has('twilio_from') ? ' invalid ' : '' }}"
                               value="{{ old('twilio_from', setting('twilio_from')) }}">
                        @if ($errors->has('twilio_from'))
                            <small class="db-field-alert">{{ $errors->first('twilio_from') }}</small>
                        @endif
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required">{{ __('levels.status') }}</label>
                        <div class="db-field-down-arrow">
                            <select name="twilio_disabled" id="twilio_disabled"
                                    class="db-field-control appearance-none @error('twilio_disabled') invalid @enderror">
                                <option value="1" {{ (old('twilio_disabled', setting('twilio_disabled')) == 1) ? 'selected' : '' }}> {{ __('setting.enable') }}</option>
                                <option value="0" {{ (old('twilio_disabled', setting('twilio_disabled')) == 0) ? 'selected' : '' }}> {{ __('setting.disable') }}</option>
                            </select>
                        </div>
                        @error('twilio_disabled')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12">
                        <button class="db-btn text-white bg-primary">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ __('setting.update_sms_setting') }}</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
