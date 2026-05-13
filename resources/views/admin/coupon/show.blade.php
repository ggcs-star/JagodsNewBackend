@extends('admin.app')


@section('content')

<div class="row">
    <div class="col-12">
		<div class="custome-breadcrumb">
        {{ Breadcrumbs::render('coupons/show') }}
        </div>
    </div>

    <div class="col-12">
        <div class="grid grid-cols-1 sm:grid-cols-5 mb-4 sm:mb-0">
            <button type="button" class="db-tabBtn active" data-tab="#information">
                <i class="fa-solid fa-circle-info"></i>
                <span>{{ __('levels.coupon_info') }}</span>
            </button>
            <button type="button" class="db-tabBtn" data-tab="#usetimes">
                <i class="fa-solid fa-cube"></i>
                <span>{{ __('levels.coupon_use_times') }}</span>
            </button>
        </div>
        <div class="db-tabDiv active" id="information">
            <ul class="db-list multiple">
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.name') }}</span>
                    <span class="db-list-item-text">{{ $coupon->name }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.code') }}</span>
                    <span class="db-list-item-text">{{ $coupon->slug }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.amount') }}</span>
                    <span class="db-list-item-text">{{ $coupon->discount_type == 5 ? currencyFormat($coupon->amount) : $coupon->amount.'%' }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.minimum_order_amount') }}</span>
                    <span class="db-list-item-text">{{ currencyFormat($coupon->minimum_order_amount) }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.discount_type') }}</span>
                    <span class="db-list-item-text">{{ trans('discount_types.'.$coupon->discount_type) }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.coupon_type') }}</span>
                    <span class="db-list-item-text">{{ trans('coupon_types.'.$coupon->discount_type) }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.limit') }}</span>
                    <span class="db-list-item-text">{{ $coupon->limit }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.used') }}</span>
                    <span class="db-list-item-text">{{ $coupon->discounts->count() }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.left') }}</span>
                    <span class="db-list-item-text">{{ $coupon->limit-$coupon->discounts->count() }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.starts_at') }}</span>
                    <span class="db-list-item-text">{{ date('h:i A d/m/Y',strtotime($coupon->from_date)) }}</span>
                </li>
                <li class="db-list-item">
                    <span class="db-list-item-title">{{ __('levels.ends_at') }}</span>
                    <span class="db-list-item-text">{{ date('h:i A d/m/Y',strtotime($coupon->to_date)) }}</span>
                </li>
            </ul>
        </div>
        <div class="db-tabDiv" id="usetimes">
            <div class="db-card mb-2">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('levels.coupon_use_time_table_title') }}</h3>
                </div>
                <div class="db-table-responsive">
                    <table class="db-table table stripe">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ __('levels.slno') }}</th>
                                <th class="db-table-head-th">{{ __('levels.code') }}</th>
                                <th class="db-table-head-th">{{ __('levels.discount_amount') }}</th>
                                <th class="db-table-head-th">{{ __('levels.order_id') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if( !blank($coupon->discounts))
                            @foreach($coupon->discounts as $key => $discount)
                            <tr class="db-table-body-tr">
                                <th class="db-table-body-td font-medium" scope="row">{{$key + 1}}</th>
                                <td class="db-table-body-td">{{$coupon->slug}}</td>
                                <td class="db-table-body-td">{{$discount->amount}}</td>
                                <td class="db-table-body-td">{{$discount->order_id}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr class="db-table-body-tr">
                                <td colspan="4" class="db-table-body-td text-center text-danger">
                                    {{__('levels.not_used_yet')}}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection