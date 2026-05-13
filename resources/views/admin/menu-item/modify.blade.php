@extends('admin.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="custome-breadcrumb">
        {{ Breadcrumbs::render('menu-items/edit', $menuItem) }}
        </div>
    </div>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ $menuItem->name }}</h3>
            </div>
            <div class="db-card-body">
                <form action="{{ route('admin.menu-items.modify', $menuItem) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="db-card">
                        <div class="db-card-header">
                            <h3 class="db-card-title">{{ __('restaurant.product_variation') }}</h3>
                            <button class="db-btn h-[38px] text-white bg-primary" id="variation-add">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>add new</span>
                            </button>
                        </div>
                        <div class="db-card-body">
                            <div class="db-table-responsive">
                                <table class="db-table">
                                    <thead class="db-table-head border-none">
                                        <tr class="db-table-head-tr">
                                            <th class="db-table-head-th">Name</th>
                                            <th class="db-table-head-th">Price</th>
                                            <th class="db-table-head-th">Discount</th>
                                            <th class="db-table-head-th">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="db-table-body" id="variationTbody">
                                        @if(!blank(session('variation')))
                                            @foreach(session('variation') as $variation)
                                                <tr class="db-table-body-tr border-none">
                                                    <td class="db-table-body-td">
                                                        <input type="text" name="variation[<?=$variation?>][name]" placeholder="{{__('levels.name')}}" class="db-field-control form-control-sm !w-auto @error("variation.$variation.name") invalid @enderror" value="{{ old("variation.$variation.name") }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <input type="text" step="0.01" name="variation[<?=$variation?>][price]" placeholder="{{__('levels.price')}}" class="db-field-control form-control-sm !w-auto change-productprice @error("variation.$variation.price") invalid @enderror" value="{{ old("variation.$variation.price") }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <input type="text" step="0.01" name="variation[<?=$variation?>][discount_price]" placeholder="{{__('levels.discount_price')}}" class="db-field-control form-control-sm !w-auto change-productdiscountprice @error("variation.$variation.discount_price") invalid @enderror" value="{{ old("variation.$variation.discount_price") }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <button class="db-table-action delete removeBtn"> <i class="fa-solid fa-trash-can"></i> <span class="db-tooltip">delete</span></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @elseif(!blank($menu_item_variations))
                                            @foreach($menu_item_variations as $menu_item_variation)
                                                @php
                                                    $variation = $menu_item_variation->id;
                                                    $loopindex = $loop->index + 1;
                                                @endphp
                                                <tr class="db-table-body-tr border-none">
                                                    <td class="db-table-body-td">
                                                        <input type="text" name="variation[<?=$variation?>][name]" placeholder="{{__('levels.name')}}" class="db-field-control form-control-sm !w-auto @error("variation.$variation.name") invalid @enderror" value="{{ old("variation.$variation.name", $menu_item_variation->name) }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <input type="text" step="0.01" name="variation[<?=$variation?>][price]" placeholder="{{__('levels.price')}}" class="db-field-control form-control-sm !w-auto change-productprice @error("variation.$variation.price") invalid @enderror" value="{{ old("variation.$variation.price", $menu_item_variation->price) }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <input type="text" step="0.01" name="variation[<?=$variation?>][discount_price]" placeholder="{{__('levels.discount_price')}}" class="db-field-control form-control-sm !w-auto change-productdiscountprice @error("variation.$variation.discount_price") invalid @enderror" value="{{ old("variation.$variation.discount_price",$menu_item_variation->discount_price) }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <button class="db-table-action delete removeBtn"> <i class="fa-solid fa-trash-can"></i> <span class="db-tooltip">delete</span></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                                
                        </div>
                    </div>
                    
                    <div class="mt-4"></div>

                    <div class="db-card">
                        <div class="db-card-header">
                            <h3 class="db-card-title">{{ __('restaurant.product_option') }}</h3>
                            <button class="db-btn h-[38px] text-white bg-primary" id="option-add">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>add new</span>
                            </button>
                        </div>
                        <div class="db-card-body">
                            <div class="db-table-responsive">
                                <table class="db-table">
                                    <thead class="db-table-head border-none">
                                        <tr class="db-table-head-tr">
                                            <th class="db-table-head-th">Name</th>
                                            <th class="db-table-head-th">Price</th>
                                            <th class="db-table-head-th">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="db-table-body" id="optionTbody">
                                        @if(!blank(session('option')))
                                            @foreach(session('option') as $option)
                                                <tr class="db-table-body-tr border-none">
                                                    <td class="db-table-body-td">
                                                        <input type="text" name="option[<?=$option?>][name]" placeholder="{{__('levels.name')}}" class="db-field-control form-control-sm !w-auto @error("option.$option.name") invalid @enderror" value="{{ old("option.$option.name") }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <input type="text" step="0.01" name="option[<?=$option?>][price]" placeholder="{{__('levels.price')}}" class="db-field-control form-control-sm !w-auto change-productprice @error("option.$option.price") invalid @enderror" value="{{ old("option.$option.price") }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <button class="db-table-action delete removeBtn"> <i class="fa-solid fa-trash-can"></i> <span class="db-tooltip">delete</span></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @elseif(!blank($menu_item_options))
                                            @foreach($menu_item_options as $menu_item_option)
                                                @php
                                                    $option = $loop->index + 1;
                                                @endphp
                                                <tr class="db-table-body-tr border-none">
                                                    <td class="db-table-body-td">
                                                        <input type="text" name="option[<?=$option?>][name]" placeholder="Na{{__('levels.name')}}me" class="db-field-control form-control-sm !w-auto @error("option.$option.name") invalid @enderror" value="{{ old("option.$option.name", $menu_item_option->name) }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <input type="text" step="0.01" name="option[<?=$option?>][price]" placeholder="{{__('levels.price')}}" class="db-field-control form-control-sm !w-auto change-productprice @error("option.$option.price") invalid @enderror" value="{{ old("option.$option.price", $menu_item_option->price) }}">
                                                    </td>
                                                    <td class="db-table-body-td">
                                                        <button class="db-table-action delete removeBtn"> <i class="fa-solid fa-trash-can"></i> <span class="db-tooltip">delete</span></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>{{ __('levels.save') }}</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        @php
            $menu_item_variation_count = 0;
            if(!blank(session('variation'))) {
                $menu_item_variation_count = count(session('variation'));
            } else {
                $menu_item_variation_count = $menu_item_variations->count();
            }

            $menu_item_option_count = 0;
            if(!blank(session('option'))) {
                $menu_item_option_count = count(session('option'));
            } else {
                $menu_item_option_count = $menu_item_options->count();
            }
        @endphp

        var menu_item_variation_count  = <?=$menu_item_variation_count?>;
        var menu_item_option_count     = <?=$menu_item_option_count?>;
    </script>
    <script src="{{ asset('js/menu-item/modify.js') }}"></script>
@endpush
