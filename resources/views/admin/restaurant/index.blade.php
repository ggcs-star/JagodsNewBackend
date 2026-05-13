@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
        {{ Breadcrumbs::render('restaurants') }}
        </div>
    </div>

    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('restaurant.restaurant_details') }}</h3>
                <div class="db-card-filter">
                    @can('restaurants_create')
                    @if (auth()->user()->myrole == 1)
                    <a href="{{ route('admin.import-restaurant') }}" class="db-card-filter-btn pseudo-none">
                        <i class="fa-solid fa-file-import"></i>
                        <span>{{ __('restaurant.import_restaurant') }}</span>
                    </a>
                    @endif
                    <a href="{{ route('admin.restaurants.create') }}" class="db-btn h-[38px] text-white bg-primary">
                        <i class="fa-solid fa-circle-plus"></i>
                        <span>{{ __('restaurant.add_restaurant') }}</span>
                    </a>
                    @endcan
                </div>
            </div>


            <div class="db-table-responsive">
                <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.restaurant.get-restaurant') }}" data-status="{{ \App\Enums\RestaurantStatus::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('restaurants_show') ||auth()->user()->can('restaurants_edit') ||auth()->user()->can('restaurants_delete') }}">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ __('levels.name') }}</th>
                            <th class="db-table-head-th">{{ __('levels.user') }}</th>
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
    <script src="{{ asset('js/restaurant/index.js') }}"></script>
@endpush
