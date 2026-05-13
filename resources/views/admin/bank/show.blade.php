@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/bank/show') }}
		</div>
	</div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-social/bootstrap-social.css') }}">
<link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
@endsection

@section('admin.setting.layout')

<div class="row">

    <div id="profile" class="col-12 profile-tabDiv active">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('levels.details') }} {{ '('. $bank->user->name .')' }}</h3>
            </div>
            <div class="db-card-body">
                <div class="row py-2">
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.bank_name') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->bank_name) }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.bank_code') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->bank_code) }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.recipient_name') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->recipient_name) }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.account_number') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->account_number) }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.mobile_agent_name') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->mobile_agent_name) }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.mobile_agent_number') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->mobile_agent_number) }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.paypal_id') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->paypal_id) }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('bank.upi_id') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ strip_tags($bank->upi_id) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
