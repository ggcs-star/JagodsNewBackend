@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('push-notification') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ __('push_notification.push_notification') }}</h3>
                    @can('push-notification_create')
                        <div class="db-card-filter">
                            <a href="{{ route('admin.push-notification.create') }}" class="db-btn h-[38px] text-white bg-primary">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>{{ __('push_notification.add_push_notification') }}</span>
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="maintable"
                        data-url="{{ route('admin.push-notification.get-notification') }}"
                        data-status="{{ \App\Enums\Status::ACTIVE }}"
                        data-hidecolumn="{{ auth()->user()->can('push-notification_delete') }}">
                        <thead>
                            <tr>
                                <th class="db-table-head-th">{{ __('levels.title') }}</th>
                                <th class="db-table-head-th">{{ __('levels.description') }}</th>
                                <th class="db-table-head-th">{{ __('levels.type') }}</th>
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
    <script src="{{ asset('js/push-notification/index.js') }}"></script>
@endpush
