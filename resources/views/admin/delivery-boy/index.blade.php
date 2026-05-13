@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
		<div class="custome-breadcrumb">
            {{ Breadcrumbs::render('delivery-boys') }}
        </div>
    </div>


    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('levels.delivery_boys_details') }}</h3>
                <div class="db-card-filter">
                    
                    @can('delivery-boys_create')
                        <a href="{{ route('admin.delivery-boys.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('user.add_delivery_boy') }}</span>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="db-table-responsive">
                <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.delivery-boys.get-delivery-boys') }}" data-hidecolumn="{{ auth()->user()->can('delivery-boys_show') || auth()->user()->can('delivery-boys_edit') || auth()->user()->can('delivery-boys_delete') }}">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
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
    <script src="{{ asset('js/delivery-boy/index.js') }}"></script>
@endpush
