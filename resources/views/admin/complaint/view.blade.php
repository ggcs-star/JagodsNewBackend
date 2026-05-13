@extends('admin.app')

@section('content')

<!--====================================
            CONTENT PART START
=====================================-->
<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('complaints/view') }}
        </div>
    </div>
    <div class="col-12" id="invoice-print">
        <div class="db-card p-4">
            <div class="flex flex-wrap items-start gap-y-2 gap-x-6 mb-5">
                <p class="text-2xl font-medium">{{ __('order.order') }}: <span class="text-heading">#{{ $report->order->order_code }}</span></p>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="text-xs capitalize h-5 leading-5 px-2 rounded-3xl text-[#F6A609] bg-[#FFEEC6]"
                        title="{{ __('levels.report_status') }}">{{ trans('report_statuses.' . $report->status) ?? null }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-5 items-end justify-between">
                <ul class="flex flex-col gap-2">
                    <li class="flex items-center gap-2">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.33301 1.3335V3.3335" stroke="#6E7191" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10.667 1.3335V3.3335" stroke="#6E7191" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M2.33301 6.06006H13.6663" stroke="#6E7191" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M14 5.66683V11.3335C14 13.3335 13 14.6668 10.6667 14.6668H5.33333C3 14.6668 2 13.3335 2 11.3335V5.66683C2 3.66683 3 2.3335 5.33333 2.3335H10.6667C13 2.3335 14 3.66683 14 5.66683Z"
                                stroke="#6E7191" stroke-width="1.5" stroke-miterlimit="10"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10.4635 9.13314H10.4694" stroke="#6E7191" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10.4635 11.1331H10.4694" stroke="#6E7191" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.99666 9.13314H8.00265" stroke="#6E7191" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M7.99666 11.1331H8.00265" stroke="#6E7191" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.52987 9.13314H5.53585" stroke="#6E7191" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5.52987 11.1331H5.53585" stroke="#6E7191" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span
                            class="text-xs">{{ \Carbon\Carbon::parse($report->order->created_at)->format('d M Y, h:i A') }}</span>
                    </li>
                    <li class="text-xs">{{ __('levels.restaurant_name') }}: <span class="text-heading">{{ $report->order->restaurant->name }}</span></li>
                    <li class="text-xs">{{ __('order.order_amount') }}: <span class="text-heading">{{ currencyFormat($report->order->total)}}</span></li>
                    @if(!blank($report->order->delivery))
                    <li class="text-xs">{{ __('levels.delivery_boy') }}: <span class="text-heading">{{ $report->order->delivery->name }}</span></li>
                    @endif
                </ul>
                <div class="flex flex-wrap gap-3">

                    <div class="relative cursor-pointer">
                        <select id="orderStatus" data-id="{{ $report->id }}" data-url="/admin/complaints/change-status/" class="text-sm cursor-pointer capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary">
                            @foreach ((new ReflectionClass(\App\Enums\ReportStatus::class))->getConstants() as $status)
                            <option value="{{ $status }}" {{$status == $report->status ? 'selected' : ''}}>{{ trans('reports'.'.'.$status) }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down cursor-pointer absolute top-1/2 right-3.5 -translate-y-1/2 text-xs text-primary"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-12 sm:col-6">
        <div class="db-card">
            <div class="db-card-body">
                <div class="flex flex-wrap gap-4 sm:gap-6">
                    <img class="w-[120px] h-[120px] object-cover rounded-lg" src="{{ $report->user->image }}" alt="avatar">
                    <div>
                        <h3 class="text-[26px] font-semibold font-client leading-[40px] capitalize">{{ $report->user->name }}</h3>
                        <div class="flex gap-3 md:gap-6">
                            <ul class="flex flex-col gap-3 py-4 border-t border-[#EFF0F6]">
                                <li class="flex items-center gap-2.5">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.333 13.6668H4.66634C2.66634 13.6668 1.33301 12.6668 1.33301 10.3335V5.66683C1.33301 3.3335 2.66634 2.3335 4.66634 2.3335H11.333C13.333 2.3335 14.6663 3.3335 14.6663 5.66683V10.3335C14.6663 12.6668 13.333 13.6668 11.333 13.6668Z" stroke="#6E7191" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M11.3337 6L9.24699 7.66667C8.56032 8.21333 7.43366 8.21333 6.74699 7.66667L4.66699 6" stroke="#6E7191" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <span class="text-sm text-[#374151]">{{ $report->user->email }}</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.6463 12.2202C14.6463 12.4602 14.593 12.7068 14.4797 12.9468C14.3663 13.1868 14.2197 13.4135 14.0263 13.6268C13.6997 13.9868 13.3397 14.2468 12.933 14.4135C12.533 14.5802 12.0997 14.6668 11.633 14.6668C10.953 14.6668 10.2263 14.5068 9.45967 14.1802C8.69301 13.8535 7.92634 13.4135 7.16634 12.8602C6.39967 12.3002 5.67301 11.6802 4.97967 10.9935C4.29301 10.3002 3.67301 9.5735 3.11967 8.8135C2.57301 8.0535 2.13301 7.2935 1.81301 6.54016C1.49301 5.78016 1.33301 5.0535 1.33301 4.36016C1.33301 3.90683 1.41301 3.4735 1.57301 3.0735C1.73301 2.66683 1.98634 2.2935 2.33967 1.96016C2.76634 1.54016 3.23301 1.3335 3.72634 1.3335C3.91301 1.3335 4.09967 1.3735 4.26634 1.4535C4.43967 1.5335 4.59301 1.6535 4.71301 1.82683L6.25967 4.00683C6.37967 4.1735 6.46634 4.32683 6.52634 4.4735C6.58634 4.6135 6.61967 4.7535 6.61967 4.88016C6.61967 5.04016 6.57301 5.20016 6.47967 5.3535C6.39301 5.50683 6.26634 5.66683 6.10634 5.82683L5.59967 6.3535C5.52634 6.42683 5.49301 6.5135 5.49301 6.62016C5.49301 6.6735 5.49967 6.72016 5.51301 6.7735C5.53301 6.82683 5.55301 6.86683 5.56634 6.90683C5.68634 7.12683 5.89301 7.4135 6.18634 7.76016C6.48634 8.10683 6.80634 8.46016 7.15301 8.8135C7.51301 9.16683 7.85967 9.4935 8.21301 9.7935C8.55967 10.0868 8.84634 10.2868 9.07301 10.4068C9.10634 10.4202 9.14634 10.4402 9.19301 10.4602C9.24634 10.4802 9.29967 10.4868 9.35967 10.4868C9.47301 10.4868 9.55967 10.4468 9.63301 10.3735L10.1397 9.8735C10.3063 9.70683 10.4663 9.58016 10.6197 9.50016C10.773 9.40683 10.9263 9.36016 11.093 9.36016C11.2197 9.36016 11.353 9.38683 11.4997 9.44683C11.6463 9.50683 11.7997 9.5935 11.9663 9.70683L14.173 11.2735C14.3463 11.3935 14.4663 11.5335 14.5397 11.7002C14.6063 11.8668 14.6463 12.0335 14.6463 12.2202Z" stroke="#6E7191" stroke-width="1.5" stroke-miterlimit="10"></path>
                                        <path d="M12.3333 6.00033C12.3333 5.60033 12.02 4.98699 11.5533 4.48699C11.1267 4.02699 10.56 3.66699 10 3.66699" stroke="#6E7191" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M14.6667 6.00016C14.6667 3.42016 12.58 1.3335 10 1.3335" stroke="#6E7191" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <span class="text-sm text-[#374151]">{{ @$report->user->phone }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 sm:col-6">
        <div class="row">
            <div class="col-12">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">{{ __('levels.description')}}</h3>
                    </div>
                    <div class="db-card-body">
                        <span class="text-sm text-[#374151]">{{ $report->description}}</span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">{{ __('levels.product_image')}}</h3>
                    </div>
                    <div class="db-card-body">
                        <img class="w-full" src="{{ $report->image }}" alt="Product Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--====================================
            CONTENT PART END
=====================================-->
@php
    $baseUrl = env('APP_URL');
@endphp
@endsection

@push('js')
<script>

    $('#orderStatus').on('change', function() {
        let orderId = $(this).data('id');
        let path = $(this).data('url');
        let status = $(this).val();
        let url = "{{$baseUrl}}" + path + orderId + "/" + status;
        
        if (status) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    location.reload();
                }
            });
        } else {
            console.log('Something went wrong!');
        }
        
    });

</script>
@endpush
