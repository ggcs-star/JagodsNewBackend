@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
		<div class="custome-breadcrumb">
            {{ Breadcrumbs::render('expense') }}
        </div>
    </div>

    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ __('levels.expense_details') }}</h3>
                <div class="db-card-filter">
                    
                    @can('expense_create')
                        <a href="{{ route('admin.expense.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('user.add_expense') }}</span>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="db-table-responsive">
                <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.expense.get-expense') }}" data-hidecolumn="{{ auth()->user()->can('expense_show') || auth()->user()->can('expense_edit') || auth()->user()->can('expense_delete') }}">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ __('levels.title') }}</th>
                            <th class="db-table-head-th">{{ __('levels.amount') }}</th>
                            <th class="db-table-head-th">{{ __('levels.date') }}</th>
                            <th class="db-table-head-th">{{ __('levels.action') }}</th>
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
    <script src="{{ asset('js/expense/index.js') }}"></script>
@endpush