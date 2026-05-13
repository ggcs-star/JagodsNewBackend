@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/social-login') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <div id="payment">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">
            <button class="db-tab-sub-btn {{ ((old('settingtypesocial', setting('settingtypesocial')) == 'facebook') || (old('settingtypesocial', setting('settingtypesocial')) == '')) ? 'active' : '' }} w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5" data-tab="#facebooktab">
                <i class="fa-solid fa-credit-card text-sm"></i>
                <span class="capitalize whitespace-nowrap text-[15px]">{{ __('setting.facebook') }}</span>
            </button>
            <button class="db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5 {{ (old('settingtypesocial', setting('settingtypesocial')) == 'google') ? 'active' : '' }}" data-tab="#googletab">
                <i class="fa-solid fa-credit-card text-sm"></i>
                <span class="capitalize whitespace-nowrap text-[15px]">{{ __('setting.google') }}</span>
            </button>

        </div>
        <div id="facebooktab" class="db-card db-tab-sub-div {{ ((old('settingtypesocial', setting('settingtypesocial')) == 'facebook') || (old('settingtypesocial', setting('settingtypesocial')) == '')) ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.facebook') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST" action="{{ route('admin.setting.social-login-update') }}">
                @csrf
                    <input type="hidden" name="settingtypesocial" value="facebook">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="facebook_key">{{ __('setting.facebook_client_id') }}</label>
                            <input name="facebook_key" id="facebook_key" type="text"
                                class="db-field-control @error('facebook_key') invalid @enderror"
                                value="{{ old('facebook_key', setting('facebook_key') ?? '') }}">
                            @error('facebook_key')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="facebook_secret">{{ __('setting.facebook_client_secret') }}</label>
                            <input name="facebook_secret" id="facebook_secret" type="text"
                                class="db-field-control @error('facebook_secret') invalid @enderror"
                                value="{{ old('facebook_secret', setting('facebook_secret') ?? '') }}">
                            @error('facebook_secret')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="facebook_url">{{ __('setting.facebook_url') }}</label>
                            <input name="facebook_url" id="facebook_url" type="text"
                                class="db-field-control @error('facebook_url') invalid @enderror"
                                value="{{ old('facebook_url', setting('facebook_url') ?? '') }}">
                            @error('facebook_url')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12">
                            <button class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('setting.update_facebook_setting') }}</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div id="googletab" class="db-card db-tab-sub-div {{ (old('settingtypesocial', setting('settingtypesocial')) == 'google') ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.google') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.social-login-update') }}">
                @csrf
                    <input type="hidden" name="settingtypesocial" value="google">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="google_key">{{ __('setting.googel_client_id') }}</label>
                            <input name="google_key" id="google_key" type="text"
                                class="db-field-control @error('google_key') invalid @enderror"
                                value="{{ old('google_key', setting('google_key') ?? '') }}">
                            @error('google_key')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="google_secret">{{ __('setting.googel_client_sceret') }}</label>
                            <input name="google_secret" id="google_secret" type="text"
                                class="db-field-control @error('google_secret') invalid @enderror"
                                value="{{ old('google_secret', setting('google_secret') ?? '') }}">
                            @error('google_secret')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="google_url">{{ __('setting.googel_url') }}</label>
                            <input name="google_url" id="google_url" type="text"
                                class="db-field-control @error('google_url') invalid @enderror"
                                value="{{ old('google_url', setting('google_url') ?? '') }}">
                            @error('google_url')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12">
                            <button class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('setting.update_google_setting') }}</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
