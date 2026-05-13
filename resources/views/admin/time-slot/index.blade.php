@extends('admin.app')

@section('content')
  
  <div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('time-slots') }}
        </div>
        </div>

        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('time_slot.time_slot_detail') }}</h3>
                    <div class="db-card-filter">
                        @can('time-slots_create')
                            <a href="{{ route('admin.time-slots.create') }}" class="db-btn h-[38px] text-white bg-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>{{ __('time_slot.add_time_slot') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>
    
                <div class="db-table-responsive">
                    <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.time-slots.index') }}" data-status="{{ \App\Enums\Status::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('time-slots_edit') || auth()->user()->can('time-slots_delete') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.restaurant') }}</th>
                                <th class="db-table-head-th">{{ __('levels.start_time') }}</th>
                                <th class="db-table-head-th">{{ __('levels.end_time') }}</th>
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
    <script src="{{ asset('js/time-slot/index.js') }}"></script>
@endpush
