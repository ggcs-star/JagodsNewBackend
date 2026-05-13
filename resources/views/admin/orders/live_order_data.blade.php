<div class="row order-table">
    <div class="xl:col-3 md:col-6 sm:col-6 col-12">
        <div class="card card-statistic-1 bg-primary">
            <div class="card-icon ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#EE1D48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.2102 7.82L12.5102 12.28C12.2002 12.46 11.8102 12.46 11.4902 12.28L3.79021 7.82C3.24021 7.5 3.10021 6.75 3.52021 6.28C3.81021 5.95 4.14021 5.68 4.49021 5.49L9.91021 2.49C11.0702 1.84 12.9502 1.84 14.1102 2.49L19.5302 5.49C19.8802 5.68 20.2102 5.96 20.5002 6.28C20.9002 6.75 20.7602 7.5 20.2102 7.82Z"></path>
                    <path d="M11.4305 14.14V20.96C11.4305 21.72 10.6605 22.22 9.98047 21.89C7.92047 20.88 4.45047 18.99 4.45047 18.99C3.23047 18.3 2.23047 16.56 2.23047 15.13V9.97C2.23047 9.18 3.06047 8.68 3.74047 9.07L10.9305 13.24C11.2305 13.43 11.4305 13.77 11.4305 14.14Z"></path>
                    <path d="M12.5703 14.14V20.96C12.5703 21.72 13.3403 22.22 14.0203 21.89C16.0803 20.88 19.5503 18.99 19.5503 18.99C20.7703 18.3 21.7703 16.56 21.7703 15.13V9.97C21.7703 9.18 20.9403 8.68 20.2603 9.07L13.0703 13.24C12.7703 13.43 12.5703 13.77 12.5703 14.14Z"></path>
                </svg>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('order.total_order') }}</h4>
                </div>
                <div class="card-body">
                    {{ $total_order }}
                </div>
            </div>
        </div>
    </div>
    <div class="xl:col-3 md:col-6 sm:col-6 col-12">
        <div class="card card-statistic-2">
            <div class="card-icon ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#3abaf4" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.6005 5.31003L11.9505 2.27003C11.3505 1.95003 10.6405 1.95003 10.0405 2.27003L4.40047 5.31003C3.99047 5.54003 3.73047 5.98003 3.73047 6.46003C3.73047 6.95003 3.98047 7.39003 4.40047 7.61003L10.0505 10.65C10.3505 10.81 10.6805 10.89 11.0005 10.89C11.3205 10.89 11.6605 10.81 11.9505 10.65L17.6005 7.61003C18.0105 7.39003 18.2705 6.95003 18.2705 6.46003C18.2705 5.98003 18.0105 5.54003 17.6005 5.31003Z"></path>
                    <path d="M9.12 11.71L3.87 9.09003C3.46 8.88003 3 8.91003 2.61 9.14003C2.23 9.38003 2 9.79003 2 10.24V15.2C2 16.06 2.48 16.83 3.25 17.22L8.5 19.84C8.68 19.93 8.88 19.98 9.08 19.98C9.31 19.98 9.55 19.91 9.76 19.79C10.14 19.55 10.37 19.14 10.37 18.69V13.73C10.36 12.87 9.88 12.1 9.12 11.71Z"></path>
                    <path d="M19.9996 10.24V12.7C19.5196 12.56 19.0096 12.5 18.4996 12.5C17.1396 12.5 15.8096 12.97 14.7596 13.81C13.3196 14.94 12.4996 16.65 12.4996 18.5C12.4996 18.99 12.5596 19.48 12.6896 19.95C12.5396 19.93 12.3896 19.87 12.2496 19.78C11.8696 19.55 11.6396 19.14 11.6396 18.69V13.73C11.6396 12.87 12.1196 12.1 12.8796 11.71L18.1296 9.09003C18.5396 8.88003 18.9996 8.91003 19.3896 9.14003C19.7696 9.38003 19.9996 9.79003 19.9996 10.24Z"></path>
                    <path d="M21.98 15.65C21.16 14.64 19.91 14 18.5 14C17.44 14 16.46 14.37 15.69 14.99C14.65 15.81 14 17.08 14 18.5C14 19.91 14.64 21.16 15.65 21.98C16.42 22.62 17.42 23 18.5 23C19.64 23 20.67 22.57 21.47 21.88C22.4 21.05 23 19.85 23 18.5C23 17.42 22.62 16.42 21.98 15.65ZM19.53 18.78C19.53 19.04 19.39 19.29 19.17 19.42L17.76 20.26C17.64 20.33 17.51 20.37 17.37 20.37C17.12 20.37 16.87 20.24 16.73 20.01C16.52 19.65 16.63 19.19 16.99 18.98L18.03 18.36V17.1C18.03 16.69 18.37 16.35 18.78 16.35C19.19 16.35 19.53 16.69 19.53 17.1V18.78Z"></path>
                </svg>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('order.order_pending') }}</h4>
                </div>
                <div class="card-body">
                    {{ $pending_order }}
                </div>
            </div>
        </div>
    </div>
    <div class="xl:col-3 md:col-6 sm:col-6 col-12">
        <div class="card card-statistic-3">
            <div class="card-icon ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#ffa426" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.9697 18V19C21.9697 20.65 21.9697 22 18.9697 22H4.96973C1.96973 22 1.96973 20.65 1.96973 19V18C1.96973 17.45 2.41973 17 2.96973 17H20.9697C21.5197 17 21.9697 17.45 21.9697 18Z"></path>
                    <path d="M14.4095 5.18002C14.4595 4.98002 14.4895 4.79002 14.4995 4.58002C14.5295 3.42002 13.8195 2.40002 12.6995 2.10002C11.0195 1.64002 9.49953 2.90002 9.49953 4.50002C9.49953 4.74002 9.52953 4.96002 9.58953 5.18002C5.97953 5.95002 3.26953 9.16002 3.26953 13V14.5C3.26953 15.05 3.71953 15.5 4.26953 15.5H19.7195C20.2695 15.5 20.7195 15.05 20.7195 14.5V13C20.7195 9.16002 18.0195 5.96002 14.4095 5.18002ZM14.9995 11.75H8.99953C8.58953 11.75 8.24953 11.41 8.24953 11C8.24953 10.59 8.58953 10.25 8.99953 10.25H14.9995C15.4095 10.25 15.7495 10.59 15.7495 11C15.7495 11.41 15.4095 11.75 14.9995 11.75Z"></path>
                </svg>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('order.order_process') }}</h4>
                </div>
                <div class="card-body">
                    {{ $process_order }}
                </div>
            </div>
        </div>
    </div>
    <div class="xl:col-3 md:col-6 sm:col-6 col-12">
        <div class="card card-statistic-4">
            <div class="card-icon ">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#47c363" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.6005 5.31L11.9505 2.27C11.3505 1.95 10.6405 1.95 10.0405 2.27L4.40047 5.31C3.99047 5.54 3.73047 5.98 3.73047 6.46C3.73047 6.95 3.98047 7.39 4.40047 7.61L10.0505 10.65C10.3505 10.81 10.6805 10.89 11.0005 10.89C11.3205 10.89 11.6605 10.81 11.9505 10.65L17.6005 7.61C18.0105 7.39 18.2705 6.95 18.2705 6.46C18.2705 5.98 18.0105 5.54 17.6005 5.31Z"></path>
                    <path d="M9.12 11.71L3.87 9.09C3.46 8.88 3 8.91 2.61 9.14C2.23 9.38 2 9.79 2 10.24V15.2C2 16.06 2.48 16.83 3.25 17.22L8.5 19.84C8.68 19.93 8.88 19.98 9.08 19.98C9.31 19.98 9.55 19.91 9.76 19.79C10.14 19.55 10.37 19.14 10.37 18.69V13.73C10.36 12.87 9.88 12.1 9.12 11.71Z"></path>
                    <path d="M19.9996 10.24V12.7C19.5196 12.56 19.0096 12.5 18.4996 12.5C17.1396 12.5 15.8096 12.97 14.7596 13.81C13.3196 14.94 12.4996 16.65 12.4996 18.5C12.4996 18.99 12.5596 19.48 12.6896 19.95C12.5396 19.93 12.3896 19.87 12.2496 19.78C11.8696 19.55 11.6396 19.14 11.6396 18.69V13.73C11.6396 12.87 12.1196 12.1 12.8796 11.71L18.1296 9.09C18.5396 8.88 18.9996 8.91 19.3896 9.14C19.7696 9.38 19.9996 9.79 19.9996 10.24Z"></path>
                    <path d="M21.98 15.67C21.16 14.66 19.91 14.02 18.5 14.02C17.44 14.02 16.46 14.39 15.69 15.01C14.65 15.83 14 17.1 14 18.52C14 19.36 14.24 20.16 14.65 20.84C14.92 21.29 15.26 21.68 15.66 22H15.67C16.44 22.64 17.43 23.02 18.5 23.02C19.64 23.02 20.67 22.6 21.46 21.9C21.81 21.6 22.11 21.24 22.35 20.84C22.76 20.16 23 19.36 23 18.52C23 17.44 22.62 16.44 21.98 15.67ZM20.76 17.96L18.36 20.18C18.22 20.31 18.03 20.38 17.85 20.38C17.66 20.38 17.47 20.31 17.32 20.16L16.21 19.05C15.92 18.76 15.92 18.28 16.21 17.99C16.5 17.7 16.98 17.7 17.27 17.99L17.87 18.59L19.74 16.86C20.04 16.58 20.52 16.6 20.8 16.9C21.09 17.21 21.07 17.68 20.76 17.96Z"></path>
                </svg>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ __('order.order_completed') }}</h4>
                </div>
                <div class="card-body">
                    {{ $completed_order }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 md:col-4">
        <div class="db-card">
            <h3 class="db-card-header">{{__('order.new_order')}}</h3>
            <div class="db-card-body">
                @if($new_orders)
                @foreach($new_orders as $order)
                <div class="p-3 ticket border-b last:border-none">
                    <div class="list-info col-8">
                        <p class="mb-1">{{__('order.just_created')}}</p>
                        <p class="mb-1">{{food_date_format($order->created_at)}}</p>
                        <h5 class="mb-1">#{{$order->id}} {{$order->restaurant->name}}</h5>
                        <p class="mb-1">{{__('levels.order_type')}}: {{ $order->getOrderType }}</p>
                        <p class="mb-1">{{ ucwords($order->user->name) ?? null }}</p>
                        <p class="mb-3">{{currencyFormat($order->total)}}</p>
                    </div>
                    <div class="col-4">
                        <a href="{{route('admin.orders.show',$order)}}" class="px-4 py-1 mb-2 text-white rounded-md bg-primary">{{__('order.details')}}</a>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 md:col-4">
        <div class="db-card">
            <h3 class="text-success db-card-header">{{__('order.accepted')}}</h3>
            <div class="db-card-body">
                @if($accepted_orders)
                @foreach($accepted_orders as $order)
                <div class="p-3 ticket border-b last:border-none">
                    <div class="list-info col-8">
                        <p class="mb-1"><b>{{__('order.accepted_by')}}&nbsp;</b>{{$order->restaurant->user->name}}</p>
                        <p class="mb-1">{{food_date_format($order->created_at)}}</p>
                        <h5 class="mb-1">#{{$order->id}} {{$order->restaurant->name}}</h5>
                        <p class="mb-1">{{__('levels.order_type')}}: {{ $order->getOrderType }}</p>
                        <p class="mb-1">{{ ucwords($order->user->name) ?? null }}</p>
                        <p class="mb-3">{{currencyFormat($order->total)}}</p>
                    </div>
                    <div class="col-4">
                        <a href="{{route('admin.orders.show',$order)}}" class="px-4 py-1 mb-2 text-white rounded-md bg-primary">{{__('order.details')}}</a>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 md:col-4">
        <div class="db-card">
            <h3 class="text-primary db-card-header">{{__('order.completed')}}</h3>
            <div class="db-card-body">
                @if($done_orders)
                @foreach($done_orders as $order)
                <div class="p-3 ticket border-b last:border-none">
                    <div class="list-info col-8">
                        <p class="mb-1">{{ trans('order_status.'.$order->status)}}</p>
                        <p class="mb-1">{{food_date_format($order->created_at)}}</p>
                        <h5 class="mb-1">#{{$order->id}} {{$order->restaurant->name}}</h5>
                        <p class="mb-1">{{__('levels.order_type')}}: {{ $order->getOrderType }}</p>
                        <p class="mb-1">{{ ucwords($order->user->name) ?? null }}</p>
                        <p class="mb-3">{{currencyFormat($order->total)}}</p>
                    </div>
                    <div class="col-4">
                        <a href="{{route('admin.orders.show',$order)}}" class="px-4 py-1 mb-2 text-white rounded-md bg-primary">{{__('order.details')}}</a>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

