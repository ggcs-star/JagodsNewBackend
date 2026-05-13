@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('transaction') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('transaction.transaction_details') }}</h3>
                    <div class="db-card-filter">
                        @if (auth()->id() == 1)
                            <button class="db-card-filter-btn table-filter-btn">
                                <i class="fa-solid fa-filter"></i>
                                <span>filter</span>
                            </button>
                        @endif
                    </div>
                </div>
                @if (auth()->id() == 1)
                    <div class="table-filter-div">
                        <div class="p-5 mb-8">
                            <div class="row">
                                <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                    <label class="db-field-title after:hidden"
                                        for="from_date">{{ __('levels.user') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select class="db-field-control appearance-none" id="user_id" name="user_id">
                                            <option value="">{{ __('levels.select') }}</option>
                                            @if (!blank($users))
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                    <label class="db-field-title after:hidden"
                                        for="from_date">{{ __('transaction.type') }}</label>
                                    <div class="db-field-down-arrow">
                                        <select class="db-field-control appearance-none" id="type_id" name="type_id">
                                            <option value="">{{ __('levels.select') }}</option>
                                                @foreach ((new ReflectionClass(\App\Enums\TransactionType::class))->getConstants() as $key => $type)
                                                    <option value="{{ $type }}">{{ __('transaction_types.'. $type ) }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                    <label class="db-field-title after:hidden"
                                        for="from_date">{{ __('levels.from_date') }}</label>
                                    <input autocomplete="off" class="db-field-control datepicker" id="from_date"
                                        type="date" name="from_date"
                                        value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                </div>
                                <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                    <label for="to_date"
                                        class="db-field-title after:hidden">{{ __('levels.to_date') }}</label>
                                    <input autocomplete="off" class="db-field-control datepicker" id="to_date"
                                        type="date" name="to_date" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                </div>
                                <div class="col-12">
                                    <div class="flex flex-wrap gap-3 mt-4">
                                        <button class="db-btn py-2 text-white bg-primary" type="button" id="get-search">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            <span>{{ __('levels.search') }}</span>
                                        </button>
                                        <button class="db-btn py-2 text-white bg-gray-600" type="button" id="refresh">
                                            <i class="fa-solid fa-xmark"></i>
                                            <span>{{ __('levels.refresh') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="maintable"
                        data-url="{{ route('admin.transaction.get-transaction') }}"
                        data-hidecolumn="{{ auth()->user()->can('transaction') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('transaction.from') }}</th>
                                <th class="db-table-head-th">{{ __('transaction.to') }}</th>
                                <th class="db-table-head-th">{{ __('transaction.type') }}</th>
                                <th class="db-table-head-th">{{ __('levels.date') }}</th>
                                <th class="db-table-head-th">{{ __('levels.amount') }}</th>
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
    <script src="{{ asset('js/transaction/index.js') }}"></script>
@endpush
