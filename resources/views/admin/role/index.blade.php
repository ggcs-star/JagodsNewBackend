@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('settings/roles') }}
        </div>
    </div>
</div>
@endsection

@section('admin.setting.layout')
  
    <div class="row">


        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('levels.roles_details') }}</h3>
                    <div class="db-card-filter">
                        
                        @can('role_create')
                            <a href="{{ route('admin.role.create') }}" class="db-btn h-[38px] text-white bg-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>{{ __('levels.add_role') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="db-table-responsive">
                    <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.roles.get-roles') }}" data-hidecolumn="{{ auth()->user()->can('role_show') || auth()->user()->can('role_edit') || auth()->user()->can('role_delete') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.id') }}</th>
                                <th class="db-table-head-th">{{ __('levels.name') }}</th>
                                @if (auth()->user()->can('role_show') || auth()->user()->can('role_edit') || auth()->user()->can('role_delete'))
                                <th class="db-table-head-th">{{ __('levels.actions') }}</th>
                                @endif
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
    <script src="{{ asset('js/role/index.js') }}"></script>
@endpush