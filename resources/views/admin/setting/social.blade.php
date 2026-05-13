@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/social') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <!-- SITE SETTING -->
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.social_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form role="form" method="POST" action="{{ route('admin.setting.social-update') }}">
                @csrf
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="facebook">{{ __('setting.facebook') }}</label>
                            <input name="facebook" id="facebook" type="text"
                                class="db-field-control {{ $errors->has('facebook') ? ' invalid ' : '' }}"
                                value="{{ old('facebook', setting('facebook')) }}">
                            @if ($errors->has('facebook'))
                            <small class="db-field-alert">{{ $errors->first('facebook') }}</small>
                            @endif
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="youtube">{{ __('levels.youtube') }}</label>
                            <input name="youtube" id="youtube" type="text"
                                class="db-field-control {{ $errors->has('youtube') ? ' invalid ' : '' }}"
                                value="{{ old('youtube', setting('youtube')) }}">
                            @if ($errors->has('youtube'))
                            <small class="db-field-alert">{{ $errors->first('youtube') }}</small>
                            @endif
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="instagram">{{ __('levels.instagram') }}</label>
                            <input name="instagram" id="instagram" type="text"
                                class="db-field-control {{ $errors->has('instagram') ? ' invalid ' : '' }}"
                                value="{{ old('instagram', setting('instagram')) }}">
                            @if ($errors->has('instagram'))
                            <small class="db-field-alert">{{ $errors->first('instagram') }}</small>
                            @endif
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="twitter">{{ __('levels.twitter') }}</label>
                            <input name="twitter" id="twitter" type="text"
                                class="db-field-control {{ $errors->has('twitter') ? ' invalid ' : '' }}"
                                value="{{ old('twitter', setting('twitter')) }}">
                            @if ($errors->has('twitter'))
                            <small class="db-field-alert">{{ $errors->first('twitter') }}</small>

                            @endif
                        </div>
                        <div class="form-col-12">
                            <button class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('setting.update_social_setting') }}</span>
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection

