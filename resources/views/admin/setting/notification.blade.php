@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/notification') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <!-- SITE SETTING -->
    <div id="site" class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ __('setting.notification_setting') }}</h3>
        </div>
        <div class="db-card-body">
            <form role="form" method="POST" action="{{ route('admin.setting.notification-update') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="firebase_api_key">{{ __('setting.firebase_api_key') }}</label>
                        <input name="firebase_api_key" id="firebase_api_key" type="text"
                            class="db-field-control @error('firebase_api_key') invalid @enderror"
                            value="{{ old('firebase_api_key', setting('firebase_api_key')) }}">
                        @error('firebase_api_key')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="firebase_authDomain">{{ __('setting.firebase_authDomain') }}</label>
                        <input name="firebase_authDomain" id="firebase_authDomain" type="text"
                            class="db-field-control @error('firebase_authDomain') invalid @enderror"
                            value="{{ old('firebase_authDomain', setting('firebase_authDomain')) }}">
                        @error('firebase_authDomain')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="projectId">{{ __('setting.projectId') }}</label>                        <input name="projectId" id="projectId" type="text"
                            class="db-field-control @error('projectId') invalid @enderror"
                            value="{{ old('projectId', setting('projectId')) }}">
                        @error('projectId')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="storageBucket">{{ __('setting.storageBucket') }}</label>
                        <input name="storageBucket" id="storageBucket" type="text"
                            class="db-field-control @error('storageBucket') invalid @enderror"
                            value="{{ old('storageBucket', setting('storageBucket')) }}">
                        @error('storageBucket')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="messagingSenderId">{{ __('setting.messagingSenderId') }}</label>
                        <input name="messagingSenderId" id="messagingSenderId" type="text"
                            class="db-field-control @error('messagingSenderId') invalid @enderror"
                            value="{{ old('messagingSenderId', setting('messagingSenderId')) }}">
                        @error('messagingSenderId')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="appId">{{ __('setting.appId') }}</label>
                        <input name="appId" id="appId" type="text"
                            class="db-field-control @error('appId') invalid @enderror"
                            value="{{ old('appId', setting('appId')) }}">
                        @error('appId')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title" for="measurementId">{{ __('setting.measurementId') }}</label>
                        <input name="measurementId" id="measurementId" type="text"
                            class="db-field-control @error('measurementId') invalid @enderror"
                            value="{{ old('measurementId', setting('measurementId')) }}">
                        @error('measurementId')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="notification_fcm_json_file">{{ __('setting.file') }} ({{ __('setting.json') }}) </label>
                        <input name="notification_fcm_json_file" id="notification_fcm_json_file" type="file"
                            class="db-field-control @error('notification_fcm_json_file') invalid @enderror"
                            value="{{ old('notification_fcm_json_file', setting('notification_fcm_json_file')) }}">
                        @error('notification_fcm_json_file')
                        <small class="db-field-alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-col-12">
                        <button class="db-btn text-white bg-primary">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ __('setting.update_notification_setting') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
