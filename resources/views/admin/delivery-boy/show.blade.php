@extends('admin.app')

@section('content')

	<div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('delivery-boys/view') }}
            </div>
        </div>


        <div class="col-12 ">
            <div class="db-card">
                <div class="col-12 p-6 rounded-xl mb-8 shadow-xs bg-white">
                    <div class="flex flex-wrap gap-4 sm:gap-6">
                        <img class="w-[120px] h-[120px] object-cover rounded-lg" src="{{ $user->image }}" alt="User profile picture">
                        <div>
                            <h3 class="text-[26px] font-semibold font-client leading-[40px] capitalize">{{ $user->name }}</h3>
                            <span class="block text-sm leading-tight font-normal font-client text-[#6e7191]">{{ @$user->email}}</span>
                            <span class="text-sm leading-4 font-normal font-client uppercase text-[#6e7191]">{{ @$user->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="flex flex-col items-start sm:flex-row sm:items-center gap-1.5 mb-6">
                <button type="button" data-tab="#profile" class="profile-tabBtn active w-full justify-start sm:w-fit inline-flex items-center sm:justify-center gap-2 h-[38px] py-2 px-4 rounded-md text-[#6E7191] stroke-[#6E7191]">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 9C11.0711 9 12.75 7.32107 12.75 5.25C12.75 3.17893 11.0711 1.5 9 1.5C6.92893 1.5 5.25 3.17893 5.25 5.25C5.25 7.32107 6.92893 9 9 9Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15.4426 16.5C15.4426 13.5975 12.5551 11.25 9.00011 11.25C5.44511 11.25 2.55762 13.5975 2.55762 16.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="capitalize text-sm">Profile</span>
                </button>
                <button type="button" data-tab="#orders" class="profile-tabBtn w-full justify-start sm:w-fit inline-flex items-center sm:justify-center gap-2 h-[38px] py-2 px-4 rounded-md text-[#6E7191] stroke-[#6E7191]">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.2275 16.5H3.72754C1.47754 16.5 1.47754 15.4875 1.47754 14.25V13.5C1.47754 13.0875 1.81504 12.75 2.22754 12.75H15.7275C16.14 12.75 16.4775 13.0875 16.4775 13.5V14.25C16.4775 15.4875 16.4775 16.5 14.2275 16.5Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15.5396 9.75V12.75H2.45215V9.75C2.45215 6.87 4.48465 4.4625 7.19215 3.885C7.59715 3.795 8.01715 3.75 8.45215 3.75H9.53965C9.97465 3.75 10.4021 3.795 10.8071 3.885C13.5146 4.47 15.5396 6.87 15.5396 9.75Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10.875 3.375C10.875 3.555 10.8525 3.72 10.8075 3.885C10.4025 3.795 9.975 3.75 9.54 3.75H8.4525C8.0175 3.75 7.5975 3.795 7.1925 3.885C7.1475 3.72 7.125 3.555 7.125 3.375C7.125 2.34 7.965 1.5 9 1.5C10.035 1.5 10.875 2.34 10.875 3.375Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.25 8.25H6.75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="capitalize text-sm">orders</span>
                </button>
            </div>
        </div>

        <div id="profile" class="col-12 profile-tabDiv active">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">Basic Information</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.name') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ $user->name }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.email') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ $user->email }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.phone') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ $user->phone }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.deposit_amount') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ isset($user->deposit->deposit_amount) ? currencyFormat($user->deposit->deposit_amount) : '' }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.order_limit_amount') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ isset($user->deposit->limit_amount) ? currencyFormat($user->deposit->limit_amount) : '' }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.order_balance') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ currencyFormat(@$user->deliveryBoyAccount->balance) }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.credit') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ currencyFormat($user->balance->balance > 0 ? $user->balance->balance : 0 ) }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.username') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ $user->username }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.address') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ $user->address ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.status') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">
                                {!! $user->statusName !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="orders" class="col-12 profile-tabDiv">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">Orders Information</h3>
                </div>
                <div class="db-card-body">
                    
                    <div class="db-table-responsive">
                        <table class="db-table table stripe" id="maintable" data-url="{{ route("admin.delivery-boys.get-order-history") }}" data-deliveryboyid="{{ $user->id }}">
                            <thead class="db-table-head">
                                <tr class="db-table-head-tr">
                                    <th class="db-table-head-th">{{ __('levels.code') }}</th>
                                    <th class="db-table-head-th">{{ __('levels.name') }}</th>
                                    <th class="db-table-head-th">{{ __('levels.date') }}</th>
                                    <th class="db-table-head-th">{{ __('levels.status') }}</th>
                                    <th class="db-table-head-th">{{ __('levels.action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
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
    <script src="{{ asset('js/delivery-boy/view.js') }}"></script>
@endpush
