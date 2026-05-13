@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/purchase-key') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <div class="col-md-9">
        <div id="" class="db-card db-tab-div active">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.license') }}</h3>
            </div>
            <div class="db-card-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('admin.setting.purchasekey-update') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="license_code" class="db-field-title required">{{ __('setting.purchase_key') }}</label>
                            <input class="db-field-control @error('license_code') invalid @enderror" type="text" id="license_code" name="license_code" value="{{ old('license_code', setting('license_code')) }}">
                            @error('license_code')
                                <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12">
                            <button type="submit" class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('levels.save') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
