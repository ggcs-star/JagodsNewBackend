@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/page') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
    <div class="row">
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('levels.pages') }}</h3>
                    <div class="db-card-filter">
                        <a href="{{ route('admin.page.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('levels.add_pages') }}</span>
                        </a>
                    </div>
                </div>
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="maintable" data-url="{{ route('admin.page.get-page') }}"
                        data-hidecolumn="{{ auth()->user()->can('page_edit') || auth()->user()->can('page_delete') }}">
                        <thead>
                            <tr>
                                <th class="db-table-head-th">{{ __('levels.title') }}</th>
                                <th class="db-table-head-th">{{ __('levels.footer_menu_section') }}</th>
                                <th class="db-table-head-th">{{ __('levels.template') }}</th>
                                <th class="db-table-head-th">{{ __('levels.status') }}</th>
                                <th class="db-table-head-th">{{ __('levels.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/datatable/css/dataTables.tailwindcss.css') }}">
@endpush

@push('js')
    <script src="{{ asset('backend/lib/datatable/js/dataTables.js') }}"></script>
    <script src="{{ asset('backend/lib/datatable/js/dataTables.tailwindcss.js') }}"></script>
    <script src="{{ asset('backend/lib/datatable/js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/page/index.js') }}"></script>
@endpush
