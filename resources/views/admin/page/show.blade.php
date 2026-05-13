@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/page/show') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <div class="row">
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('levels.pages') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="text-lg font-medium capitalize mb-2 text-paragraph">{{ $page->title }}</h3>
                            <label>{!! $page->statusName !!}</label>
                            <p class="db-light-text pt-4">{!! $page->description !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection