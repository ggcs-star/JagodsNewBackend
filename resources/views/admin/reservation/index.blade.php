@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('reservations') }}
        </div>
    </div>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('levels.reservation_details') }}</h3>

                @can('reservation_create')
                <div class="db-card-filter">
                    <a href="{{ route('admin.reservation.create') }}" class="db-btn h-[38px] text-white bg-primary"><i class="fa-solid fa-circle-plus"></i> {{ __('levels.add_reservation') }}</a>
                </div>
            @endcan
            </div>
            <div class="db-table-responsive">
                    <table class="db-table stripe" id="maintable" data-url="{{ route('admin.reservation.index') }}" data-status="{{ \App\Enums\Status::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('reservation_edit') || auth()->user()->can('reservation_delete') }}">
                    <thead>
                        <tr>
                            <th class="db-table-head-th">{{ __('levels.name') }}</th>
                            <th class="db-table-head-th">{{ __('levels.phone') }}</th>
                            <th class="db-table-head-th">{{ __('levels.table') }}</th>
                            <th class="db-table-head-th">{{ __('levels.reservation_date') }}</th>
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
    <script src="{{ asset('js/reservation/index.js') }}"></script>
@endpush
