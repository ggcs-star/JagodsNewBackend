@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('bank') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('levels.bank') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.profile-bank',$bank) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title required">{{ __('bank.bank_name') }}</label>
                                <input type="text" name="bank_name"
                                    class="db-field-control @error('bank_name') invalid @enderror"
                                    value="{{ old('bank_name', $bank->bank_name) }}">
    
                                @error('bank_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('bank.bank_code') }}</label>
                                <input type="text" name="bank_code"
                                    class="db-field-control @error('bank_code') invalid @enderror"
                                    value="{{ old('bank_code', $bank->bank_code) }}">
    
                                @error('bank_code')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('bank.recipient_name') }}</label>
                                <input type="text" name="recipient_name"
                                    class="db-field-control @error('recipient_name') invalid @enderror"
                                    value="{{ old('recipient_name', $bank->recipient_name) }}">
    
                                @error('recipient_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('bank.account_number') }}</label>
                                <input type="text" name="account_number"
                                    class="db-field-control @error('account_number') invalid @enderror"
                                    value="{{ old('account_number', $bank->account_name) }}">
    
                                @error('account_number')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('bank.mobile_agent_name') }}</label>
                                <input type="text" name="mobile_agent_name"
                                    class="db-field-control @error('mobile_agent_name') invalid @enderror" value="{{ old('mobile_agent_name', $bank->mobile_agent_name) }}">
    
                                @error('mobile_agent_name')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('bank.mobile_agent_number') }}</label>
                                <input type="text" name="mobile_agent_number"
                                    class="db-field-control @error('mobile_agent_number') invalid @enderror" value="{{ old('mobile_agent_number', $bank->mobile_agent_number) }}" onkeypress="validate(event)">
    
                                @error('mobile_agent_number')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
    
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('bank.paypal_id') }}</label>
                                <input type="text" name="paypal_id"
                                    class="db-field-control datepicker @error('paypal_id') invalid @enderror"
                                    value="{{ old('paypal_id', $bank->paypal_id) }}">
    
                                @error('paypal_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title">{{ __('bank.upi_id') }}</label>
                                <input type="text" name="upi_id"
                                    class="db-field-control datepicker @error('upi_id') invalid @enderror"
                                    value="{{ old('upi_id', $bank->upi_id) }}">
    
                                @error('upi_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
    
    
                            <div class="col-12">
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
    </div>
@endsection