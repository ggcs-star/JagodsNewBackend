@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/google-map') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <!-- SITE SETTING -->
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.google_map_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.setting.google-map') }}">
                @csrf
                @method('PUT')
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="google_map_api_key">{{ __('levels.google_map_api_key') }}</label>
                            <input class="db-field-control" name="google_map_api_key" id="google_map_api_key" type="text"
                                class="form-control @error('google_map_api_key') invalid @enderror"
                                value="@if(!env('DEMO_MODE')){{ old('google_map_api_key',setting('google_map_api_key')) }}@endif">
                            @error('google_map_api_key')
                            <div class="invalid-feedback">
                                <small class="db-field-alert">{{ $message }}</small>
                            </div>
                            @enderror
                        </div>
                        <div class="form-col-12">
                            <button class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('setting.update_google_map_setting') }}</span>
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
