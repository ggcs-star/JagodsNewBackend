@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
        {{ Breadcrumbs::render('menu-items') }}
        </div>
    </div>

    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('restaurant.menu_items_details') }}</h3>
                <div class="db-card-filter">
                    @can('menu-items_create')
                        <a href="{{ route('admin.menu-items.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('restaurant.add_menu_item') }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <div class="db-table-responsive">
                <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.menu-items.index') }}" data-status="{{ \App\Enums\MenuItemStatus::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('menu-items_show') || auth()->user()->can('menu-items_edit') || auth()->user()->can('menu-items_delete') }}">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ __('levels.name') }}</th>
                            <th class="db-table-head-th">{{ __('levels.categories') }}</th>
                            <th class="db-table-head-th">{{ __('levels.status') }}</th>
                            <th class="db-table-head-th">{{ __('levels.unit_price') }}</th>
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
    <script src="{{ asset('js/menu-item/index.js') }}"></script>
@endpush
