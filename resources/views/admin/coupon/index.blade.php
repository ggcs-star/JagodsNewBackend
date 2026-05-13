@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
		<div class="custome-breadcrumb">
        {{ Breadcrumbs::render('coupons') }}
        </div>
    </div>

    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('levels.coupon_details') }}</h3>
                <div class="db-card-filter">
                    @can('coupon_create')
                        <a href="{{ route('admin.coupon.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('levels.add_coupon') }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="db-table-responsive">
                <table class="db-table table striped" id="maintable" data-url="{{ route('admin.coupon.index') }}" data-status="{{ \App\Enums\Status::ACTIVE }}"
                data-hidecolumn="{{ auth()->user()->can('coupon_edit') || auth()->user()->can('coupon_delete') || auth()->user()->can('coupon_show') }}" >
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ __('levels.name') }}</th>
                            <th class="db-table-head-th">{{ __('levels.code') }}</th>
                            <th class="db-table-head-th">{{ __('levels.coupon_type') }}</th>
                            <th class="db-table-head-th">{{ __('levels.limit') }}</th>
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
    <script src="{{ asset('js/coupon/index.js') }}"></script>
@endpush
