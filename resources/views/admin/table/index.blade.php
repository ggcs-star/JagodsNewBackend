@extends('admin.app')

@section('content')
  
  <div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('tables') }}
        </div>
    </div>

    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('table.table_details') }}</h3>
                <div class="db-card-filter">
                    @can('tables_create')
                        <a href="{{ route('admin.tables.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('table.add_tables') }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="db-table-responsive">
                <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.tables.index') }}" data-status="{{ \App\Enums\Status::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('tables_edit') || auth()->user()->can('tables_delete') }}">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ __('levels.name') }}</th>
                            <th class="db-table-head-th">{{ __('levels.capacity') }}</th>
                            <th class="db-table-head-th">{{ __('levels.restaurant') }}</th>
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
    <script src="{{ asset('js/table/index.js') }}"></script>
@endpush
