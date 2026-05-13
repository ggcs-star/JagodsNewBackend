@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/payment') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')

    <div id="payment">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">
            <button class="db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5 {{ old('settingtypepayment', setting('settingtypepayment')) == 'stripe' || old('settingtypepayment', setting('settingtypepayment')) == '' ? 'active' : '' }}" data-tab="#stripe">
                <i class="fa-solid fa-credit-card text-sm"></i>
                <span class="capitalize whitespace-nowrap text-[15px]">{{ __('setting.stripe') }}</span>
            </button>
            <button class="db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5 {{ old('settingtypepayment', setting('settingtypepayment')) == 'razorpay' ? 'active' : '' }}" data-tab="#razorpay">
                <i class="fa-solid fa-credit-card text-sm"></i>
                <span class="capitalize whitespace-nowrap text-[15px]">{{ __('setting.razorpay') }}</span>
            </button>
            <button class="db-tab-sub-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5 {{ old('settingtypepayment', setting('settingtypepayment')) == 'paystack' ? 'active' : '' }}" data-tab="#paystack">
                <i class="fa-solid fa-credit-card text-sm"></i>
                <span class="capitalize whitespace-nowrap text-[15px]">{{ __('setting.paystack') }}</span>
            </button>
            <div class="dropdown-group w-full">
                <button class="dropdown-btn w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="fa-solid fa-circle-chevron-down text-sm"></i>
                    <span class="capitalize whitespace-nowrap text-[15px]">more payment</span>
                </button>
                <div class="dropdown-list absolute top-[42px] right-0 z-30 p-2 rounded-md bg-white shadow-lg">
                    <button class="db-tab-sub-btn w-full flex items-center whitespace-nowrap justify-start my-0.5 gap-2.5 pl-3 pr-6 py-1.5 text-sm rounded-md capitalize transition text-gray-500 hover:text-primary hover:bg-primary/5 {{ old('settingtypepayment', setting('settingtypepayment')) == 'paypal' ? 'active' : '' }}" data-tab="#paypal"><i class="fa-solid fa-credit-card"></i>{{ __('setting.paypal') }}</button>
                    <button class="db-tab-sub-btn w-full flex items-center whitespace-nowrap justify-start my-0.5 gap-2.5 pl-3 pr-6 py-1.5 text-sm rounded-md capitalize transition text-gray-500 hover:text-primary hover:bg-primary/5 {{ old('settingtypepayment', setting('settingtypepayment')) == 'paytm' ? 'active' : '' }}" data-tab="#paytm"><i class="fa-solid fa-credit-card"></i>{{ __('setting.paytm') }}</button>
                    <button class="db-tab-sub-btn w-full flex items-center whitespace-nowrap justify-start my-0.5 gap-2.5 pl-3 pr-6 py-1.5 text-sm rounded-md capitalize transition text-gray-500 hover:text-primary hover:bg-primary/5 {{ old('settingtypepayment', setting('settingtypepayment')) == 'phonePe' ? 'active' : '' }}" data-tab="#phonePe"><i class="fa-solid fa-credit-card"></i>{{ __('setting.phonePe') }}</button>
                    <button class="db-tab-sub-btn w-full flex items-center whitespace-nowrap justify-start my-0.5 gap-2.5 pl-3 pr-6 py-1.5 text-sm rounded-md capitalize transition text-gray-500 hover:text-primary hover:bg-primary/5 {{ old('settingtypepayment', setting('settingtypepayment')) == 'sslcommerz' ? 'active' : '' }}" data-tab="#sslcommerz"><i class="fa-solid fa-credit-card"></i>{{ __('setting.sslcommerz') }}</button>
                </div>
            </div>
        </div>
        <div id="stripe" class="db-card db-tab-sub-div {{ old('settingtypepayment', setting('settingtypepayment')) == 'stripe' || old('settingtypepayment', setting('settingtypepayment')) == '' ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.stripe_setting') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.payment-update') }}">
                    @csrf
                        <input type="hidden" name="settingtypepayment" value="stripe">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="stripe_key">{{ __('levels.stripe_key') }}
                                </label>
                                <input name="stripe_key" id="stripe_key" type="text"
                                    class="db-field-control @error('stripe_key') invalid @enderror"
                                    value="{{ old('stripe_key', setting('stripe_key') ?? '') }}">
                                @error('stripe_key')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="stripe_secret">{{ __('levels.stripe_secret') }}
                                </label>
                                <input name="stripe_secret" id="stripe_secret" type="text"
                                    class="db-field-control @error('stripe_secret') invalid @enderror"
                                    value="{{ old('stripe_secret', setting('stripe_secret') ?? '') }}">
                                @error('stripe_secret')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('setting.update_stripe_setting') }}</span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div id="razorpay" class="db-card db-tab-sub-div {{ old('settingtypepayment', setting('settingtypepayment')) == 'razorpay' ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.razorpay_setting') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.payment-update') }}">
                    @csrf
                        <input type="hidden" name="settingtypepayment" value="razorpay">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="razorpay_key">{{ __('levels.razorpay_key') }}
                                </label>
                                <input name="razorpay_key" id="razorpay_key" type="text"
                                    class="db-field-control @error('razorpay_key')invalid @enderror"
                                    value="{{ old('razorpay_key', setting('razorpay_key') ?? '') }}">
                                @error('razorpay_key')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="razorpay_secret">{{ __('levels.razorpay_secret') }}
                                </label>
                                <input name="razorpay_secret" id="razorpay_secret" type="text"
                                    class="db-field-control @error('razorpay_secret') invalid @enderror"
                                    value="{{ old('razorpay_secret', setting('razorpay_secret') ?? '') }}">
                                @error('razorpay_secret')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('setting.update_razorpay_setting') }}</span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div id="paystack" class="db-card db-tab-sub-div {{ old('settingtypepayment', setting('settingtypepayment')) == 'paystack' ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.paystack_setting') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.payment-update') }}">
                    @csrf
                        <input type="hidden" name="settingtypepayment" value="paystack">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paystack_key">{{ __('setting.paystack_key') }}
                                </label>
                                <input name="paystack_key" id="paystack_key" type="text"
                                    class="db-field-control @error('paystack_key')invalid @enderror"
                                    value="{{ old('paystack_key', setting('paystack_key') ?? '') }}">
                                @error('paystack_key')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paystack_secret">{{ __('setting.paystack_secret') }}
                                </label>
                                <input name="paystack_secret" id="paystack_secret" type="text"
                                    class="db-field-control @error('paystack_secret') invalid @enderror"
                                    value="{{ old('paystack_secret', setting('paystack_secret') ?? '') }}">
                                @error('paystack_secret')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('setting.update_paystack_setting') }}</span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div id="paypal" class="db-card db-tab-sub-div {{ old('settingtypepayment', setting('settingtypepayment')) == 'paypal' ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.paypal_setting') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.payment-update') }}">
                    @csrf
                        <input type="hidden" name="settingtypepayment" value="paypal">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paypal_client_id">{{ __('setting.paypal_client_id') }}
                                </label>
                                <input name="paypal_client_id" id="paypal_client_id" type="text"
                                    class="db-field-control @error('paypal_client_id')invalid @enderror"
                                    value="{{ old('paypal_client_id', setting('paypal_client_id') ?? '') }}">
                                @error('paypal_client_id')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="paypal_client_secret">{{ __('setting.paypal_client_secret') }}
                                </label>
                            <input name="paypal_client_secret" id="paypal_client_secret"
                                type="text"
                                class="db-field-control @error('paypal_client_secret')invalid @enderror"
                                value="{{ old('paypal_client_secret', setting('paypal_client_secret') ?? '') }}">
                            @error('paypal_client_secret')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paypal_mode">{{ __('setting.paypal_mode') }}
                                </label>
                                <input name="paypal_mode" id="paypal_mode" type="text"
                                    class="db-field-control @error('paypal_mode')invalid @enderror"
                                    value="{{ old('paypal_mode', setting('paypal_mode') ?? '') }}">
                                @error('paypal_mode')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title" for="paypal_app_id">{{ __('setting.paypal_app_id') }}</label>
                                <input name="paypal_app_id" id="paypal_app_id" type="text"
                                    class="db-field-control @error('paypal_app_id')invalid @enderror"
                                    value="{{ old('paypal_app_id', setting('paypal_app_id') ?? '') }}">
                                @error('paypal_app_id')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('setting.update_paypal_setting') }}</span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div id="paytm" class="db-card db-tab-sub-div {{ old('settingtypepayment', setting('settingtypepayment')) == 'paytm' ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.paytm') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.payment-update') }}">
                    @csrf
                        <input type="hidden" name="settingtypepayment" value="paytm">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paytm_environment">{{ __('setting.paytm_environment') }}
                                </label>
                                <input name="paytm_environment" id="paytm_environment" type="text"
                                    class="db-field-control @error('paytm_environment') invalid @enderror"
                                    value="{{ old('paytm_environment', setting('paytm_environment') ?? '') }}">
                                @error('paytm_environment')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paytm_merchant_id">{{ __('setting.paytm_merchant_id') }}
                                </label>
                                <input name="paytm_merchant_id" id="paytm_merchant_id" type="text"
                                    class="db-field-control @error('paytm_merchant_id')invalid @enderror"
                                    value="{{ old('paytm_merchant_id', setting('paytm_merchant_id') ?? '') }}">
                                @error('paytm_merchant_id')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paytm_merchant_key">{{ __('setting.paytm_merchant_key') }}
                                </label>
                                <input name="paytm_merchant_key" id="paytm_merchant_key"
                                    type="text"
                                    class="db-field-control @error('paytm_merchant_key')invalid @enderror"
                                    value="{{ old('paytm_merchant_key', setting('paytm_merchant_key') ?? '') }}">
                                @error('paytm_merchant_key')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="paytm_merchant_website">{{ __('setting.paytm_merchant_website') }}</label>
                                <input name="paytm_merchant_website" id="paytm_merchant_website"
                                type="text"
                                class="db-field-control @error('paytm_merchant_website') invalid @enderror"
                                value="{{ old('paytm_merchant_website', setting('paytm_merchant_website') ?? '') }}">
                            @error('paytm_merchant_website')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="paytm_channel">{{ __('setting.paytm_channel') }}
                                </label>
                            <input name="paytm_channel" id="paytm_channel" type="text"
                                class="db-field-control @error('paytm_channel')invalid @enderror"
                                value="{{ old('paytm_channel', setting('paytm_channel') ?? '') }}">
                            @error('paytm_channel')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="paytm_industry_type">{{ __('setting.paytm_industry_type') }}
                            </label>
                            <input name="paytm_industry_type" id="paytm_industry_type"
                                type="text"
                                class="db-field-control @error('paytm_industry_type') invalid @enderror"
                                value="{{ old('paytm_industry_type', setting('paytm_industry_type') ?? '') }}">
                            @error('paytm_industry_type')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('setting.update_paytm_setting') }}</span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div id="phonePe" class="db-card db-tab-sub-div {{ old('settingtypepayment', setting('settingtypepayment')) == 'phonePe' ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.phonePe') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.payment-update') }}">
                    @csrf
                        <input type="hidden" name="settingtypepayment" value="phonepe">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="phonepe_merchant_id">{{ __('setting.phonepe_merchant_id') }}
                                </label>
                            <input name="phonepe_merchant_id" id="phonepe_merchant_id"
                                type="text"
                                class="db-field-control @error('phonepe_merchant_id') invalid @enderror"
                                value="{{ old('phonepe_merchant_id', setting('phonepe_merchant_id') ?? '') }}">
                            @error('phonepe_merchant_id')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="phonepe_merchant_user_id">{{ __('setting.phonepe_merchant_user_id') }}
                                </label>
                            <input name="phonepe_merchant_user_id" id="phonepe_merchant_user_id"
                                type="text"
                                class="db-field-control @error('phonepe_merchant_user_id') invalid @enderror"
                                value="{{ old('phonepe_merchant_user_id', setting('phonepe_merchant_user_id') ?? '') }}">
                            @error('phonepe_merchant_user_id')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="phonepe_env">{{ __('setting.phonepe_env') }}
                                </label>
                                <input name="phonepe_env" id="phonepe_env" type="text"
                                    class="db-field-control @error('phonepe_env')invalid @enderror"
                                    value="{{ old('phonepe_env', setting('phonepe_env') ?? '') }}">
                                @error('phonepe_env')
                                <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="phonepe_salt_index">{{ __('setting.phonepe_salt_index') }}
                            </label>
                            <input name="phonepe_salt_index" id="phonepe_salt_index"
                                type="text"
                                class="db-field-control @error('phonepe_salt_index')invalid @enderror"
                                value="{{ old('phonepe_salt_index', setting('phonepe_salt_index') ?? '') }}">
                            @error('phonepe_salt_index')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="phonepe_salt_key">{{ __('setting.phonepe_salt_key') }}
                            </label>
                            <input name="phonepe_salt_key" id="phonepe_salt_key" type="text"
                                class="db-field-control @error('phonepe_salt_key')invalid @enderror"
                                value="{{ old('phonepe_salt_key', setting('phonepe_salt_key') ?? '') }}">
                            @error('phonepe_salt_key')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('setting.update_phonepe_setting') }}</span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
        <div id="sslcommerz" class="db-card db-tab-sub-div {{ old('settingtypepayment', setting('settingtypepayment')) == 'sslcommerz' ? 'active' : '' }}">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('setting.sslcommerz') }}</h3>
            </div>
            <div class="db-card-body">
                <form role="form" method="POST"
                action="{{ route('admin.setting.payment-update') }}">
                    @csrf
                        <input type="hidden" name="settingtypepayment" value="sslcommerz">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="sslcommerz_store_name">{{ __('setting.sslcommerz_store_name') }}
                                </label>
                            <input name="sslcommerz_store_name" id="sslcommerz_store_name"
                                type="text"
                                class="db-field-control @error('sslcommerz_store_name') invalid @enderror"
                                value="{{ old('sslcommerz_store_name', setting('sslcommerz_store_name') ?? '') }}">
                            @error('sslcommerz_store_name')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="sslcommerz_store_id">{{ __('setting.sslcommerz_store_id') }}
                                </label>
                            <input name="sslcommerz_store_id" id="sslcommerz_store_id"
                                type="text"
                                class="db-field-control @error('sslcommerz_store_id') invalid @enderror"
                                value="{{ old('sslcommerz_store_id', setting('sslcommerz_store_id') ?? '') }}">
                            @error('sslcommerz_store_id')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required"
                                for="sslcommerz_store_password">{{ __('setting.sslcommerz_store_password') }}
                                </label>
                            <input name="sslcommerz_store_password" id="sslcommerz_store_password"
                                type="text"
                                class="db-field-control @error('sslcommerz_store_password') invalid @enderror"
                                value="{{ old('sslcommerz_store_password', setting('sslcommerz_store_password') ?? '') }}">
                            @error('sslcommerz_store_password')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="sslcommerz_mode">{{ __('setting.sslcommerz_mode') }}
                                </label>
                            <input name="sslcommerz_mode" id="sslcommerz_mode" type="text"
                                class="db-field-control @error('sslcommerz_mode')invalid @enderror"
                                value="{{ old('sslcommerz_mode', setting('sslcommerz_mode') ?? '') }}">
                            @error('sslcommerz_mode')
                            <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('setting.update_sslcommerz_setting') }}</span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
