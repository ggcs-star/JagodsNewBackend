@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/support') }}
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
            <form role="form" method="POST" action="{{ route('admin.setting.support') }}">
                @csrf
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="support_phone">{{ __('setting.support_phone') }}</label>
                            <input name="support_phone" id="support_phone" type="text"
                                class="db-field-control @error('support_phone') invalid @enderror"
                                value="{{ old('support_phone', setting('support_phone')) }}">
                            @error('support_phone')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12">
                            <button class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('setting.update_support_setting') }}</span>
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
