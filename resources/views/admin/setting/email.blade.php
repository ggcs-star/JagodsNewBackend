@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/email') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')

    <!-- SITE SETTING -->
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.email_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form role="form" method="POST" action="{{ route('admin.setting.email-update') }}">
                @csrf
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="mail_host">{{ __('levels.mail_host') }}</label>
                            <input name="mail_host" id="mail_host" type="text"
                                class="db-field-control @error('mail_host') invalid @enderror"
                                value="{{ old('mail_host', setting('mail_host')) }}">
                            @error('mail_host')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="mail_port">{{ __('levels.mail_port') }}</label>
                            <input name="mail_port" id="mail_port" class="db-field-control @error('mail_port') invalid @enderror"
                                value="{{ old('mail_port', setting('mail_port')) }}">
                            @error('mail_port')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="mail_username">{{ __('levels.mail_username') }}</label>
                            <input name="mail_username" id="mail_username" type="text"
                            class="db-field-control @error('mail_username') invalid @enderror"
                            value="{{ old('mail_username', setting('mail_username')) }}">
                            @error('mail_username')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="mail_password">{{ __('levels.mail_password') }}</label>
                            <input name="mail_password" id="mail_password" type="text"
                            class="db-field-control @error('mail_password') invalid @enderror"
                            value="{{ old('mail_password', setting('mail_password')) }}">
                            @error('mail_password')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="mail_from_name">{{ __('levels.mail_from_name') }}</label>
                            <input name="mail_from_name" id="mail_from_name" type="text"
                            class="db-field-control @error('mail_from_name') invalid @enderror"
                            value="{{ old('mail_from_name', setting('mail_from_name')) }}">
                            @error('mail_from_name')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="mail_from_address">{{ __('levels.mail_from_address') }}</label>
                            <input name="mail_from_address" id="mail_from_address" type="text"
                            class="db-field-control @error('mail_from_address') invalid @enderror"
                            value="{{ old('mail_from_address', setting('mail_from_address')) }}">
                            @error('mail_from_address')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ __('levels.status') }}</label>
                            <div class="db-field-down-arrow">
                                <select name="mail_disabled" id="mail_disabled"
                                    class="db-field-control appearance-none @error('mail_disabled') invalid @enderror">
                                    <option value="1" {{ (old('mail_disabled', setting('mail_disabled')) == 1) ? 'selected' : '' }}> {{ __('setting.enable') }}</option>
                                    <option value="0" {{ (old('mail_disabled', setting('mail_disabled')) == 0) ? 'selected' : '' }}> {{ __('setting.disable') }}</option>
                                </select>
                            </div>
                            @error('mail_disabled')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12">
                            <button class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('setting.update_email_setting') }}</span>
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

@endsection
