
@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/theme') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <div id="theme" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">{{__('setting.theme')}}</h3>
        </div>
        <div class="db-card-body">
            <form role="form" method="POST" action="{{ route('admin.setting.theme.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="theme_logo" class="db-field-title">{{__('setting.logo')}} {{ __('setting.logo_size')}}</label>
                        <input name="site_logo" class="db-field-control" id="theme_logo" type="file">
                        <img class="w-[150px] h-[120px] object-fill rounded-lg mt-2" alt="site_logo" src="{{ themeSetting('site_logo') ? themeSetting('site_logo')->logo : asset('images/seeder/settings/logo.png') }}">
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="fav_icon" class="db-field-title">{{__('setting.fav_icon')}} {{__('setting.fav_icon_size')}} </label>
                        <input name="fav_icon" class="db-field-control" id="fav_icon" type="file">
                        <img class="w-[120px] h-[120px] object-fill rounded-lg mt-2" alt="fav_icon" src="{{ themeSetting('fav_icon') ? themeSetting('fav_icon')->favicon : asset('images/seeder/settings/favicon.png') }}">
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="footer_logo" class="db-field-title">{{__('setting.footer_logo')}} {{__('setting.footer_logo_size')}} </label>
                        <input name="site_footer_logo" class="db-field-control" id="footer_logo" type="file">
                        <img class="w-[150px] h-[120px] object-fill rounded-lg mt-2" alt="site_footer_logo" src="{{ themeSetting('site_footer_logo') ? themeSetting('site_footer_logo')->footerLogo : asset('images/seeder/settings/footer_logo.png') }}">
                    </div>
                    <div class="form-col-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{__('levels.save')}}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
