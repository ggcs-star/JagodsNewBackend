@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/bank') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')

    <div class="row">

        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('bank.bank_details') }}</h3>
                    <div class="db-card-filter">
                        @if (auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                        <button class="db-card-filter-btn table-filter-btn">
                            <i class="fa-solid fa-filter"></i>
                            <span>{{ __('levels.filter') }}</span>
                        </button>
                        @endif
                        @can('bank_create')
                            <a href="{{ route('admin.bank.create') }}" class="db-btn h-[38px] text-white bg-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>{{ __('bank.add_bank') }}</span>
                            </a>
                        @endcan
                    </div>
                </div>

                @if (auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                <div class="table-filter-div input-group input-daterange" id="date-picker" style="">
                    <div class="p-5 mb-8">
                        <div class="row">
                            <div class="col-6 sm:col-4">
                                <label class="db-field-title">{{ __('restaurant.restaurants') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="user_id" id="user_id" class="db-field-control form-control appearance-none @error('user_id') invalid @enderror" data-url="{{ route('admin.withdraw.get-user-info') }}">
                                        <option value="0">{{ __('levels.all') }}</option>
                                        @foreach ($restaurants as $restaurant)
                                        <option value="{{ $restaurant->user->id }}">
                                            {{ $restaurant->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="flex flex-wrap gap-3 input-group-append h-full items-center">
                                    <button class="db-btn py-2 text-white bg-primary h-fit" id="date-search">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <span>{{ __('levels.search') }}</span>
                                    </button>
                                    <button class="db-btn py-2 text-white bg-gray-600 h-fit" type="button" id="refresh">
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
                    <table class="db-table striped" id="maintable" data-url="{{ route('admin.get-bank') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('bank.bank_name') }}</th>
                                <th class="db-table-head-th">{{ __('bank.account_number') }}</th>
                                <th class="db-table-head-th">{{ __('bank.mobile_agent_name') }}</th>
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
    <script src="{{ asset('js/bank/index.js') }}"></script>
@endpush
