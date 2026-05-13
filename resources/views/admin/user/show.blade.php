@extends('admin.app')

@section('content')
    
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('user/view') }}
            </div>
        </div>

        <div class="col-12 ">
            <div class="db-card">
                <div class="col-12 p-6 rounded-xl shadow-xs bg-white">
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

        <div id="profile" class="col-12 profile-tabDiv active">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('levels.basic_information') }}</h3>
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
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.address') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ $user->address }}</span>
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
                                <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.role') }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ $user->getrole->name }}</span>
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

    </div>

@endsection