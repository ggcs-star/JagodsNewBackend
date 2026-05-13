@extends('admin.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('menu-items/view') }}
            </div>
        </div>

        <div class="col-12">
            <div class="grid grid-cols-1 sm:grid-cols-5 mb-4 sm:mb-0">
                <button type="button" class="db-tabBtn active" data-tab="#information">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>{{ __('levels.coupon_info') }}</span>
                </button>
                <button type="button" class="db-tabBtn" data-tab="#image">
                    <i class="fa-solid fa-cube"></i>
                    <span>{{ __('levels.image') }}</span>
                </button>
            </div>
            <div class="db-tabDiv active" id="information">
                <ul class="db-list multiple">
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.name') }}</span>
                        <span class="db-list-item-text">{{ $menuItem->name }}</span>
                    </li>
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.status') }}</span>
                        <span class="db-list-item-text">{!! $menuItem->statusName !!}</span>
                    </li>
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.created_date') }}</span>
                        <span class="db-list-item-text">{{ $menuItem->created_at->diffForHumans() }}</span>
                    </li>
                    <li class="db-list-item">
                        <span class="db-list-item-title">{{ __('levels.description') }}</span>
                        <span class="db-list-item-text">{{ strip_tags($menuItem->description) }}</span>
                    </li>
                   
                </ul>
            </div>
            <div class="db-tabDiv" id="image">
                @if(!blank($menuItem->image))
                    <div class="col-lg-4 sm:col-4">
                        <div class="db-card p-3">
                            <img class="d-block w-100 h-232 rounded" src="{{ $menuItem->image }}">
                        </div>  
                    </div>
                @endif
            </div>
    
        </div>

    </div>
@endsection
