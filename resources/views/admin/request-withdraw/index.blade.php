@extends('admin.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('request-withdraw') }}
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 sm:col-6 xl:col-4">
                <div class="p-4 rounded-lg flex items-center gap-4 bg-[#FF4F99]">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#567DFF" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.75 15.92H13.4C14.05 15.92 14.59 15.34 14.59 14.64C14.59 13.77 14.28 13.6 13.77 13.42L12.76 13.07V15.92H12.75Z" fill="#FF4F99"></path>
                            <path d="M11.9701 1.89998C6.45007 1.91998 1.98007 6.40998 2.00007 11.93C2.02007 17.45 6.51007 21.92 12.0301 21.9C17.5501 21.88 22.0201 17.39 22.0001 11.87C21.9801 6.34998 17.4901 1.88998 11.9701 1.89998ZM14.2601 12C15.0401 12.27 16.0901 12.85 16.0901 14.64C16.0901 16.18 14.8801 17.42 13.4001 17.42H12.7501V18C12.7501 18.41 12.4101 18.75 12.0001 18.75C11.5901 18.75 11.2501 18.41 11.2501 18V17.42H10.8901C9.25007 17.42 7.92007 16.04 7.92007 14.34C7.92007 13.93 8.26007 13.59 8.67007 13.59C9.08007 13.59 9.42007 13.93 9.42007 14.34C9.42007 15.21 10.0801 15.92 10.8901 15.92H11.2501V12.54L9.74007 12C8.96007 11.73 7.91007 11.15 7.91007 9.35998C7.91007 7.81998 9.12007 6.57998 10.6001 6.57998H11.2501V5.99998C11.2501 5.58998 11.5901 5.24998 12.0001 5.24998C12.4101 5.24998 12.7501 5.58998 12.7501 5.99998V6.57998H13.1101C14.7501 6.57998 16.0801 7.95998 16.0801 9.65998C16.0801 10.07 15.7401 10.41 15.3301 10.41C14.9201 10.41 14.5801 10.07 14.5801 9.65998C14.5801 8.78998 13.9201 8.07998 13.1101 8.07998H12.7501V11.46L14.2601 12Z" fill="#FF4F99"></path>
                            <path d="M9.41992 9.37002C9.41992 10.24 9.72992 10.41 10.2399 10.59L11.2499 10.94V8.08002H10.5999C9.94992 8.08002 9.41992 8.66002 9.41992 9.37002Z" fill="#FF4F99"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-white">{{ __('levels.total_balance') }}</h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ currencyFormat($totalBalance) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-4">
                <div class="p-4 rounded-lg flex items-center gap-4 bg-[#8262FE]">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#567DFF" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.75 15.92H13.4C14.05 15.92 14.59 15.34 14.59 14.64C14.59 13.77 14.28 13.6 13.77 13.42L12.76 13.07V15.92H12.75Z" ></path>
                            <path d="M11.9701 1.89998C6.45007 1.91998 1.98007 6.40998 2.00007 11.93C2.02007 17.45 6.51007 21.92 12.0301 21.9C17.5501 21.88 22.0201 17.39 22.0001 11.87C21.9801 6.34998 17.4901 1.88998 11.9701 1.89998ZM14.2601 12C15.0401 12.27 16.0901 12.85 16.0901 14.64C16.0901 16.18 14.8801 17.42 13.4001 17.42H12.7501V18C12.7501 18.41 12.4101 18.75 12.0001 18.75C11.5901 18.75 11.2501 18.41 11.2501 18V17.42H10.8901C9.25007 17.42 7.92007 16.04 7.92007 14.34C7.92007 13.93 8.26007 13.59 8.67007 13.59C9.08007 13.59 9.42007 13.93 9.42007 14.34C9.42007 15.21 10.0801 15.92 10.8901 15.92H11.2501V12.54L9.74007 12C8.96007 11.73 7.91007 11.15 7.91007 9.35998C7.91007 7.81998 9.12007 6.57998 10.6001 6.57998H11.2501V5.99998C11.2501 5.58998 11.5901 5.24998 12.0001 5.24998C12.4101 5.24998 12.7501 5.58998 12.7501 5.99998V6.57998H13.1101C14.7501 6.57998 16.0801 7.95998 16.0801 9.65998C16.0801 10.07 15.7401 10.41 15.3301 10.41C14.9201 10.41 14.5801 10.07 14.5801 9.65998C14.5801 8.78998 13.9201 8.07998 13.1101 8.07998H12.7501V11.46L14.2601 12Z"></path>
                            <path d="M9.41992 9.37002C9.41992 10.24 9.72992 10.41 10.2399 10.59L11.2499 10.94V8.08002H10.5999C9.94992 8.08002 9.41992 8.66002 9.41992 9.37002Z" ></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-white">{{ __('levels.total_requested_amount') }}</h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ currencyFormat($requestedAmount) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-4">
                <div class="p-4 rounded-lg flex items-center gap-4 bg-[#567DFF]">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#567DFF" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.75 15.92H13.4C14.05 15.92 14.59 15.34 14.59 14.64C14.59 13.77 14.28 13.6 13.77 13.42L12.76 13.07V15.92H12.75Z" ></path>
                            <path d="M11.9701 1.89998C6.45007 1.91998 1.98007 6.40998 2.00007 11.93C2.02007 17.45 6.51007 21.92 12.0301 21.9C17.5501 21.88 22.0201 17.39 22.0001 11.87C21.9801 6.34998 17.4901 1.88998 11.9701 1.89998ZM14.2601 12C15.0401 12.27 16.0901 12.85 16.0901 14.64C16.0901 16.18 14.8801 17.42 13.4001 17.42H12.7501V18C12.7501 18.41 12.4101 18.75 12.0001 18.75C11.5901 18.75 11.2501 18.41 11.2501 18V17.42H10.8901C9.25007 17.42 7.92007 16.04 7.92007 14.34C7.92007 13.93 8.26007 13.59 8.67007 13.59C9.08007 13.59 9.42007 13.93 9.42007 14.34C9.42007 15.21 10.0801 15.92 10.8901 15.92H11.2501V12.54L9.74007 12C8.96007 11.73 7.91007 11.15 7.91007 9.35998C7.91007 7.81998 9.12007 6.57998 10.6001 6.57998H11.2501V5.99998C11.2501 5.58998 11.5901 5.24998 12.0001 5.24998C12.4101 5.24998 12.7501 5.58998 12.7501 5.99998V6.57998H13.1101C14.7501 6.57998 16.0801 7.95998 16.0801 9.65998C16.0801 10.07 15.7401 10.41 15.3301 10.41C14.9201 10.41 14.5801 10.07 14.5801 9.65998C14.5801 8.78998 13.9201 8.07998 13.1101 8.07998H12.7501V11.46L14.2601 12Z"></path>
                            <path d="M9.41992 9.37002C9.41992 10.24 9.72992 10.41 10.2399 10.59L11.2499 10.94V8.08002H10.5999C9.94992 8.08002 9.41992 8.66002 9.41992 9.37002Z" ></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-white">{{ __('levels.total_withdraw_amount') }}</h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ currencyFormat($withdrawAmount) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="db-card">
            @can('request-withdraw_create')
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('levels.request_withdraw') }}</h3>
                    <div class="db-card-filter">
                        <a href="{{ route('admin.request-withdraw.create') }}" class="db-btn h-[38px] text-white bg-primary">
                            <i class="fa-solid fa-circle-plus"></i>
                            <span>{{ __('levels.add_request_withdraw') }}</span>
                        </a>
                    </div>
                </div>
            @endcan
            
            <div class="db-table-responsive">
                <table class="db-table stripe" id="maintable" data-url="{{ route('admin.request-withdraw.get-request-withdraw') }}"
                data-status="{{ \App\Enums\RequestWithdrawStatus::PENDING }}"
                data-hidecolumn="{{ auth()->user()->can('request-withdraw_edit') || auth()->user()->can('request-withdraw_delete')}}">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ __('levels.name') }}</th>
                            <th class="db-table-head-th">{{ __('levels.amount') }}</th>
                            <th class="db-table-head-th">{{ __('levels.date') }}</th>
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
    <script src="{{ asset('js/requestwithdraw/index.js') }}"></script>
@endpush
