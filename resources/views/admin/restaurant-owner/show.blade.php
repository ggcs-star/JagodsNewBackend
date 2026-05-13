@extends('admin.app')

@section('content')
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('restaurant-owners/view') }}
        </div>
    </div>
    <div class="p-6 rounded-xl mb-8 shadow-xs bg-white">
        <div class="flex flex-wrap gap-4 sm:gap-6">
            <img class="w-[120px] h-[120px] object-cover rounded-lg" src="{{ $user->image }}" alt="avatar">
            <div>
                <h3 class="text-[26px] font-semibold font-client leading-[40px] capitalize">{{ $user->name }}</h3>
                <label class="p-0.5 px-2 rounded text-[10px] leading-4 font-medium font-client uppercase text-[#E89806] bg-[#FFF5DE]" style="margin-bottom: 8px;">{{ $user->roles->first()->name ?? '' }}</label>
                
                <span class="block text-sm leading-tight font-normal font-client text-[#6e7191]">{{ $user->email ?? '' }}</span>
                <span class="text-sm leading-4 font-normal font-client uppercase text-[#6e7191]">{{ $user->phone ?? '' }}</span>
            </div>
        </div>
    </div>
    <div id="profile">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">Basic Information</h3>
            </div>
            <div class="db-card-body">
                <div class="row py-2">
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.first_name') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ $user->first_name }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.last_name') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ $user->last_name }}</span>
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
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.username') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ $user->username }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.address') }}</span>
                            <span class="db-list-item-text w-full sm:w-1/2">{{ $user->address }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.deposit_amount') }}</span>
                            <span
                                class="db-list-item-text w-full sm:w-1/2">{{ currencyFormat($user->deposit->deposit_amount ?? '') }}</span>
                        </div>
                    </div>
                    <div class="col-12 sm:col-6 !py-1.5">
                        <div class="db-list-item p-0">
                            <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.credit') }}</span>
                            <span
                                class="db-list-item-text w-full sm:w-1/2">{{ currencyFormat($user->balance->balance > 0 ? $user->balance->balance : 0) }}</span>
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
@endsection
