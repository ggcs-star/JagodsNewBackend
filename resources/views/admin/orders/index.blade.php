@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('orders') }}
            </div>
        </div>
        <div class="col-12 mb-9">
            <div class="row">
                <div class="col-12 sm:col-6 xl:col-3">
                    <div class="p-4 rounded-lg flex items-center gap-4 bg-[#EE1D48]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="#EE1D48" xmlns="http://www.w3.org/2000/svg">
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
                    <div class="p-4 rounded-lg flex items-center gap-4 bg-[#3abaf4]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="#3abaf4" xmlns="http://www.w3.org/2000/svg">
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
                    <div class="p-4 rounded-lg flex items-center gap-4 bg-[#ffa426]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="#ffa426" xmlns="http://www.w3.org/2000/svg">
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
                    <div class="p-4 rounded-lg flex items-center gap-4 bg-[#47c363]">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="#47c363" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.6005 5.31L11.9505 2.27C11.3505 1.95 10.6405 1.95 10.0405 2.27L4.40047 5.31C3.99047 5.54 3.73047 5.98 3.73047 6.46C3.73047 6.95 3.98047 7.39 4.40047 7.61L10.0505 10.65C10.3505 10.81 10.6805 10.89 11.0005 10.89C11.3205 10.89 11.6605 10.81 11.9505 10.65L17.6005 7.61C18.0105 7.39 18.2705 6.95 18.2705 6.46C18.2705 5.98 18.0105 5.54 17.6005 5.31Z"></path>
                                <path d="M9.12 11.71L3.87 9.09C3.46 8.88 3 8.91 2.61 9.14C2.23 9.38 2 9.79 2 10.24V15.2C2 16.06 2.48 16.83 3.25 17.22L8.5 19.84C8.68 19.93 8.88 19.98 9.08 19.98C9.31 19.98 9.55 19.91 9.76 19.79C10.14 19.55 10.37 19.14 10.37 18.69V13.73C10.36 12.87 9.88 12.1 9.12 11.71Z"></path>
                                <path d="M19.9996 10.24V12.7C19.5196 12.56 19.0096 12.5 18.4996 12.5C17.1396 12.5 15.8096 12.97 14.7596 13.81C13.3196 14.94 12.4996 16.65 12.4996 18.5C12.4996 18.99 12.5596 19.48 12.6896 19.95C12.5396 19.93 12.3896 19.87 12.2496 19.78C11.8696 19.55 11.6396 19.14 11.6396 18.69V13.73C11.6396 12.87 12.1196 12.1 12.8796 11.71L18.1296 9.09C18.5396 8.88 18.9996 8.91 19.3896 9.14C19.7696 9.38 19.9996 9.79 19.9996 10.24Z"></path>
                                <path d="M21.98 15.67C21.16 14.66 19.91 14.02 18.5 14.02C17.44 14.02 16.46 14.39 15.69 15.01C14.65 15.83 14 17.1 14 18.52C14 19.36 14.24 20.16 14.65 20.84C14.92 21.29 15.26 21.68 15.66 22H15.67C16.44 22.64 17.43 23.02 18.5 23.02C19.64 23.02 20.67 22.6 21.46 21.9C21.81 21.6 22.11 21.24 22.35 20.84C22.76 20.16 23 19.36 23 18.52C23 17.44 22.62 16.44 21.98 15.67ZM20.76 17.96L18.36 20.18C18.22 20.31 18.03 20.38 17.85 20.38C17.66 20.38 17.47 20.31 17.32 20.16L16.21 19.05C15.92 18.76 15.92 18.28 16.21 17.99C16.5 17.7 16.98 17.7 17.27 17.99L17.87 18.59L19.74 16.86C20.04 16.58 20.52 16.6 20.8 16.9C21.09 17.21 21.07 17.68 20.76 17.96Z"></path>
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
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('order.order_details') }}</h3>
                    <div class="db-card-filter">
                        <button class="db-card-filter-btn table-filter-btn">
                            <i class="fa-solid fa-filter"></i>
                            <span>{{ __('levels.filter')}}</span>
                        </button>

                        <div class="dropdown-group">
                            <button class="db-card-filter-btn dropdown-btn">
                                <i class="fa-solid fa-file-export"></i>
                                <span>{{ __('levels.export') }}</span>
                            </button>
                            <div class="dropdown-list db-card-filter-dropdown-list">
                                <a href="javascript:void(0);" id="printBtn" data-div-id="printTableArea" class="db-card-filter-dropdown-menu">
                                    <i class="fa-solid fa-print"></i>
                                    {{ __('levels.print') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-filter-div">
                    <div class="p-5 mb-8">
                        <div class="row">
                            <div class="col-12 sm:col-6 xl:col-4">
                                <label class="db-field-title">{{ __('order.order_code') }}</label>
                                <input class="db-field-control" type="text" id="code" name="code">
                            </div>
                            <div class="col-12 sm:col-6 xl:col-4">
                                <label class="db-field-title">{{ __('order.order_type') }}</label>
                                <div class="db-field-down-arrow">
                                    <select class="db-field-control appearance-none" id="order_type" name="order_type">
                                        <option value="">--</option>
                                        @foreach (trans('orders_type') as $key => $orderType)
                                            <option value="{{ $key }}">{{ $orderType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 sm:col-6 xl:col-4">
                                <label class="db-field-title">status</label>
                                <div class="db-field-down-arrow">
                                    <select class="db-field-control appearance-none" id="status" name="status">
                                        <option value="">--</option>
                                        @foreach (trans('order_status') as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 sm:col-6 xl:col-4">
                                <label class="db-field-title">from date</label>
                                <input autocomplete="off" class="db-field-control" id="start_date" type="date"
                                    name="start_date" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                            </div>
                            <div class="col-12 sm:col-6 xl:col-4">
                                <label class="db-field-title">to date</label>
                                <input autocomplete="off" class="db-field-control" id="end_date" type="date"
                                    name="end_date" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                            </div>
                            <div class="col-12">
                                <div class="flex flex-wrap gap-3 items-center h-full">
                                    <button class="db-btn py-2 text-white bg-primary h-fit" id="date-search" type="button">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <span>{{ __('levels.search') }}</span>
                                    </button>
                                    <button class="db-btn py-2 text-white bg-gray-600 h-fit" id="refresh" type="button">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span>{{ __('order.clear') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="db-table-responsive" id="printTableArea">
                    <table class="db-table stripe" id="maintable" data-url="{{ route('admin.orders.get-orders') }}"
                        data-status="{{ \App\Enums\OrderStatus::PENDING }}"
                        data-hidecolumn="{{ auth()->user()->can('orders_show') || auth()->user()->can('orders_edit') || auth()->user()->can('orders_delete') }}">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.order_code') }}</th>
                                <th class="db-table-head-th">{{ __('levels.name') }}</th>
                                <th class="db-table-head-th">{{ __('levels.date') }}</th>
                                <th class="db-table-head-th">{{ __('levels.order_type') }}</th>
                                <th class="db-table-head-th">{{ __('levels.status') }}</th>
                                <th class="db-table-head-th">{{ __('levels.total') }}</th>
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
    <script src="{{ asset('js/orders/index.js') }}"></script>
@endpush
