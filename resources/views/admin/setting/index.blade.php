@extends('admin.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                <div class="page-header">
                    @yield('admin.setting.breadcrumbs')
                </div>
            </div>
        </div>
        <div class = "col-12 md:col-4 xl:col-3">
            <div class = "db-card">
                <button type="button" class="settings-btn w-full md:hidden flex items-center justify-center gap-2 p-2 rounded bg-primary text-white"><span class="capitalize">Settings Menu</span><i class="setting-btn-icon icon fa-solid fa-chevron-down text-sm"></i></button>
                <div class = "h-0 overflow-hidden md:h-auto md:overflow-auto transition-all duration-300">
                    <nav class ="p-3">
                        <a href="{{ route('admin.setting.index') }}" class="db-tab-btn {{ request()->is('admin/setting') ? 'active' : '' }} "> <i class="fa-solid fa-globe"></i> {{ __('setting.site_setting') }}</a>
                        <a href="{{ route('admin.setting.sms') }}" class="db-tab-btn {{ request()->is('admin/setting/sms') ? 'active' : '' }}"> <i class="fa-solid fa-message"></i> {{ __('setting.sms_setting') }}</a>
                        <a href="{{ route('admin.setting.payment') }}" class="db-tab-btn {{ request()->is('admin/setting/payment') ? 'active' : '' }}"> <i class="fa-solid fa-credit-card"></i> {{ __('setting.payment_setting') }}</a>
                        <a href="{{ route('admin.setting.email') }}" class="db-tab-btn {{ request()->is('admin/setting/email') ? 'active' : '' }}"> <i class= "fa-regular fa-envelope"></i> {{ __('setting.email_setting') }}</a>
                        <a href="{{ route('admin.setting.notification') }}" class="db-tab-btn {{ request()->is('admin/setting/notification') ? 'active' : '' }}"> <i class="fa-regular fa-bell"></i> {{ __('setting.notification_setting') }}</a>    
                        <a href="{{ route('admin.setting.social-login') }}" class="db-tab-btn {{ request()->is('admin/setting/social-login') ? 'active' : '' }}"> <i class="fa-solid fa-retweet"></i> {{ __('setting.social_login_setting') }}</a>
                        <a href="{{ route('admin.setting.otp') }}" class="db-tab-btn {{ request()->is('admin/setting/otp') ? 'active' : '' }}"> <i class="fa-solid fa-comment-sms"></i> {{ __('setting.otp_setting') }}</a>
                        <a href="{{ route('admin.setting.social') }}" class="db-tab-btn {{ request()->is('admin/setting/social') ? 'active' : '' }}"> <i class="fa-solid fa-users"></i> {{ __('setting.social_setting') }}</a>
                        <a href="{{ route('admin.setting.google-map') }}" class="db-tab-btn {{ request()->is('admin/setting/google-map') ? 'active' : '' }}"> <i class="fa-solid fa-map-location-dot"></i> {{ __('setting.google_map_setting') }}</a>
                        <a href="{{ route('admin.setting.app') }}" class="db-tab-btn {{ request()->is('admin/setting/app-setting') ? 'active' : '' }}"> <i class="fa-solid fa-mobile-screen-button"></i> {{ __('setting.app_setting') }}</a>
                        <a href="{{ route('admin.page.index') }}" class="db-tab-btn {{ request()->is('admin/page*') ? 'active' : '' }}"> <i class="fa-regular fa-folder"></i> {{ __('setting.page') }}</a>
                        <a href="{{ route('admin.setting.theme') }}" class="db-tab-btn {{ request()->is('admin/setting/theme') ? 'active' : '' }}"> <i class="fa-solid fa-brush"></i> {{ __('setting.theme') }}</a>
                        <a href="{{ route('admin.setting.support') }}" class="db-tab-btn {{ request()->is('admin/setting/support-setting') ? 'active' : '' }}"> <i class="fa-solid fa-headset"></i> {{ __('setting.support_setting') }}</a>
                        <a href="{{ url('admin/bank') }}" class="db-tab-btn {{ request()->is('admin/bank*') ? 'active' : '' }}"><i class="fa-solid fa-building-columns"></i>{{ __('menu.bank_details') }}</a>
                        <a href="{{ url('admin/role') }}" class="db-tab-btn {{ request()->is('admin/role*') ? 'active' : '' }}"><i class="fa-solid fa-key"></i> {{ __('menu.role') }}</a>
                        <a href="{{ url('admin/language') }}" class="db-tab-btn {{ request()->is('admin/language*') ? 'active' : '' }}"><i class="fa-regular fa-flag"></i> {{ __('menu.language') }}</a>
                        @if (env('DEMO_MODE') == false)
                            <a href="{{ route('admin.setting.purchasekey') }}" class="db-tab-btn {{ request()->is('admin/setting/purchasekey') ? 'active' : '' }}"> <i class="fa-solid fa-id-card"></i> {{ __('setting.license') }}</a>
                        @endif

                    </nav>
                </div>
            </div>
        </div>

        <div class="col-12 md:col-8 xl:col-9">
            @yield('admin.setting.layout')
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
@endpush

@push('js')
    <script src="{{ asset('js/setting/create.js') }}"></script>
@endpush
