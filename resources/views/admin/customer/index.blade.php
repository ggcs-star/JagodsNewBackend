@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('customers') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('user.customers') }}</h3>
                </div>
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="maintable" data-url="{{ route('admin.customers.get-customers') }}" data-hidecolumn="{{ auth()->user()->can('customers_show') || auth()->user()->can('customers_edit') || auth()->user()->can('customers_delete') }}">
                        <thead>
                            <tr>
                                <th class="db-table-head-th">{{ __('levels.name') }}</th>
                                <th class="db-table-head-th">{{ __('levels.email') }}</th>
                                <th class="db-table-head-th">{{ __('levels.phone') }}</th>
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
    <script src="{{ asset('js/customer/index.js') }}"></script>
@endpush
