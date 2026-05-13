@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/language') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
<div class="row">

    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('language.language_details') }}</h3>
                <div class="db-card-filter">
                    <a href="{{ url('translations') }}" target="_blank" class="db-btn h-[38px] bg-primary text-white"> 
                        <i class="fas fa-cog"></i>{{ __('language.translate') }}
                    </a>
                    @can('language_create')
                        <a href="{{ route('admin.language.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('language.add_language') }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="db-table-responsive">
                <table class="db-table stripe" id="maintable" data-url="{{ route('admin.language.index') }}" data-status="{{ \App\Enums\Status::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('language_edit') || auth()->user()->can('language_delete') }}">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ __('language.language_name') }}</th>
                            <th class="db-table-head-th">{{ __('language.flag') }}</th>
                            <th class="db-table-head-th">{{ __('language.language_code') }}</th>
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
<script src="{{ asset('js/language/index.js') }}"></script>
@endpush
