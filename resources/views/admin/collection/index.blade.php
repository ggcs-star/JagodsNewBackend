@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('collections') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                @can('collection_create')
                    <div class="db-card-header border-none">
                        <h3 class="db-card-title">{{ __('collection.collection_details') }}</h3>
                        <div class="db-card-filter">
                            <a href="{{ route('admin.collection.create') }}" class="db-btn h-[38px] text-white bg-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>{{ __('collection.add_collection') }}</span>
                            </a>
                        </div>
                    </div>
                @endcan
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="maintable" data-url="{{ route('admin.collection.get-collection') }}"
                        data-hidecolumn="{{ auth()->user()->can('collection_delete') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.name') }}</th>
                                <th class="db-table-head-th">{{ __('levels.date') }}</th>
                                <th class="db-table-head-th">{{ __('levels.amount') }}</th>
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
    <script src="{{ asset('js/collection/index.js') }}"></script>
@endpush
