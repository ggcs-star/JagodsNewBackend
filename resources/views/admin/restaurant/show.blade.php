@extends('admin.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('restaurant/view') }}
            </div>
        </div>

        <div class="col-12 mb-9">
            <div class="row">
                <div class="col-12 sm:col-6 xl:col-3">
                    <div class="db-card p-4 rounded-lg flex items-center gap-4 bg-[#FF4F99]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white text black">
                            <svg class="fill-[#FF4F99]" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.2102 7.82L12.5102 12.28C12.2002 12.46 11.8102 12.46 11.4902 12.28L3.79021 7.82C3.24021 7.5 3.10021 6.75 3.52021 6.28C3.81021 5.95 4.14021 5.68 4.49021 5.49L9.91021 2.49C11.0702 1.84 12.9502 1.84 14.1102 2.49L19.5302 5.49C19.8802 5.68 20.2102 5.96 20.5002 6.28C20.9002 6.75 20.7602 7.5 20.2102 7.82Z"></path>
                                <path d="M11.4305 14.14V20.96C11.4305 21.72 10.6605 22.22 9.98047 21.89C7.92047 20.88 4.45047 18.99 4.45047 18.99C3.23047 18.3 2.23047 16.56 2.23047 15.13V9.97C2.23047 9.18 3.06047 8.68 3.74047 9.07L10.9305 13.24C11.2305 13.43 11.4305 13.77 11.4305 14.14Z"></path>
                                <path d="M12.5703 14.14V20.96C12.5703 21.72 13.3403 22.22 14.0203 21.89C16.0803 20.88 19.5503 18.99 19.5503 18.99C20.7703 18.3 21.7703 16.56 21.7703 15.13V9.97C21.7703 9.18 20.9403 8.68 20.2603 9.07L13.0703 13.24C12.7703 13.43 12.5703 13.77 12.5703 14.14Z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('order.total_order') }}</h3>
                            <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ $total_order }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 sm:col-6 xl:col-3">
                    <div class="db-card p-4 rounded-lg flex items-center gap-4 bg-[#8262FE]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white text black">
                            <svg class="fill-[#8262FE]" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.6005 5.31003L11.9505 2.27003C11.3505 1.95003 10.6405 1.95003 10.0405 2.27003L4.40047 5.31003C3.99047 5.54003 3.73047 5.98003 3.73047 6.46003C3.73047 6.95003 3.98047 7.39003 4.40047 7.61003L10.0505 10.65C10.3505 10.81 10.6805 10.89 11.0005 10.89C11.3205 10.89 11.6605 10.81 11.9505 10.65L17.6005 7.61003C18.0105 7.39003 18.2705 6.95003 18.2705 6.46003C18.2705 5.98003 18.0105 5.54003 17.6005 5.31003Z"></path>
                                <path d="M9.12 11.71L3.87 9.09003C3.46 8.88003 3 8.91003 2.61 9.14003C2.23 9.38003 2 9.79003 2 10.24V15.2C2 16.06 2.48 16.83 3.25 17.22L8.5 19.84C8.68 19.93 8.88 19.98 9.08 19.98C9.31 19.98 9.55 19.91 9.76 19.79C10.14 19.55 10.37 19.14 10.37 18.69V13.73C10.36 12.87 9.88 12.1 9.12 11.71Z"></path>
                                <path d="M19.9996 10.24V12.7C19.5196 12.56 19.0096 12.5 18.4996 12.5C17.1396 12.5 15.8096 12.97 14.7596 13.81C13.3196 14.94 12.4996 16.65 12.4996 18.5C12.4996 18.99 12.5596 19.48 12.6896 19.95C12.5396 19.93 12.3896 19.87 12.2496 19.78C11.8696 19.55 11.6396 19.14 11.6396 18.69V13.73C11.6396 12.87 12.1196 12.1 12.8796 11.71L18.1296 9.09003C18.5396 8.88003 18.9996 8.91003 19.3896 9.14003C19.7696 9.38003 19.9996 9.79003 19.9996 10.24Z"></path>
                                <path d="M21.98 15.65C21.16 14.64 19.91 14 18.5 14C17.44 14 16.46 14.37 15.69 14.99C14.65 15.81 14 17.08 14 18.5C14 19.91 14.64 21.16 15.65 21.98C16.42 22.62 17.42 23 18.5 23C19.64 23 20.67 22.57 21.47 21.88C22.4 21.05 23 19.85 23 18.5C23 17.42 22.62 16.42 21.98 15.65ZM19.53 18.78C19.53 19.04 19.39 19.29 19.17 19.42L17.76 20.26C17.64 20.33 17.51 20.37 17.37 20.37C17.12 20.37 16.87 20.24 16.73 20.01C16.52 19.65 16.63 19.19 16.99 18.98L18.03 18.36V17.1C18.03 16.69 18.37 16.35 18.78 16.35C19.19 16.35 19.53 16.69 19.53 17.1V18.78Z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('order.order_pending') }}</h3>
                            <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ $pending_order }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 sm:col-6 xl:col-3">
                    <div class="db-card p-4 rounded-lg flex items-center gap-4 bg-[#567DFF]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white text black">
                            <svg class="fill-[#567DFF]" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.9697 18V19C21.9697 20.65 21.9697 22 18.9697 22H4.96973C1.96973 22 1.96973 20.65 1.96973 19V18C1.96973 17.45 2.41973 17 2.96973 17H20.9697C21.5197 17 21.9697 17.45 21.9697 18Z"></path>
                                <path d="M14.4095 5.18002C14.4595 4.98002 14.4895 4.79002 14.4995 4.58002C14.5295 3.42002 13.8195 2.40002 12.6995 2.10002C11.0195 1.64002 9.49953 2.90002 9.49953 4.50002C9.49953 4.74002 9.52953 4.96002 9.58953 5.18002C5.97953 5.95002 3.26953 9.16002 3.26953 13V14.5C3.26953 15.05 3.71953 15.5 4.26953 15.5H19.7195C20.2695 15.5 20.7195 15.05 20.7195 14.5V13C20.7195 9.16002 18.0195 5.96002 14.4095 5.18002ZM14.9995 11.75H8.99953C8.58953 11.75 8.24953 11.41 8.24953 11C8.24953 10.59 8.58953 10.25 8.99953 10.25H14.9995C15.4095 10.25 15.7495 10.59 15.7495 11C15.7495 11.41 15.4095 11.75 14.9995 11.75Z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('order.order_process') }}</h3>
                            <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ $process_order }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 sm:col-6 xl:col-3">
                    <div class="db-card p-4 rounded-lg flex items-center gap-4 bg-[#A953FF]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white text black">
                            <svg class="fill-[#A953FF]" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.6005 5.31125L11.9505 2.27125C11.3505 1.95125 10.6405 1.95125 10.0405 2.27125L4.40047 5.31125C3.99047 5.54125 3.73047 5.98125 3.73047 6.46125C3.73047 6.95125 3.98047 7.39125 4.40047 7.61125L10.0505 10.6512C10.3505 10.8112 10.6805 10.8913 11.0005 10.8913C11.3205 10.8913 11.6605 10.8112 11.9505 10.6512L17.6005 7.61125C18.0105 7.39125 18.2705 6.95125 18.2705 6.46125C18.2705 5.98125 18.0105 5.54125 17.6005 5.31125Z"/>
                                <path d="M9.12 11.7106L3.87 9.09058C3.46 8.88058 3 8.91058 2.61 9.14058C2.23 9.38058 2 9.79058 2 10.2406V15.2006C2 16.0606 2.48 16.8306 3.25 17.2206L8.5 19.8406C8.68 19.9306 8.88 19.9806 9.08 19.9806C9.31 19.9806 9.55 19.9106 9.76 19.7906C10.14 19.5506 10.37 19.1406 10.37 18.6906V13.7306C10.36 12.8706 9.88 12.1006 9.12 11.7106Z"/>
                                <path d="M20.0006 10.2406V12.7006C19.5206 12.5606 19.0106 12.5006 18.5006 12.5006C17.1406 12.5006 15.8106 12.9706 14.7606 13.8106C13.3206 14.9406 12.5006 16.6506 12.5006 18.5006C12.5006 18.9906 12.5606 19.4806 12.6906 19.9506C12.5406 19.9306 12.3906 19.8706 12.2506 19.7806C11.8706 19.5506 11.6406 19.1406 11.6406 18.6906V13.7306C11.6406 12.8706 12.1206 12.1006 12.8806 11.7106L18.1306 9.09058C18.5406 8.88058 19.0006 8.91058 19.3906 9.14058C19.7706 9.38058 20.0006 9.79058 20.0006 10.2406Z"/>
                                <path d="M21.98 15.6695C21.16 14.6595 19.91 14.0195 18.5 14.0195C17.44 14.0195 16.46 14.3895 15.69 15.0095C14.65 15.8295 14 17.0995 14 18.5195C14 19.3595 14.24 20.1595 14.65 20.8395C14.92 21.2895 15.26 21.6795 15.66 21.9995H15.67C16.44 22.6395 17.43 23.0195 18.5 23.0195C19.64 23.0195 20.67 22.5995 21.46 21.8995C21.81 21.5995 22.11 21.2395 22.35 20.8395C22.76 20.1595 23 19.3595 23 18.5195C23 17.4395 22.62 16.4395 21.98 15.6695ZM20.76 17.9595L18.36 20.1795C18.22 20.3095 18.03 20.3795 17.85 20.3795C17.66 20.3795 17.47 20.3095 17.32 20.1595L16.21 19.0495C15.92 18.7595 15.92 18.2795 16.21 17.9895C16.5 17.6995 16.98 17.6995 17.27 17.9895L17.87 18.5895L19.74 16.8595C20.04 16.5795 20.52 16.5995 20.8 16.8995C21.09 17.2095 21.07 17.6795 20.76 17.9595Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('order.order_completed') }}</h3>
                            <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ $completed_order }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-4 sm:col-4 xl:col-4">
                    <div class="db-card">
                        <div class="card-body">
                            <div class="card py-4">
                                <div class="db-card-body card-profile restaurant-edit-button">
                                    <img class="profile-user-img img-responsive img-circle inline-block" src="{{ $restaurant->logo }}" alt="User profile picture">
                                    <h3>{{ $restaurant->name }}</h3>
                                    <p>
                                        {{ $restaurant->address }}
                                    </p>
                                    @isset(auth()->user()->restaurant->id)
                                        <a href="{{ route('admin.restaurant.restaurant-edit', auth()->user()->restaurant->id) }}"
                                            class="db-table-action edit modal-btn btn-sm btn-icon restaurant-edit-icon me-3" data-toggle="tooltip"
                                            data-placement="top" data-original-title="Edit"> <i class="far fa-edit"></i> <span class="db-tooltip">edit</span></a>
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="db-card p-4 mt-5">
                        <div class="card-body card-profile">
                            <img class="profile-user-img img-responsive img-circle inline-block" src="{{ $user->image }}"
                                alt="User profile picture">
                            <h3 class="text-center">{{ $user->name }}</h3>
                            <p class="text-center">
                                {{ $user->getrole->name ?? '' }}
                            </p>

                            <ul class="list-group mylist border rounded-md mt-5">
                                <li class="list-group-item border-b profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.username') }}</span>
                                    <span class="">{{ $user->username }}</span>
                                </li>
                                <li class="list-group-item border-b profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.phone') }}</span>
                                    <span class="">{{ $user->phone }}</span>
                                </li>
                                <li class="list-group-item border-b profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.email') }}</span>
                                    <span class="">{{ $user->email }}</span>
                                </li>
                                <li class="list-group-item border-b profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.address') }}</span>
                                    <span class="">{{ $user->address }}</span>
                                </li>
                                <li class="list-group-item border-b profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.deposit_amount') }}</span>
                                    <span class="">
                                        {{ isset($user->deposit->deposit_amount) ? currencyFormat($user->deposit->deposit_amount) : '' }}
                                    </span>
                                </li>
                                <li class="list-group-item border-b profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.limit_amount') }}</span>
                                    <span class="">
                                        {{ isset($user->deposit->limit_amount) ? currencyFormat($user->deposit->limit_amount) : '' }}
                                    </span>
                                </li>
                                <li class="list-group-item border-b profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.credit') }}</span>
                                    <span class="">
                                        {{ currencyFormat($user->balance->balance) }}
                                    </span>
                                </li>
                                <li class="list-group-item profile-list-group-item">
                                    <span class="font-weight-bold">{{ __('levels.status') }}</span>
                                    <span class="">{!! $user->statusName !!}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="col-8 md:col-8 sm:col-8 xl:col-8">
                    <div class="db-card">
                        <div class="db-card-header">
                            <h3>{{ __('restaurant.restaurant_information') }}</h3>
                        </div>
                        <div class="db-card-body">
                            <div class="row py-5 px-4">
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.name') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{{ $restaurant->name }}</span>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.latitude') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{{ $restaurant->lat }}</span>
                                    </div>
                                </div>
                                @if (isset($restaurant->cuisines))
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('cuisine.cuisines') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">
                                            @foreach ($restaurant->cuisines as $cuisine)
                                            <span>{{ $cuisine->name }}</span>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                            @endforeach
                                        </span>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.longitude') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{{ $restaurant->long }}</span>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.delivery_status') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{!! $restaurant->deliveryStatusName !!}</span>
                                    </div>
                                </div>

                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.current_status') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{!! $restaurant->currentStatusName !!}</span>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.pickup_status') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{!! $restaurant->picupStatusName !!}</span>
                                    </div>
                                </div>

                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.status') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{!! $restaurant->statusName !!}</span>
                                    </div>
                                </div>

                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.table_status') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{!! $restaurant->tableStatusName !!}</span>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.waiter_status') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{!! $restaurant->waiterStatusName !!}</span>
                                    </div>
                                </div>
                                
                                
                                
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.opening_time') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{{ date('h:i A', strtotime($restaurant->opening_time)) }}</span>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-6 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-full sm:w-1/2">{{ __('levels.closing_time') }}</span>
                                        <span class="db-list-item-text w-full sm:w-1/2">{{ date('h:i A', strtotime($restaurant->closing_time)) }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-12 sm:col-12 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-min sm:w-1/4">{{ __('levels.address') }}</span>
                                        <span class="db-list-item-text w-min sm:w-3/4">{{ $restaurant->address }}</span>
                                    </div>
                                </div>
                                <div class="col-12 sm:col-12 !py-1.5">
                                    <div class="db-list-item p-0">
                                        <span class="db-list-item-title w-min sm:w-1/4">{{ __('levels.description') }}</span>
                                        <span class="db-list-item-text w-min sm:w-3/4">{!! $restaurant->description !!}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="db-card mt-5">
                        <div class="db-card-header">
                            <h3>{{ __('restaurant.menu_items') }}</h3>
                        </div>
                        <div class="db-card-body">
                            <div class="db-table-responsive">
                                <table class="db-table table stripe" id="maintable" data-restaurantid="{{ $restaurant->id }}" data-url="{{ route('admin.restaurant.get-menu-items') }}" data-hidecolumn="">
                                    <thead class="db-table-head">
                                        <tr class="db-table-head-tr">
                                            <th class="db-table-head-th">{{ __('levels.name') }}</th>
                                            <th class="db-table-head-th">{{ __('levels.categories') }}</th>
                                            <th class="db-table-head-th">{{ __('levels.unit_price') }}</th>
                                            <th class="db-table-head-th">{{ __('levels.discount_price') }}</th>
                                            <th class="db-table-head-th">{{ __('levels.actions') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

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
    <script src="{{ asset('js/restaurant/menu-item.js') }}"></script>
@endpush
