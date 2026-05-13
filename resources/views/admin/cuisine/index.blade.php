@extends('admin.app')

@section('content')

  <div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('cuisines') }}
        </div>
        </div>

        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('cuisine.cuisine_details') }}</h3>
                    <div class="db-card-filter">
                        <button class="db-card-filter-btn table-filter-btn">
                            <i class="fa-solid fa-filter"></i>
                            <span>{{ __('levels.filter') }}</span>
                        </button>
                        @can('cuisine_create')
                            <a href="{{ route('admin.cuisine.create') }}" class="db-btn h-[38px] text-white bg-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>{{ __('cuisine.add_cuisine') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>
    
                <div class="table-filter-div input-group input-daterange" id="date-picker" style="">
                    <div class="p-5 mb-8">
                        <div class="row">
                            <div class="col-4 sm:col-4">
                                <label class="db-field-title">{{ __('levels.status') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="status" id="status" class="db-field-control appearance-none @error('status') invalid @enderror">
                                        <option value="">---</option>
                                        @foreach (trans('statuses') as $key => $status)
                                        <option value="{{ $key }}">
                                            {{ $status }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4 sm:col-4">
                                <label class="db-field-title">{{ __('levels.request') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="requested" id="requested" class="db-field-control appearance-none @error('status') invalid @enderror">
                                        <option value="">---</option>
                                        @foreach (trans('category_requests') as $key => $requested)
                                        <option value="{{ $key }}">
                                            {{ $requested }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4 mt-5">
                                <div class="flex flex-wrap gap-3 input-group-append h-full items-center">
                                    <button class="db-btn py-2 text-white bg-gray-600 h-fit" id="refresh">
                                        <span>{{ __('levels.refresh') }}</span>
                                    </button>
                                    <button class="db-btn py-2 text-white bg-primary h-fit" id="date-search">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <span>{{ __('levels.search') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="db-table-responsive">
                    <table class="db-table table stripe" id="maintable" data-url="{{ route('admin.cuisine.index') }}" data-status="{{ \App\Enums\Status::ACTIVE }}" data-hidecolumn="{{ auth()->user()->can('cuisine_edit') || auth()->user()->can('cuisine_delete') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.name') }}</th>
                                <th class="db-table-head-th">{{ __('levels.created_by') }}</th>
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
    <script src="{{ asset('js/cuisine/index.js') }}"></script>
@endpush
